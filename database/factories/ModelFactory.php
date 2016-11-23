<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Motor\Backend\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name'           => $faker->name,
        'email'          => $faker->unique()->safeEmail,
        'password'       => $password ?: $password = bcrypt('secret'),
        'api_token'      => str_random(60),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Motor\Backend\Models\Role::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name
    ];
});

$factory->define(Motor\Backend\Models\Language::class, function (Faker\Generator $faker) {
    return [
        'iso_639_1'    => str_random(2),
        'english_name' => $faker->word,
        'native_name'  => $faker->word
    ];
});

$factory->define(Motor\Backend\Models\Client::class, function (Faker\Generator $faker) {
    return [
        'name'       => $faker->sentence,
        'created_by' => 1,
        'updated_by' => 1,
    ];
});

$factory->define(Motor\Backend\Models\Permission::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word . '.' . $faker->word
    ];
});

$factory->define(Motor\Backend\Models\PermissionGroup::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word
    ];
});

$factory->define(Motor\Backend\Models\EmailTemplate::class, function (Faker\Generator $faker) {
    return [
        'client_id'  => 1,
        'name'       => $faker->sentence,
        'subject'    => $faker->sentence,
        'body_text'  => $faker->paragraph(1),
        'body_html'  => $faker->paragraph(1),
        'created_by' => 1,
        'updated_by' => 1,
    ];
});