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
    $this->seed(1);
    }

    /**
     * @param $record_count
     * @param int $i=0
     */

    public function seed($record_count, int $i=0){
        while ($i < $record_count) {
            $inputs = Blog::fillItem(false);
            $item = new Blog();
            $item->fill($inputs);
            $item->save();
            $i++;
        }
    }


}
