<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NidDb extends Model
{
    use HasFactory;

    protected $table = 'nid_db';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'nid_number',
        'name',
        'dob',
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    /**
     * Get the user that owns this NID record
     */
    public function user()
    {
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }
}
