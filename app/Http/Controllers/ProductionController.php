<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\ProductionTeaPacket;
use App\Models\TeaPacket;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function index(){
        $teaPackets = TeaPacket::where('stock_in' ,'>', 0)->get();
        $production = Production::get()->toArray();
        return view('backend.production.index',compact('production','teaPackets'));
    }
        public function store(Request $request)
        {
            $request->validate([
                'tea_packets' => 'required|array', 
                'weights' => 'required|array', 
            ]);

            $totalWeight = array_sum($request->weights);
            $production = Production::create([
                'production_code' => uniqid('prod_'),
                'total_weight' => $totalWeight,
                'stock_in' => $totalWeight,
            ]);
            foreach ($request->tea_packets as $index => $teaPacketId) {
                $teaPacket = TeaPacket::findOrFail($teaPacketId);
                $weightTaken = $request->weights[$index];

                
                if ($weightTaken > $teaPacket->stock_in) {
                    return response()->json([
                        'error' => "Weight exceeds available stock for {$teaPacket->name}"
                    ], 400);
                }
                ProductionTeaPacket::create([
                    'production_id' => $production->id,
                    'tea_packet_id' => $teaPacketId,
                    'weight_taken' => $weightTaken,
                ]);
                $teaPacket->stock_in -= $weightTaken;
                $teaPacket->stock_out += $weightTaken;
                $teaPacket->save();
            }
            return redirect()->back()->with('success', 'Production created successfully');
        }

}
