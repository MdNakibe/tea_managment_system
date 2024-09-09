<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionTeaPacket extends Model
{
    use HasFactory;
    protected $table = 'production_tea_packet';
    protected $fillable = [
        'production_id','tea_packet_id','weight_taken'
    ];

    public function teaPeak()
    {
        return $this->belongsTo(TeaPacket::class, 'tea_packet_id','id');
    }
    
    
}
