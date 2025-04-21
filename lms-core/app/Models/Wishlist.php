<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Wishlist
 *
 * @property int $wishlist_id
 * @property int $book_id
 * @property int $member_id
 * @property string $added_date
 *
 * @property-read \App\Models\Buku $buku
 * @property-read \App\Models\Member $member
 *
 * @method static static create(array<string, mixed> $attributes = [])
 * @method static static|null find(mixed $id, array<int, string> $columns = ['*'])
 *
 * @package App\Models
 */
class Wishlist extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wishlist';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'wishlist_id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The type of the primary key ID.
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
        'wishlist_id', 'book_id', 'member_id', 'added_date',
    ];

    // Relationships

    /**
     * Get the book for the wishlist.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'book_id', 'book_id');
    }

    /**
     * Get the member for the wishlist.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }

    /**
     * Create a new Wishlist instance and persist it to the database.
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
     * Find a Wishlist by ID.
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
}
