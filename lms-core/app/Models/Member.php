<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Member Model
 *
 * @property int $member_id
 * @property string $name
 * @property string $email
 * @property string $status
 * @property string $membership_date
 * @property string|null $phone
 * @property string|null $address
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Bookmark> $bookmarks
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Wishlist> $wishlists
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review_Buku> $reviews
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log_Pinjam_Buku> $logPinjams
 *
 * @method static static findOrFail(mixed $id, array<int, string> $columns = ['*'])
 * @method static static find(mixed $id, array<int, string> $columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|static where(string $column, string $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static static first()
 * @method int update(array<string, mixed> $attributes = [], array<string, mixed> $options = [])
 * @method \Illuminate\Database\Eloquent\Collection<int, static> get(array<int, string> $columns = ['*'])
 * @method int count(string $columns = '*')
 * @method static self create(array<string, mixed> $attributes = [])
 *
 * @package App\Models
 */
class Member extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'member';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'member_id';

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
        'name', 'email', 'status', 'membership_date', 'phone', 'address',
    ];

    // Relationships
    /**
     * Get the bookmarks for the member.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookmarks()
    {
        return $this->hasMany(\App\Models\Bookmark::class, 'member_id', 'member_id');
    }

    /**
     * Get the wishlists for the member.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wishlists()
    {
        return $this->hasMany(\App\Models\Wishlist::class, 'member_id', 'member_id');
    }

    /**
     * Get the reviews for the member.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany(\App\Models\Review_Buku::class, 'member_id', 'member_id');
    }

    /**
     * Get the log pinjams for the member.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logPinjams()
    {
        return $this->hasMany(\App\Models\Log_Pinjam_Buku::class, 'member_id', 'member_id');
    }
}
