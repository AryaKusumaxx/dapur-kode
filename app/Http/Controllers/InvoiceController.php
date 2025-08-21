<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    protected $pdfService;
    
    /**
     * Constructor
     */
    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isManager() || $user->isAdmin()) {
            $invoices = Invoice::with('order.user')->latest()->paginate(10);
        } else {
            $invoices = Invoice::whereHas('order', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->latest()->paginate(10);
        }
        
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $user = Auth::user();
        
        // Check if the user is authorized to view this invoice
        if (!$user->isManager() && !$user->isAdmin() && $invoice->order->user_id !== $user->id) {
            abort(403);
        }
        
        $invoice->load('order.items', 'payments');
        
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Generate and download PDF invoice.
     */
    public function downloadPdf(Invoice $invoice)
    {
        $user = Auth::user();
        
        // Check if the user is authorized to download this invoice
        if (!$user->isManager() && !$user->isAdmin() && $invoice->order->user_id !== $user->id) {
            abort(403);
        }
        
        $pdfPath = $this->pdfService->generateInvoicePdf($invoice);
        
        return response()->download(storage_path('app/public/' . $pdfPath));
    }
}
