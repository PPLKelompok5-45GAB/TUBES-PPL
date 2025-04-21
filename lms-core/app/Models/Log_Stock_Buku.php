<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Log_Stock_Buku
 *
 * @property int $log_id
 * @property int $book_id
 * @property string $entry_date
 * @property int|null $qty_added
 * @property int|null $qty_removed
 * @property string|null $notes
 *
 * @property-read \App\Models\Buku $buku
 *
 * @package App\Models
 */
class Log_Stock_Buku extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_stock_buku';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'log_id';

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
        'log_id', 'book_id', 'entry_date', 'qty_added', 'qty_removed', 'notes',
    ];

    // Relationships

    /**
     * Get the book for the stock log.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'book_id', 'book_id');
    }
}
