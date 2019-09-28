<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Category;
use App\Models\Product;
use App\Models\People;
use App\Models\Provider;
use App\Models\Role;
use App\Models\Purchase;
use App\Models\Sale;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        User::truncate();
        Category::truncate();
        Product::truncate();
        People::truncate();
        Provider::truncate();
        Role::truncate();
        Purchase::truncate();
        Sale::truncate();

        $this->call(UsersTableSeeder::class);

        factory(Category::class, 10)->create();
        factory(Product::class, 10)->create();
        factory(People::class, 10)->create();
        factory(Provider::class, 10)->create();

    }
}
