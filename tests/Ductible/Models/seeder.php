<?php

use Faker\Generator;
use Ductible\Models\Toy;
use Ductible\Models\Wife;
use Ductible\Models\Child;
use Ductible\Models\Husband;
use Illuminate\Database\Eloquent\Factory;

$factory = App::make(Factory::class);

$factory->define(Husband::class, function (Generator $faker) {
    return [
        'name' => "{$faker->firstNameMale} {$faker->lastName}",
    ];
});

$factory->define(Wife::class, function (Generator $faker) {
    return [
        'name' => "{$faker->firstNameFemale} {$faker->lastName}",
    ];
});

$factory->define(Child::class, function (Generator $faker) {
    return [
        'name' => $faker->name,
    ];
});

$factory->define(Toy::class, function (Generator $faker) {
    return [
        'name' => "{$faker->colorName} {$faker->word}",
    ];
});

factory(Husband::class, 100)->create()->each(function ($husband) {
    $wife = factory(Wife::class)->make();

    $husband->wife()->save($wife);

    factory(Child::class, 2)->create()->each(function ($child) use ($husband, $wife) {
        $husband->children()->save($child);
        $wife->children()->save($child);

        factory(Toy::class, 5)->create()->each(function ($toy) use ($child) {
            $child->toys()->save($toy);
        });
    });
});
