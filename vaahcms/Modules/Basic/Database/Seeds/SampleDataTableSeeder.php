<?php
namespace VaahCms\Modules\Basic\Database\Seeds;


use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\NoReturn;
use VaahCms\Modules\Basic\Models\Blog;
use WebReinvent\VaahCms\Libraries\VaahSeeder;

class SampleDataTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    #[NoReturn] public function run()
    {
//        $request = new Request([
//            'model_namespace' => Blog::class
//        ]);
//        $fillable = VaahSeeder::fill($request);
//        $inputs = $fillable['data']['fill'];
        $i = 0;
        $records = 15;
        while ($i < $records) {
            $inputs = Blog::fillItem(false);
            $item = new Blog();
            $item->fill($inputs);
            $item->save();
            $i++;
        }
    }


}
