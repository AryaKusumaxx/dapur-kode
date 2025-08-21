<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    protected $notificationService;
    
    /**
     * Constructor
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display form to upload payment proof.
     */
    public function showUploadForm(Invoice $invoice)
    {
        $user = Auth::user();
        
        // Check if the user is authorized to upload payment for this invoice
        if (!$user->isManager() && !$user->isAdmin() && $invoice->order->user_id !== $user->id) {
            abort(403);
        }
        
        return view('payments.upload', compact('invoice'));
    }

    /**
     * Upload payment proof.
     */
    public function upload(Request $request, Invoice $invoice)
    {
        $user = Auth::user();
        
        // Check if the user is customer
        if (!$user->isCustomer()) {
            return redirect()->route('dashboard')
                ->with('error', 'Hanya customer yang dapat melakukan pembayaran.');
        }
        
        $request->validate([
            'payment_method' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'proof_file' => 'required|image|max:2048', // max 2MB
            'notes' => 'nullable|string',
        ]);
        
        if ($invoice->status !== 'pending') {
            return back()->with('error', 'Invoice ini tidak dalam status menunggu pembayaran.');
        }
        
        // Upload file
        $path = $request->file('proof_file')->store('payment_proofs', 'public');
        
        // Create payment record
        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'payment_method' => $request->payment_method,
            'amount' => $request->amount,
            'proof_file' => $path,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);
        
        // Notify admin about new payment
        $this->notificationService->notifyNewPayment($payment);
        
        return redirect()->route('customer.invoices.show', $invoice->id)
            ->with('success', 'Bukti pembayaran berhasil diunggah. Kami akan segera memverifikasinya.');
    }

    /**
     * Display a listing of payments.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isManager() || $user->isAdmin()) {
            $pendingPayments = Payment::where('status', 'pending')
                ->with('invoice.order.user')
                ->latest()
                ->paginate(10);
            
            return view('payments.index', compact('pendingPayments'));
        }
        
        // Customers can't access this page
        abort(403);
    }

    /**
     * Display the payment details.
     */
    public function show(Payment $payment)
    {
        $user = Auth::user();
        
        // Check if the user is authorized to view this payment
        if (!$user->isManager() && !$user->isAdmin() && $payment->invoice->order->user_id !== $user->id) {
            abort(403);
        }
        
        $payment->load('invoice.order');
        
        return view('payments.show', compact('payment'));
    }

    /**
     * Verify the payment.
     */
    public function verify(Payment $payment)
    {
        $user = Auth::user();
        
        // Only managers and admins can verify payments
        if (!$user->isManager() && !$user->isAdmin()) {
            abort(403);
        }
        
        $invoice = $payment->invoice;
        
        // Update payment status
        $payment->update([
            'status' => 'verified',
            'verified_by' => $user->id,
            'verified_at' => now(),
        ]);
        
        // Check if payment amount is sufficient
        if ($payment->amount >= $invoice->order->total) {
            // Update invoice status
            $invoice->update(['status' => 'paid']);
            
            // Update order status
            $invoice->order->update(['status' => 'paid']);
        } else {
            // Payment is partial, update invoice status only if needed
            if ($invoice->isFullyPaid()) {
                $invoice->update(['status' => 'paid']);
                $invoice->order->update(['status' => 'paid']);
            }
        }
        
        // Notify customer about verified payment
        $this->notificationService->notifyPaymentVerified($payment);
        
        return redirect()->route('admin.payments.index')
            ->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    /**
     * Reject the payment.
     */
    public function reject(Request $request, Payment $payment)
    {
        $request->validate([
            'rejection_reason' => 'required|string',
        ]);
        
        $user = Auth::user();
        
        // Only managers and admins can reject payments
        if (!$user->isManager() && !$user->isAdmin()) {
            abort(403);
        }
        
        // Update payment status
        $payment->update([
            'status' => 'rejected',
            'notes' => $request->rejection_reason,
            'verified_by' => $user->id,
            'verified_at' => now(),
        ]);
        
        return redirect()->route('admin.payments.index')
            ->with('success', 'Pembayaran telah ditolak.');
    }
}
