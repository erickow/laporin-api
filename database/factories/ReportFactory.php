<?php

use Faker\Generator as Faker;



$factory->define(App\Models\Report::class, function (Faker $faker) {
  $faker->addProvider(new \Faker\Provider\ms_MY\Address($faker));
  return [
      'type_id' => $faker->numberBetween($min = 0, $max = 3),
      'description' => $faker->text($maxNbChars = 250)  ,
      'location' => $faker->townState,
      'long' => $faker->longitude($min = 7.4, $max = 7.7),
      'lat' => $faker->latitude($min = 9.4, $max = 9.7),
      'created_by' => $faker->numberBetween($min = 0, $max = 3),
      'updated_by' => $faker->numberBetween($min = 0, $max = 3),
  ];
  
});
