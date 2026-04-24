<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::latest()->paginate(10);
        return view('payment-methods.index', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('payment-methods.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:bank_account,qris',
            'account_number' => 'nullable|string|max:50',
            'account_holder' => 'nullable|string|max:255',
            'qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nmid' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Handle QR code upload if present - save to public/uploads/images/qris
        if ($request->hasFile('qr_code')) {
            $file = $request->file('qr_code');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Create directory if it doesn't exist
            $uploadPath = public_path('uploads/images/qris');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Move file to public/uploads/images/qris
            $file->move($uploadPath, $filename);
            $validated['qr_code_path'] = 'uploads/images/qris/' . $filename;
        }

        // If type is bank_account, make sure account_number and account_holder are provided
        if ($validated['type'] === 'bank_account') {
            $request->validate([
                'account_number' => 'required|string|max:50',
                'account_holder' => 'required|string|max:255',
            ]);
        }

        PaymentMethod::create(array_merge($validated, [
            'is_active' => $request->has('is_active') ? true : false,
        ]));

        return redirect()->route('payment-methods.index')->with('success', 'Metode pembayaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentMethod $paymentMethod)
    {
        return view('payment-methods.show', compact('paymentMethod'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        return view('payment-methods.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:bank_account,qris',
            'account_number' => 'nullable|string|max:50',
            'account_holder' => 'nullable|string|max:255',
            'qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nmid' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Handle QR code upload if present - save to public/uploads/images/qris
        if ($request->hasFile('qr_code')) {
            // Delete old QR code if exists
            if ($paymentMethod->qr_code_path) {
                $oldFilePath = public_path($paymentMethod->qr_code_path);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            $file = $request->file('qr_code');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Create directory if it doesn't exist
            $uploadPath = public_path('uploads/images/qris');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Move file to public/uploads/images/qris
            $file->move($uploadPath, $filename);
            $validated['qr_code_path'] = 'uploads/images/qris/' . $filename;
        }

        // If type is bank_account, make sure account_number and account_holder are provided
        if ($validated['type'] === 'bank_account') {
            $request->validate([
                'account_number' => 'required|string|max:50',
                'account_holder' => 'required|string|max:255',
            ]);
        }

        $paymentMethod->update(array_merge($validated, [
            'is_active' => $request->has('is_active') ? true : false,
        ]));

        return redirect()->route('admin.settings')->with('success', 'Metode pembayaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        // Delete QR code if exists
        if ($paymentMethod->qr_code_path) {
            $filePath = public_path($paymentMethod->qr_code_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $paymentMethod->delete();

        return redirect()->route('payment-methods.index')->with('success', 'Metode pembayaran berhasil dihapus.');
    }
}
