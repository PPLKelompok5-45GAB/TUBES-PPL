<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admin';

    protected $primaryKey = 'admin_id';

    public $incrementing = true;

    protected $keyType = 'int';

    // Relationships
    /**
     * Get the announcements for the admin.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pengumumans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Pengumuman::class, 'admin_id', 'admin_id');
    }
}
