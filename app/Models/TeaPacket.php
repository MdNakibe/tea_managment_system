<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeaPacket extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_id',
        'packet_weight',
        'stock_in',
        'stock_out',
        'name',
        'is_empty'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
