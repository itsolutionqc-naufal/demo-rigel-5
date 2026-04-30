<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\Setting;
use App\Models\SaleTransaction;
use App\Models\Commission;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        // Cache services - get all services (not just active) so admin can manage commissions for all
        $services = cache()->remember('services.all', 3600, function () {
            return Service::select('id', 'name', 'image', 'commission_rate', 'whatsapp_number', 'minimum_nominal', 'is_active')
                ->orderBy('name')
                ->get();
        });

        // Optimize query with specific columns
        $paymentMethods = PaymentMethod::with(['service' => function($query) {
            $query->select('id', 'name', 'image', 'commission_rate');
        }])
        ->select('id', 'service_id', 'name', 'type', 'is_active', 'account_number', 'account_holder', 'qr_code_path', 'nmid')
        ->latest()
        ->get();

        return view('settings.index', compact('paymentMethods', 'services'));
    }

    /**
     * Store a new payment method
     */
    public function storePaymentMethod(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:bank_account,qris',
            'account_number' => 'nullable|string|max:50',
            'account_holder' => 'nullable|string|max:255',
            'qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nmid' => 'nullable|string|max:255',
        ]);

        // Handle QR code upload - save to public/uploads/images/qris
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

        PaymentMethod::create($validated);

        // Clear payment methods cache
        cache()->forget('payment_methods.all');

        return redirect()->route('admin.settings')->with('success', 'Metode pembayaran berhasil ditambahkan!');
    }

    /**
     * Update an existing payment method
     */
    public function updatePaymentMethod(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:bank_account,qris',
            'account_number' => 'nullable|string|max:50',
            'account_holder' => 'nullable|string|max:255',
            'qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nmid' => 'nullable|string|max:255',
        ]);

        // Handle QR code upload - save to public/uploads/images/qris
        if ($request->hasFile('qr_code')) {
            // Delete old QR code if exists
            if ($paymentMethod->qr_code_path) {
                $oldFilePath = public_path($paymentMethod->qr_code_path);
                if (file_exists($oldFilePath)) {-
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

        $paymentMethod->update($validated);

        // Clear payment methods cache
        cache()->forget('payment_methods.all');

        return redirect()->route('admin.settings')->with('success', 'Metode pembayaran berhasil diperbarui!');
    }

    /**
     * Delete a payment method
     */
    public function deletePaymentMethod(PaymentMethod $paymentMethod)
    {
        // Delete QR code if exists
        if ($paymentMethod->qr_code_path) {
            $filePath = public_path($paymentMethod->qr_code_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $paymentMethod->delete();

        return redirect()->route('admin.settings')->with('success', 'Metode pembayaran berhasil dihapus!');
    }

    /**
     * Format WhatsApp number to start with 62
     */
    private function formatWhatsAppNumber($number)
    {
        if (!$number) {
            return null;
        }

        // Remove spaces and special characters
        $number = preg_replace('/[^0-9]/', '', $number);

        // If starts with 0, replace with 62
        if (substr($number, 0, 1) === '0') {
            $number = '62' . substr($number, 1);
        }

        // If doesn't start with 62, add it
        if (substr($number, 0, 2) !== '62') {
            $number = '62' . $number;
        }

        return $number;
    }

    /**
     * Update commission rates and WhatsApp numbers for all services
     */
    public function updateServiceCommissions(Request $request)
    {
        $request->validate([
            'commissions' => 'required|array',
            'commissions.*' => 'required|numeric|min:0|max:100',
            'whatsapp_numbers' => 'nullable|array',
            'whatsapp_numbers.*' => 'nullable|string|max:20',
            'minimum_nominals' => 'nullable|array',
            'minimum_nominals.*' => 'nullable|numeric|min:0',
        ]);

        // Optimasi: Ambil semua service sekaligus
        $serviceIds = array_keys($request->commissions);
        $services = Service::whereIn('id', $serviceIds)->get()->keyBy('id');

        $updatedCount = 0;

        // Gunakan transaction untuk atomic operation
        DB::transaction(function () use ($services, $request, &$updatedCount) {
            foreach ($request->commissions as $serviceId => $commissionRate) {
                $service = $services->get($serviceId);
                if ($service) {
                    $whatsappNumber = $request->whatsapp_numbers[$serviceId] ?? null;
                    $minimumNominal = $request->minimum_nominals[$serviceId] ?? 15000;
                    $service->update([
                        'commission_rate' => $commissionRate,
                        'whatsapp_number' => $this->formatWhatsAppNumber($whatsappNumber),
                        'minimum_nominal' => $minimumNominal,
                    ]);
                    $updatedCount++;
                }
            }
        });

        // Clear services cache
        cache()->forget('services.all');
        cache()->forget('services.active');

        return redirect()->route('admin.settings')->with('success', "Berhasil memperbarui {$updatedCount} layanan.");
    }
}
