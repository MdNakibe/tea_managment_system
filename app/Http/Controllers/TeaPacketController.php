<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\TeaPacket;
use Illuminate\Http\Request;

class TeaPacketController extends Controller
{
    public function index(){
        $teapack = TeaPacket::with('invoice')->get()->toArray();
        return view('backend.teaPacket.index',compact('teapack'));
    }
    public function store(Request $request){
            $invoiceId = $request->invoice_id;
            $packetNames = $request->name;
            $packetWeights = $request->packet_weight;
            $invoice = Invoice::findOrFail($invoiceId);
            $totalWeight = array_sum($packetWeights);
            if ($totalWeight > $invoice->stock_in) {
                $availableWeight = $invoice->stock_in;
                    return response()->json([
                        'error' => 'Total packet weight exceeds available tea leaves. Available weight: ' . $availableWeight . ' kg.'
                    ], 400);

            }
            $totalPacketWeight = 0;
            foreach ($packetWeights as $index => $weight) {
                $name = $packetNames[$index] ?? '';
                
                if (!empty($weight) && !empty($name)) {
                    TeaPacket::create([
                        'invoice_id' => $invoiceId,
                        'packet_weight' => $weight,
                        'name' => $name,
                        'stock_in' => $weight
                    ]);
                    $totalPacketWeight += $weight;
                }
            }
            $invoice->stock_in -= $totalPacketWeight;
            $invoice->stock_out += $totalPacketWeight;
            if ($invoice->stock_in < 0) {
                $invoice->stock_in = 0;
            }

            $invoice->save();
            return response()->json(['success' => 'Tea packets created successfully.']);
        }
}
