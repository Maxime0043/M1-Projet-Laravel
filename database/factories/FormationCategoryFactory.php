<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Formation;
use App\Models\FormationCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormationCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FormationCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'formation' => Formation::all()->random()->id,
            'category'  => Category::all()->random()->id
        ];
    }
}
