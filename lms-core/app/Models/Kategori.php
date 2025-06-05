<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Kategori Model
 *
 * @property string $category_name
 *
 * @method static \Illuminate\Database\Eloquent\Builder|static orderBy(string $column, string $direction = 'asc')
 * @method static \Illuminate\Contracts\Pagination\LengthAwarePaginator paginate(int $perPage = 15, array<int, string> $columns = ['*'], string $pageName = 'page', int|null $page = null)
 * @method static static findOrFail(mixed $id, array<int, string> $columns = ['*'])
 * @method static static find(mixed $id, array<int, string> $columns = ['*'])
 */
class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    protected $primaryKey = 'category_id';

    public $incrementing = true;

    protected $keyType = 'int';

    // Relationships
    /**
     * Get the books for the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bukus(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Buku::class, 'category_id');
    }
}
