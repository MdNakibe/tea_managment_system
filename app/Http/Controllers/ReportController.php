<?php

namespace App\Http\Controllers;

use App\Models\ProductPacket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function productReport($id){
        $data = DB::table('product_packings')
        ->select('product_packings.*', 'product_packings.packet_weight as product_weight')
        ->join('productions', 'product_packings.production_id', '=', 'productions.id')
        ->join('production_tea_packet', 'productions.id', '=', 'production_tea_packet.production_id')
        ->join('tea_packets', 'production_tea_packet.tea_packet_id', '=', 'tea_packets.id')
        ->join('invoices', 'tea_packets.invoice_id', '=', 'invoices.id')
        ->where('product_packings.id', $id)
        ->select(
            'product_packings.*',
            'product_packings.packet_weight as product_weight',
            'productions.*',
            'production_tea_packet.*',
            'tea_packets.*',
            'invoices.*'
        )
        ->first(); 

        if ($data) {
            return view('backend.productPeaking.report', compact('data'));
        }
    
        return view('backend.productPeaking.report', ['error' => 'Product not found']);
    }
}
