<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\ProductPacket;
use Illuminate\Http\Request;

class ProductPacketController extends Controller
{
    public function index(){
        $production = Production::where('stock_in' ,'>', 0)->get();
        $peakting = ProductPacket::get()->toArray();
        return view('backend.productPeaking.index',compact('production','peakting'));
    }
    function productCode($production_id) {
        return 'tea-' . $production_id . '-' . rand(1000, 9999);
    }
    public function store(Request $request)
        {
            $request->validate([
                'units' => 'required',
                'weights' => 'required',
            ]);
            $production_id = $request->production_id;
            $unit = $request->units;
            $weights = $request->weights;
            if ($unit === 'gm') {
                $weights = $weights / 1000; 
            }
            $teaPacket = Production::find($production_id);
            if ($weights > $teaPacket->stock_in) {
                return redirect()->back()->withErrors("The weight for packet ID {$production_id} exceeds the available stock.");
            }
            $barcode = $this->productCode($production_id);
            ProductPacket::create([
                'production_id' => $production_id,
                'packet_weight' => $weights,
                'unit' => $unit, 
                'code' => $barcode, 
            ]);

            $teaPacket->stock_in -= $weights;
            $teaPacket->stock_out += $weights;
            $teaPacket->save();

            return redirect()->route('productpackets.index')->with('success', 'Production recorded successfully!');
        }
}
