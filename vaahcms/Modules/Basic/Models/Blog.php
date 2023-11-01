<?php namespace VaahCms\Modules\Basic\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Faker\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use WebReinvent\VaahCms\Entities\User;
use WebReinvent\VaahCms\Libraries\VaahSeeder;
use WebReinvent\VaahCms\Traits\CrudWithUuidObservantTrait;
use function Laravel\Prompts\search;

/**
 * @method static where(string $string, mixed $title)
 * @property mixed|string slug
 */
class Blog extends Model
{

    use SoftDeletes;
    use CrudWithUuidObservantTrait;

    //-------------------------------------------------
    protected $table = 'vh_blogs';
    //-------------------------------------------------
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    //-------------------------------------------------
    protected $fillable = [
        'uuid',
        'title',
        'slug',
        'content',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    //-------------------------------------------------
    protected $fill_except = [

    ];
    //-------------------------------------------------
    protected function serializeDate(DateTimeInterface $date): string
    {
        $date_time_format = config('settings.global.datetime_format');
        return $date->format($date_time_format);
    }
    //-------------------------------------------------
    public function getContentAttribute($value): string
    {
        return ucfirst($value);
    }
    //-------------------------------------------------
    public function getTitleAttribute($value): string
    {
        return ucfirst($value);
    }
    //-------------------------------------------------
    public static function getUnFillableColumns(): array
    {
        return [
            'uuid',
            'created_by',
            'updated_by',
            'deleted_by',
        ];
    }
    //-------------------------------------------------
    public static function getFillableColumns(): array
    {
        $model = new self();
        $except = $model->fill_except;
        $fillable_columns = $model->getFillable();
        return array_diff(
            $fillable_columns, $except
        );
    }
    //-------------------------------------------------
    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class,
            'created_by', 'id'
        )->select('id', 'uuid', 'first_name', 'last_name', 'email');
    }
    //-------------------------------------------------
    public static function getEmptyItem(): array
    {
        $model = new self();
        $fillable = $model->getFillable();
        $empty_item = [];
        foreach ($fillable as $column) {
            $empty_item[$column] = null;
        }
        return $empty_item;
    }
    //-------------------------------------------------
    public function updatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class,
            'updated_by', 'id'
        )->select('id', 'uuid', 'first_name', 'last_name', 'email');
    }
    //-------------------------------------------------
    public function deletedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class,
            'deleted_by', 'id'
        )->select('id', 'uuid', 'first_name', 'last_name', 'email');
    }
    //-------------------------------------------------
    public function getTableColumns(): array
    {
        return $this->getConnection()
            ->getSchemaBuilder()
            ->getColumnListing($this->getTable());
    }
    //-------------------------------------------------
    public function scopeExclude($query, $columns)
    {
        return $query->select(array_diff($this->getTableColumns(), $columns));
    }
    //-------------------------------------------------
    public function scopeBetweenDates($query, $from, $to)
    {

        if ($from) {
            $from = \Carbon::parse($from)
                ->startOfDay()
                ->toDateTimeString();
        }

        if ($to) {
            $to = \Carbon::parse($to)
                ->endOfDay()
                ->toDateTimeString();
        }

        $query->whereBetween('updated_at', [$from, $to]);
    }
    //-------------------------------------------------
    public static function createItem($request): array
    {
        $inputs = $request->all();
        $validation = self::validation($inputs);
        if (!$validation['success']) {
            return $validation;
        }
        // check if title exist
        $item = self::where('title', $inputs['title'])->withTrashed()->first();
        if ($item) {
            $response['success'] = false;
            $response['messages'][] = "This title is already exist.";
            return $response;
        }
        // check if slug exist
        $item = self::where('slug', $inputs['slug'])->withTrashed()->first();
        if ($item) {
            $response['success'] = false;
            $response['messages'][] = "This slug is already exist.";
            return $response;
        }
        $item = new self();
        $item->fill($inputs);
        $item->slug = Str::slug($inputs['slug']);
        $item->save();

        $response = self::getItem($item->id);
        $response['messages'][] = 'Saved successfully.';
        return $response;

    }
    //-------------------------------------------------
    public function scopeGetSorted($query, $filter)
    {

        if (!isset($filter['sort'])) {
            return $query->orderBy('id', 'asc');
        }
        $sort = $filter['sort'];
        $direction = Str::contains($sort, ':');

        if (!$direction) {
            return $query->orderBy($sort, 'asc');
        }

        $sort = explode(':', $sort);

        return $query->orderBy($sort[0], $sort[1]);
    }
    //-------------------------------------------------
    public function scopeTrashedFilter($query, $filter)
    {

        if (!isset($filter['trashed'])) {
            return $query;
        }
        $trashed = $filter['trashed'];

        if ($trashed === 'include') {
            return $query->withTrashed();
        } else if ($trashed === 'only') {
            return $query->onlyTrashed();
        }

    }
    //-------------------------------------------------
    public function scopeSearchFilter($query, $filter)
    {
        if (!isset($filter['q'])) {
            return $query;
        }
        $search = $filter['q'];
        $query->where(function ($q) use ($search) {
            $q->where('title', 'LIKE', '%' . $search . '%')
                ->orWhere('slug', 'LIKE', '%' . $search . '%')
                ->orWhereHas('createdByUser', function ($userQuery) use ($search) {
                    $userQuery->where('first_name', 'LIKE', '%' . $search . '%');
                });
        });
    }
    //-------------------------------------------------
    public static function getList($request): array
    {
        $list = self::getSorted($request->filter);
        $list->trashedFilter($request->filter);
        $list->searchFilter($request->filter);

        $rows = config('vaahcms.per_page');

        if ($request->has('rows')) {
            $rows = $request->rows;
        }
        $list = $list->with(['createdByUser']);
        $list = $list->paginate($rows);

        $response['success'] = true;
        $response['data'] = $list;
        return $response;
    }
    //-------------------------------------------------
    public static function updateList($request): array
    {

        $inputs = $request->all();

        $rules = array(
            'type' => 'required',
        );
        $messages = array(
            'type.required' => 'Action type is required',
        );
        $validator = \Validator::make($inputs, $rules, $messages);
        if ($validator->fails()) {

            $errors = errorsToArray($validator->errors());
            $response['success'] = false;
            $response['errors'] = $errors;
            return $response;
        }
        if (isset($inputs['items'])) {
            $items_id = collect($inputs['items'])
                ->pluck('id')
                ->toArray();
        }
        $items = self::whereIn('id', $items_id)
            ->withTrashed()->get();

        foreach ($items as $item) {
            if ($item->created_by !== Auth::id()) {
                $response['success'] = false;
                $response['errors'][] = trans("vaahcms::messages.permission_denied");
                return $response;
            }
        }
        switch ($inputs['type']) {
            case 'trash':
                self::whereIn('id', $items_id)->delete();
                break;
            case 'restore':
                self::whereIn('id', $items_id)->restore();
                break;
        }
        $response['success'] = true;
        $response['data'] = $items;
        $response['messages'][] = 'Action was successful.';
        return $response;
    }
    //-------------------------------------------------
    public static function deleteList($request): array
    {
        $inputs = $request->all();
        $rules = array(
            'type' => 'required',
            'items' => 'required',
        );

        $messages = array(
            'type.required' => 'Action type is required',
            'items.required' => 'Select items',
        );

        $validator = \Validator::make($inputs, $rules, $messages);
        if ($validator->fails()) {

            $errors = errorsToArray($validator->errors());
            $response['success'] = false;
            $response['errors'] = $errors;
            return $response;
        }

        $items_id = collect($inputs['items'])->pluck('id')->toArray();
        $items = self::whereIn('id', $items_id)->get();

        // Checking permission for each item
        foreach ($items as $item) {
            if ($item->created_by !== Auth::id()) {
                $response['success'] = false;
                $response['errors'][] = trans("vaahcms::messages.permission_denied");
                return $response;
            }
        }
        self::whereIn('id', $items_id)->forceDelete();

        $response['success'] = true;
        $response['data'] = true;
        $response['messages'][] = 'Action was successful.';

        return $response;
    }
    //-------------------------------------------------
    public static function listAction($request, $type): array
    {
        $inputs = $request->all();

        if (isset($inputs['items'])) {
            $items_id = collect($inputs['items'])
                ->pluck('id')
                ->toArray();

            $items = self::whereIn('id', $items_id)
                ->withTrashed()->get();
            foreach ($items as $item) {
                if ($item->created_by !== Auth::id()) {
                    $response['success'] = false;
                    $response['errors'][] = trans("vaahcms::messages.permission_denied");
                    return $response;
                }
            }
        }


        $list = self::query();

        if ($request->has('filter')) {
            $list->getSorted($request->filter);
            $list->trashedFilter($request->filter);
            $list->searchFilter($request->filter);
        }
        switch ($type) {
            case 'trash':
                if (isset($items_id) && count($items_id) > 0) {
                    self::whereIn('id', $items_id)->delete();
                }
                break;
            case 'restore':
                if (isset($items_id) && count($items_id) > 0) {
                    self::whereIn('id', $items_id)->restore();
                }
                break;
            case 'delete':
                if (isset($items_id) && count($items_id) > 0) {
                    self::whereIn('id', $items_id)->forceDelete();
                }
                break;
            case 'trash-all':
                foreach ($list->get() as $item) {
                    if ($item->created_by !== Auth::id()) {
                        $response['success'] = false;
                        $response['errors'][] = trans("vaahcms::messages.permission_denied");
                        return $response;
                    }
                }
                $list->delete();
                break;
            case 'restore-all':
                $list->restore();
                break;
            case 'delete-all':
                foreach ($list->get() as $item) {
                    if ($item->created_by !== Auth::id()) {
                        $response['success'] = false;
                        $response['errors'][] = trans("vaahcms::messages.permission_denied");
                        return $response;
                    }
                }
                $list->forceDelete();
                break;
            case 'create-100-records':
            case 'create-1000-records':
            case 'create-5000-records':
            case 'create-10000-records':

                if (!config('basic.is_dev')) {
                    $response['success'] = false;
                    $response['errors'][] = 'User is not in the development environment.';

                    return $response;
                }

                preg_match('/-(.*?)-/', $type, $matches);

                if (count($matches) !== 2) {
                    break;
                }
                self::seedSampleItems($matches[1]);
                break;
        }

        $response['success'] = true;
        $response['data'] = true;
        $response['messages'][] = 'Action was successful.';

        return $response;
    }
    //-------------------------------------------------
    public static function getItem($id): array
    {
        $item = self::where('id', $id)
            ->with(['createdByUser', 'updatedByUser', 'deletedByUser'])
            ->withTrashed()
            ->first();

        if (!$item) {
            $response['success'] = false;
            $response['errors'][] = 'Record not found with ID: ' . $id;
            return $response;
        }
        if ($item->created_by != Auth::id()) {
            $response['success'] = false;
            $response['errors'][] = trans("vaahcms::messages.permission_denied");
            return $response;
        }
        $response['success'] = true;
        $response['data'] = $item;
        return $response;

    }
    //-------------------------------------------------
    public static function updateItem($request, $id): array
    {
        $inputs = $request->all();

        $validation = self::validation($inputs);
        if (!$validation['success']) {
            return $validation;
        }

        // check if name exist
        $item = self::where('id', '!=', $id)
            ->withTrashed()
            ->where('title', $inputs['title'])->first();

        if ($item) {
            $response['success'] = false;
            $response['errors'][] = "This name is already exist.";
            return $response;
        }

        // check if slug exist
        $item = self::where('id', '!=', $id)
            ->withTrashed()
            ->where('slug', $inputs['slug'])->first();

        if ($item) {
            $response['success'] = false;
            $response['errors'][] = "This slug is already exist.";
            return $response;
        }


        $item = self::where('id', $id)->withTrashed()->first();
        $item->fill($inputs);
        $item->slug = Str::slug($inputs['slug']);
        $item->save();

        $response = self::getItem($item->id);
        $response['messages'][] = 'Saved successfully.';
        return $response;

    }
    //-------------------------------------------------
    public static function deleteItem($id): array
    {
        $item = self::where('id', $id)->withTrashed()->first();
        if (!$item) {
            $response['success'] = false;
            $response['errors'][] = 'Record does not exist.';
            return $response;
        }
        $item->forceDelete();

        $response['success'] = true;
        $response['data'] = [];
        $response['messages'][] = 'Record has been deleted';

        return $response;
    }
    //-------------------------------------------------
    public static function itemAction($id, $type): array
    {
        switch ($type) {
            case 'trash':
                self::where('id', $id)
                    ->withTrashed()
                    ->delete();
                break;
            case 'restore':
                self::where('id', $id)
                    ->withTrashed()
                    ->restore();
                break;
        }

        return self::getItem($id);
    }
    //-------------------------------------------------
    public static function validation($inputs): array
    {

        $rules = array(
            'title' => 'required|max:150',
            'slug' => 'required|max:150',
            'content' => 'required|max:10000'
        );

        $validator = \Validator::make($inputs, $rules);
        if ($validator->fails()) {
            $messages = $validator->errors();
            $response['success'] = false;
            $response['errors'] = $messages->all();
            return $response;
        }

        $response['success'] = true;
        return $response;

    }
    //-------------------------------------------------
    public static function seedSampleItems($records = 100)
    {

        $i = 0;

        while ($i < $records) {
            $inputs = self::fillItem(false);

            $item = new self();
            $item->fill($inputs);
            $item->save();
            $i++;

        }

    }
    //-------------------------------------------------
    public static function fillItem($is_response_return = true)
    {
        $request = new Request([
            'model_namespace' => self::class,
            'except' => self::getUnFillableColumns()
        ]);
        $fillable = VaahSeeder::fill($request);
        if (!$fillable['success']) {
            return $fillable;
        }
        $inputs = $fillable['data']['fill'];

        $faker = Factory::create();
        if (!$is_response_return) {
            return $inputs;
        }
        $response['success'] = true;
        $response['data']['fill'] = $inputs;
        return $response;
    }
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
}
