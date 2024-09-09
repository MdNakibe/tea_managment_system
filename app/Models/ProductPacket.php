<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPacket extends Model
{
    use HasFactory;

    protected $table = 'product_packings';
    protected $fillable = [
        'production_id','code','packet_weight','unit','quantity'
    ];

    public function production()
    {
        return $this->belongsTo(Production::class);
    }
}
