<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(){
        $invoice = Invoice::with('teaPackets')
                    ->get()->toArray();
        return view('backend.invoice.index',compact('invoice'));
    }
    public function store(Request $request) {
        $request->validate([
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'price' => 'required|numeric',
            'weight' => 'required|numeric',
        ]);
        Invoice::create([
            'invoice_number' => $request->invoice_number,
            'price' => $request->price,
            'weight' => $request->weight,
            'stock_in' => $request->weight,
        ]);
    
        return redirect()->back()->with('success', 'Invoice created successfully');
    }
    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        return response()->json($invoice);
    }
    public function update(Request $request, $id)
    {
        $invoice = Invoice::find($id);

        if ($invoice) {
            $invoice->invoice_number = $request->invoice_number;
            $invoice->price = $request->price;
            $invoice->weight = $request->weight;
            $invoice->stock_in = $request->weight;
            $invoice->save();

            return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
        } else {
            return redirect()->route('invoices.index')->with('error', 'Invoice not found.');
        }
    }
    public function destroy($id){
            $invoice = Invoice::findOrFail($id);
            
            // Delete the invoice
            $invoice->delete();
            
            return response()->json(['success' => 'Invoice deleted successfully']);
        }
}
