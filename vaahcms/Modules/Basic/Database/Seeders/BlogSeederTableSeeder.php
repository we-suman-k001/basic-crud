<?php namespace VaahCms\Modules\Basic\Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class BlogSeederTableSeeder extends Seeder
{

    public function run()
    {
        $faker = Faker::create();
        $data = [];

        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                'title' => $faker->sentence,
                'content' => $faker->paragraph,
                'author' => $faker->name,
                'created_at' => $faker->dateTime,
                'updated_at' => $faker->dateTime,
            ];
        }

        return $data;
	}

}
