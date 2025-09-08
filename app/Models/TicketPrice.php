<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketPrice extends Model
{
    use HasFactory;

    protected $table = 'ticket_prices';
    protected $primaryKey = 'price_id';

    protected $fillable = [
        'train_id',
        'compartment_id',
        'base_price',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
    ];

    /**
     * Get the train that this price belongs to
     */
    public function train()
    {
        return $this->belongsTo(Train::class, 'train_id', 'train_id');
    }

    /**
     * Get the compartment that this price is for
     */
    public function compartment()
    {
        return $this->belongsTo(Compartment::class, 'compartment_id', 'compartment_id');
    }
}
