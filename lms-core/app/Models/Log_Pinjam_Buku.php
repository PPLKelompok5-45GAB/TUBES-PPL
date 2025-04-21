<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Log_Pinjam_Buku Model
 *
 * @property int $loan_id
 * @property int $book_id
 * @property int $member_id
 * @property string $borrow_date
 * @property string $due_date
 * @property string|null $return_date
 * @property string $status
 * @property int $overdue_count
 *
 * @property-read \App\Models\Buku $buku
 * @property-read \App\Models\Member $member
 *
 * @method static static create(array<string, mixed> $attributes = [])
 * @method static static|null find(mixed $id, array<int, string> $columns = ['*'])
 * @method static static findOrFail(mixed $id, array<int, string> $columns = ['*'])
 * @method static static find(mixed $id, array<int, string> $columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|static where(string $column, string $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static static first()
 * @method int update(array<string, mixed> $attributes = [], array<string, mixed> $options = [])
 * @method \Illuminate\Database\Eloquent\Collection<int, static> get(array<int, string> $columns = ['*'])
 * @method int count(string $columns = '*')
 *
 * @package App\Models
 */
class Log_Pinjam_Buku extends Model
{
    use HasFactory;

    protected $table = 'log_pinjam_buku';

    protected $primaryKey = 'loan_id';

    public $incrementing = false;

    protected $keyType = 'int';

    /**
     * The number of times this loan has been overdue.
     *
     * @var int
     */
    public int $overdue_count = 0;

    protected $fillable = [
        'loan_id', 'book_id', 'member_id', 'borrow_date', 'due_date', 'return_date', 'status', 'overdue_count',
    ];

    // Relationships
    /**
     * Get the book for the loan log.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'book_id', 'book_id');
    }

    /**
     * Get the member for the loan log.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }

    /**
     * Find a Log_Pinjam_Buku by ID or fail.
     *
     * @param int $id
     * @param array<int, string> $columns
     * @return static
     */
    public static function findOrFail($id, $columns = ['*']): self
    {
        /** @var static $model */
        $model = static::query()->findOrFail($id, $columns);
        return $model;
    }
}
