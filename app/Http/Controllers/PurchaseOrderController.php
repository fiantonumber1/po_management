<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        // Filter status berdasarkan query string
        $status = $request->get('status', 'ALL');

        $orders = PurchaseOrder::when($status !== 'ALL', function ($query) use ($status) {
            return $query->where('status', $status);
        })->get();

        return view('purchase_orders.index', compact('orders', 'status'));
    }

    public function create()
    {
        return view('purchase_orders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'po_number' => 'required|unique:purchase_orders',
            'po_date' => 'required|date',
            'invoice_number' => 'required|unique:purchase_orders',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'buyer' => 'required',
            'grand_total' => 'required|numeric|min:0',
        ]);

        PurchaseOrder::create($request->all());

        Alert::success('Success', 'Purchase Order added successfully!');

        return redirect()->route('purchase_orders.index');
    }

    public function updateStatus(Request $request, PurchaseOrder $order)
    {
        $request->validate([
            'status' => 'required|in:Pending,PAID'
        ]);

        $order->update(['status' => $request->status]);

        Alert::success('Success', 'Status updated successfully!');

        return redirect()->route('purchase_orders.index');
    }
}
