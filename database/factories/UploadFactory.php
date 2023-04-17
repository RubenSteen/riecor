<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Upload>
 */
class UploadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::all()->random()->id,
            'name' => $this->faker->realTextBetween(4, 26),
            'original_name' => "{$this->faker->word()}.jpg",
            'original_extension' => "{$this->faker->fileExtension()}",
            'original_size' => rand(1000, 1000000),
            'original_mime_type' => "{$this->faker->mimeType()}",
        ];
    }
}
