<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdminFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Admin::class;

    /**
     * The roles.
     *
     * @var array
     */
    protected array $roles = ['admin', 'super admin'];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->email(),
            'phone' => $this->faker->phoneNumber(),
            'role' => $this->faker->randomElement($this->roles),
        ];
    }
}
