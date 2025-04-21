<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Bookmark
 *
 * @property int $bookmark_id
 * @property int $book_id
 * @property int $member_id
 * @property int|null $page_number
 * @property string|null $notes
 * @property string $added_date
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Bookmark> $bookmarks
 * @property-read \App\Models\Buku $buku
 * @property-read \App\Models\Member $member
 *
 * @method static static create(array<string, mixed> $attributes = [])
 * @method static static|null find(mixed $id, array<int, string> $columns = ['*'])
 *
 * @package App\Models
 */
class Bookmark extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bookmarks';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'bookmark_id';

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
     * @var array<int, string>
     */
    protected $fillable = [
        'bookmark_id', 'book_id', 'member_id', 'page_number', 'notes', 'added_date',
    ];

    // Relationships

    /**
     * Get the book for the bookmark.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'book_id', 'book_id');
    }

    /**
     * Get the member for the bookmark.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }

    /**
     * Create a new Bookmark instance and persist it to the database.
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
     * Find a Bookmark by ID.
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
