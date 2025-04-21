<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Review_Buku
 *
 * @property int $review_id
 * @property int $book_id
 * @property int $member_id
 * @property int $rating
 * @property string $review_text
 * @property string $review_date
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review_Buku> $reviews
 * @property-read \App\Models\Buku $buku
 * @property-read \App\Models\Member $member
 *
 * @package App\Models
 */
/**
 * Review_Buku Model
 *
 * @property int $review_id
 * @property int $book_id
 * @property int $member_id
 * @property int $rating
 * @property string $review_text
 * @property string $review_date
 *
 * @method static static create(array<string, mixed> $attributes = [])
 * @method static static|null find(mixed $id, array<int, string> $columns = ['*'])
 * @method static static findOrFail(mixed $id, array<int, string> $columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|static where(string $column, string $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static static first()
 * @method int update(array<string, mixed> $attributes = [], array<string, mixed> $options = [])
 * @method \Illuminate\Database\Eloquent\Collection<int, static> get(array<int, string> $columns = ['*'])
 */
class Review_Buku extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'review_buku';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'review_id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'review_id', 'book_id', 'member_id', 'rating', 'review_text', 'review_date',
    ];

    // Relationships

    /**
     * Get the book for the review.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'book_id', 'book_id');
    }

    /**
     * Get the member for the review.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }

    /**
     * Create a new Review_Buku instance and persist it to the database.
     *
     * @param array<string, mixed> $attributes
     * @return static
     */
    public static function create(array $attributes): static
    {
        /**
         * @phpstan-ignore-next-line
         */
        $model = (new static())->newInstance($attributes);
        $model->save();
        return $model;
    }

    /**
     * Find a Review_Buku by ID.
     *
     * @param mixed $id
     * @param array<int, string> $columns
     * @return static|null
     */
    public static function find($id, $columns = ['*']): ?static
    {
        /**
         * @phpstan-ignore-next-line
         */
        $model = static::query()->find($id, $columns);
        return $model instanceof static ? $model : null;
    }

    /**
     * Update the review attributes.
     *
     * @param array<string, mixed> $attributes
     * @param array<string, mixed> $options
     * @return bool
     */
    public function update(array $attributes = [], array $options = []): bool
    {
        return parent::update($attributes, $options);
    }
}
