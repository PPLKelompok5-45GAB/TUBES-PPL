<?php

namespace Database\Factories;

use App\Models\Log_Pinjam_Buku;
use Illuminate\Database\Eloquent\Factories\Factory;

class Log_Pinjam_BukuFactory extends Factory
{
    protected $model = Log_Pinjam_Buku::class;

    public function definition(): array
    {
        $borrowDate = $this->faker->date();
        $dueDate = $this->faker->dateTimeBetween($borrowDate, '+2 weeks')->format('Y-m-d');
        $returnDateObj = $this->faker->optional()->dateTimeBetween($borrowDate, $dueDate);
        $returnDate = $returnDateObj ? $returnDateObj->format('Y-m-d') : null;
        return [
            'loan_id' => $this->faker->unique()->numberBetween(10000, 99999),
            'book_id' => \App\Models\Buku::factory(),
            'member_id' => \App\Models\Member::factory(),
            'borrow_date' => $borrowDate,
            'due_date' => $dueDate,
            'return_date' => $returnDate,
            'status' => $this->faker->randomElement(['borrowed', 'returned', 'late', 'lost', 'pending', 'approved']),
        ];
    }
}
