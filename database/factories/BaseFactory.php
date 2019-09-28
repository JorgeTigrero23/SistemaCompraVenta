<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Models\Category::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->text(100)
    ];
});

$factory->define(App\Models\Product::class, function (Faker $faker) {
    return [
        'category_id' => App\Models\Category::all()->random()->id,
        'barcode' => $faker->unique()->ean13,
        'name' => $faker->word,
        'description' => $faker->text(100),
        'price_sale' => $faker->randomFloat(2, 1, 1000),
        'stock' => 250,
        'stock_min' => 10,
    ];
});

$factory->define(App\Models\People::class, function (Faker $faker) {
    return [
        'name' =>$faker->name,
        'document_type' => $faker->randomElement(['DNI', 'RUC']),
        'document_number' => $faker->unique()->isbn10,
        'address' => $faker->address,
        'phone' => $faker->e164PhoneNumber,
        'email' => $faker->unique()->safeEmail
    ];
});

$factory->define(App\Models\Provider::class, function (Faker $faker) {
    return [
        'id' => App\Models\People::all()->random()->id,
        'contact' => $faker->lastName .' '.$faker->firstName,
        'contact_phone' => $faker->e164PhoneNumber
    ];
});
