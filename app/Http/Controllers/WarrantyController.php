<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Warranty;
use App\Services\PdfService;
use App\Services\WarrantyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarrantyController extends Controller
{
    protected $warrantyService;
    protected $pdfService;
    
    /**
     * Constructor
     */
    public function __construct(WarrantyService $warrantyService, PdfService $pdfService)
    {
        $this->warrantyService = $warrantyService;
        $this->pdfService = $pdfService;
    }

    /**
     * Display a listing of the warranties.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isManager() || $user->isAdmin()) {
            $warranties = Warranty::with('product', 'order.user')
                ->latest()
                ->paginate(10);
        } else {
            $warranties = Warranty::whereHas('order', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with('product')
            ->latest()
            ->paginate(10);
        }
        
        return view('warranties.index', compact('warranties'));
    }

    /**
     * Display the specified warranty.
     */
    public function show(Warranty $warranty)
    {
        $user = Auth::user();
        
        // Check if the user is authorized to view this warranty
        if (!$user->isManager() && !$user->isAdmin() && $warranty->order->user_id !== $user->id) {
            abort(403);
        }
        
        $warranty->load('product', 'order', 'extensions');
        
        return view('warranties.show', compact('warranty'));
    }

    /**
     * Show form to extend warranty.
     */
    public function showExtendForm(Warranty $warranty)
    {
        $user = Auth::user();
        
        // Check if the user is authorized and warranty can be extended
        if (!$warranty->canBeExtended() || 
            (!$user->isManager() && !$user->isAdmin() && $warranty->order->user_id !== $user->id)) {
            abort(403, 'Garansi tidak dapat diperpanjang.');
        }
        
        $warrantyPrices = $warranty->product->warrantyPrices;
        
        return view('warranties.extend', compact('warranty', 'warrantyPrices'));
    }

    /**
     * Process warranty extension.
     */
    public function extend(Request $request, Warranty $warranty)
    {
        $user = Auth::user();
        
        // Check if user is a customer
        if (!$user->isCustomer()) {
            return redirect()->route('dashboard')
                ->with('error', 'Hanya customer yang dapat memperpanjang garansi.');
        }
    
        $request->validate([
            'warranty_price_id' => 'required|exists:product_warranty_prices,id',
        ]);
        
        
        // Check if the user is authorized and warranty can be extended
        if (!$warranty->canBeExtended() || 
            (!$user->isManager() && !$user->isAdmin() && $warranty->order->user_id !== $user->id)) {
            abort(403, 'Garansi tidak dapat diperpanjang.');
        }
        
        $warrantyPrice = $warranty->product->warrantyPrices()
            ->where('id', $request->warranty_price_id)
            ->firstOrFail();
        
        // For simplicity, we're creating a direct extension without payment
        // In a real system, this would create an order for the extension
        $this->warrantyService->extendWarranty($warranty, $warrantyPrice->months);
        
        return redirect()->route('customer.warranties.show', $warranty->id)
            ->with('success', 'Garansi berhasil diperpanjang.');
    }

    /**
     * Generate and download warranty certificate.
     */
    public function downloadCertificate(Warranty $warranty)
    {
        $user = Auth::user();
        
        // Check if the user is authorized to download this warranty certificate
        if (!$user->isManager() && !$user->isAdmin() && $warranty->order->user_id !== $user->id) {
            abort(403);
        }
        
        $pdfPath = $this->pdfService->generateWarrantyCertificatePdf($warranty);
        
        return response()->download(storage_path('app/public/' . $pdfPath));
    }
}
