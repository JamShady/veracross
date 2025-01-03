<?php

namespace Database\Factories;

use App\Models\PhoneNumber;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhoneNumberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PhoneNumber::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'number' => $this->faker->e164PhoneNumber,
        ];
    }
}
