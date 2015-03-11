<?php namespace Modules\Product\Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\ProgressBar;

class ProductDatabaseSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();
        $faker = Factory::create();
        $products = [];
        $progressBar = new ProgressBar($this->command->getOutput(), 50);
        $progressBar->start();
        for ($i = 0; $i < 50; $i ++) {
            $products[] = [
                'name' => $faker->name,
                'description' => $faker->text(5),
                'price' => $faker->randomNumber(5),
                'delivery' => $faker->randomNumber(5),
                'tax_rate' => '21',
                'created_at' => new \DateTime('NOW'),
                'updated_at' => new \DateTime('NOW'),
            ];
            $progressBar->advance();
        }
        $progressBar->finish();
        DB::table('products')->insert($products);
    }
}
