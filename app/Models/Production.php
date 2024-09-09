<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    use HasFactory;
    protected $fillable = [
        'production_code', 
        'total_weight',
        'stock_in',
        'stock_out',
    ];

    public function productionTeaPackets()
    {
        return $this->hasOne(ProductionTeaPacket::class, 'production_id');
    }
}
