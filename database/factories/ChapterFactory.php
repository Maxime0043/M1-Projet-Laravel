<?php

namespace Database\Factories;

use App\Models\Chapter;
use App\Models\Formation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChapterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Chapter::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'     => $this->faker->unique()->realTextBetween(20, 40),
            'duration'  => $this->faker->time(),
            'content'   => $this->faker->randomHtml(4, 8),
            'formation' => Formation::all()->random()->id
        ];
    }
}
