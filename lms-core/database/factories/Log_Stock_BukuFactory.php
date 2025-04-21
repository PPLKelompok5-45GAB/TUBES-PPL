<?php

namespace Database\Factories;

use App\Models\Log_Stock_Buku;
use Illuminate\Database\Eloquent\Factories\Factory;

class Log_Stock_BukuFactory extends Factory
{
    protected $model = Log_Stock_Buku::class;

    public function definition(): array
    {
        return [
            'book_id' => \App\Models\Buku::factory(),
            'entry_date' => $this->faker->date(),
            'qty_added' => $this->faker->numberBetween(0, 20),
            'qty_removed' => $this->faker->numberBetween(0, 20),
            'notes' => $this->faker->sentence(),
        ];
    }
}
