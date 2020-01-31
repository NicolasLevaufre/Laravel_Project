<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
            'name' =>$faker->name,
            'stock'=>$faker->numberBetween($min = 0, $max = 2000),
            'price'=>$faker->numberBetween($min = 1, $max = 500)
    ];
});
