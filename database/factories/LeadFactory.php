<?php

namespace Homeful\KwYCCheck\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Homeful\KwYCCheck\Models\Lead;

class LeadFactory extends Factory
{
    protected $model = Lead::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'birthdate' => $this->faker->date(),
            'email' => $this->faker->email(),
            'mobile' => $this->faker->phoneNumber(),
            'code' => $this->faker->word(),
            'id_type' => $this->faker->word(),
            'id_number' => $this->faker->uuid(),
            'id_image_url' => $this->faker->url(),
            'selfie_image_url' => $this->faker->url(),
            'id_mark_url' => $this->faker->url(),
        ];
    }
}
