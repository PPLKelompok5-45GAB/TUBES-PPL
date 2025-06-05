<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Buku Model
 *
 * @property int $book_id
 * @property int $category_id
 * @property string $title
 * @property string $author
 * @property string $isbn
 * @property int $publication_year
 * @property string $publisher
 * @property int $total_stock
 * @property int $borrowed_qty
 * @property int $available_qty
 * @property string $synopsis
 * @property string $image
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
 * @property-read \App\Models\Kategori $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Bookmark> $bookmarks
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review_Buku> $reviews
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Wishlist> $wishlists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log_Pinjam_Buku> $logPinjams
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log_Stock_Buku> $logStocks
 *
 * @package App\Models
 */
class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';
    protected $primaryKey = 'book_id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'book_id', 'category_id', 'title', 'author', 'isbn', 'publication_year', 'publisher', 'total_stock', 'borrowed_qty', 'available_qty', 'synopsis', 'image',
    ];

    // Relationships
    /**
     * Get the category for the book.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(\App\Models\Kategori::class, 'category_id', 'category_id');
    }

    /**
     * Get the bookmarks for the book.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class, 'book_id', 'book_id');
    }

    /**
     * Get the reviews for the book.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany(Review_Buku::class, 'book_id', 'book_id');
    }

    /**
     * Get the wishlists for the book.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'book_id', 'book_id');
    }

    /**
     * Get the log pinjams for the book.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logPinjams()
    {
        return $this->hasMany(Log_Pinjam_Buku::class, 'book_id', 'book_id');
    }

    /**
     * Get the log stocks for the book.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logStocks()
    {
        return $this->hasMany(Log_Stock_Buku::class, 'book_id', 'book_id');
    }

    /**
     * Create a new Buku instance and persist it to the database.
     *
     * @param array<string, mixed> $attributes
     * @return static
     */
    public static function create(array $attributes): static
    {
        // @phpstan-ignore-next-line
        $model = (new static())->newInstance($attributes);
        $model->save();
        return $model;
    }

    /**
     * Find a Buku by ID.
     *
     * @param mixed $id
     * @param array<int, string> $columns
     * @return static|null
     */
    public static function find($id, $columns = ['*']): ?static
    {
        // @phpstan-ignore-next-line
        $model = static::query()->find($id, $columns);
        return $model instanceof static ? $model : null;
    }

    /**
     * @phpstan-ignore-next-line
     */
    public static function query(): \Illuminate\Database\Eloquent\Builder
    {
        return (new static())->newQuery();
    }

    /**
     * Proxy to Eloquent where with explicit parameter types for PHPStan compliance.
     *
     * @param string|array<int|string, string>|\Closure|\Illuminate\Contracts\Database\Query\Expression $column
     * @param string|null $operator
     * @param mixed $value
     * @param string $boolean
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function where(
        $column,
        $operator = null,
        $value = null,
        $boolean = 'and'
    ): \Illuminate\Database\Eloquent\Builder {
        // @phpstan-ignore-next-line
        $query = (new static())->newQuery();
        return $query->where($column, $operator, $value, $boolean);
    }

    /**
     * Find a Buku by primary key or fail.
     *
     * @param int $id
     * @return self
     */
    public static function findOrFail($id): self
    {
        $model = static::find($id);
        if ($model === null) {
            abort(404, 'Book not found');
        }
        return $model;
    }

    /**
     * Scope a query to only include books with available quantity greater than a given value.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $minQty
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailableQtyGreaterThan(\Illuminate\Database\Eloquent\Builder $query, int $minQty): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('available_qty', '>', $minQty);
    }

    /**
     * Check if the book is available for borrowing.
     *
     * @return bool
     */
    public function isAvailableForBorrowing(): bool
    {
        return $this->available_qty > 0;
    }

    /**
     * Check if the book is already borrowed by a specific member.
     *
     * @param int $memberId
     * @return bool
     */
    public function isAlreadyBorrowedBy(int $memberId): bool
    {
        return $this->logPinjams()
            ->where('member_id', $memberId)
            ->whereIn('status', ['pending', 'approved', 'overdue'])
            ->exists();
    }
}
