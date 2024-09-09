<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_number', 
        'price', 
        'weight',
        'stock_in',
        'stock_out',
    ];

    public function teaPackets(){
        return $this->hasOne(TeaPacket::class, 'invoice_id', 'id');
    }
}
