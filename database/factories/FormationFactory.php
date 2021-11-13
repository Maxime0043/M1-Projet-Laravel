<?php

namespace Database\Factories;

use App\Models\Formation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Formation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        do {
            $id = User::all()->random()->id;
            $currentUser = User::find($id);
        } while ($currentUser->email == 'admin@admin.com');

        return [
            'title'         => $this->faker->unique()->realTextBetween(10, 30),
            'description'   => $this->faker->text(500),
            'price'         => $this->faker->randomFloat(2, 10, 500),
            'picture'       => $this->faker->imageUrl(),
            'user_id'       => $id
        ];
    }
}
