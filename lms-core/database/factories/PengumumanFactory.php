<?php

namespace Database\Factories;

use App\Models\Pengumuman;
use Illuminate\Database\Eloquent\Factories\Factory;

class PengumumanFactory extends Factory
{
    protected $model = Pengumuman::class;

    public function definition(): array
    {
        return [
            'admin_id' => \App\Models\Admin::factory(),
            'title' => $this->faker->sentence(3),
            'content' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['draft', 'published']),
            'post_date' => $this->faker->date(),
        ];
    }
}
