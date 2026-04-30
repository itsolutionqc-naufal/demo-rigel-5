<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    /**
     * Format WhatsApp number to standard format (62xxxxxxxxxx)
     */
    private function formatWhatsAppNumber($number)
    {
        if (empty($number)) {
            return null;
        }

        // Remove all non-numeric characters
        $number = preg_replace('/[^0-9]/', '', $number);

        // If starts with 0, replace with 62
        if (substr($number, 0, 1) === '0') {
            $number = '62'.substr($number, 1);
        }

        // If starts with 8 (no 62 prefix), add 62
        if (substr($number, 0, 1) === '8') {
            $number = '62'.$number;
        }

        // If doesn't start with 62, add it
        if (substr($number, 0, 2) !== '62') {
            $number = '62'.$number;
        }

        return $number;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Service::query();

        // Apply date filters
        if ($request->filled('created_date')) {
            try {
                $day = Carbon::createFromFormat('Y-m-d', (string) $request->created_date);
                $query->whereBetween('created_at', [$day->copy()->startOfDay(), $day->copy()->endOfDay()]);
            } catch (\Throwable $e) {
                // ignore invalid date
            }
        } else {
            // Backward compatibility (older URLs)
            if ($request->filled('start_date')) {
                try {
                    $start = Carbon::createFromFormat('Y-m-d', (string) $request->start_date)->startOfDay();
                    $query->where('created_at', '>=', $start);
                } catch (\Throwable $e) {
                    // ignore invalid date
                }
            }

            if ($request->filled('end_date')) {
                try {
                    $end = Carbon::createFromFormat('Y-m-d', (string) $request->end_date)->endOfDay();
                    $query->where('created_at', '<=', $end);
                } catch (\Throwable $e) {
                    // ignore invalid date
                }
            }
        }

        // Apply category filter
        if ($request->filled('category')) {
            if ($request->category === 'reseller_coin') {
                $query->where(function ($q) {
                    $q->where('category', 'reseller_coin')
                        ->orWhereNull('category')
                        ->orWhere('category', '');
                });
            } else {
                $query->where('category', $request->category);
            }
        }

        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('category', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $services = $query->paginate(10)->appends($request->query());

        return view('services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $category = $request->get('category');

        return view('services.create', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Store request received', $request->all());

        $category = $request->input('category') ?: null;

        $rules = [
            'name' => 'required|string|max:255',
            'category' => ['nullable', Rule::in(['talent_hunter', 'reseller_coin'])],
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Max 5MB, added webp
            'price' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,draft',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'whatsapp_number' => 'nullable|string|max:20',
            'telegram_bot_id' => 'nullable|exists:telegram_bots,id',
            'telegram_chat_id' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ];

        if ($category !== 'talent_hunter') {
            $rules['commission_rate'] = 'required|numeric|min:0|max:100';
        }

        $request->validate($rules);

        \Log::info('Validation passed', ['whatsapp_number' => $request->whatsapp_number]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time().'_'.$image->getClientOriginalName();

            // Save to public/uploads/images/application
            $image->move(public_path('uploads/images/application'), $filename);
            $imagePath = 'uploads/images/application/'.$filename;
        }

        $whatsappNumber = $this->formatWhatsAppNumber($request->whatsapp_number);
        \Log::info('Creating service', [
            'name' => $request->name,
            'whatsapp_number' => $request->whatsapp_number,
            'formatted_whatsapp' => $whatsappNumber,
        ]);

        Service::create([
            'name' => $request->name,
            'category' => $category,
            'description' => $request->description,
            'image' => $imagePath,
            'price' => $request->price,
            'status' => $request->status,
            'commission_rate' => $request->commission_rate ?? 0,
            'whatsapp_number' => $whatsappNumber,
            'telegram_bot_id' => $request->telegram_bot_id,
            'telegram_chat_id' => $request->telegram_chat_id,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Clear services cache so new service appears in settings dropdown
        cache()->forget('services.all');
        cache()->forget('services.active');

        return redirect()
            ->route('services.index', $category ? ['category' => $category] : [])
            ->with('success', 'Berhasil menambahkan Layanan baru!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        return view('services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $category = $request->input('category') ?: $service->category;

        $rules = [
            'name' => 'required|string|max:255',
            'category' => ['nullable', Rule::in(['talent_hunter', 'reseller_coin'])],
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Max 5MB, added webp
            'price' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,draft',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'whatsapp_number' => 'nullable|string|max:20',
            'telegram_bot_id' => 'nullable|exists:telegram_bots,id',
            'telegram_chat_id' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ];

        if ($category !== 'talent_hunter') {
            $rules['commission_rate'] = 'required|numeric|min:0|max:100';
        }

        $request->validate($rules);

        $imagePath = $service->image;
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($service->image && file_exists(public_path($service->image))) {
                unlink(public_path($service->image));
            }

            $image = $request->file('image');
            $filename = time().'_'.$image->getClientOriginalName();

            // Save to public/uploads/images/application
            $image->move(public_path('uploads/images/application'), $filename);
            $imagePath = 'uploads/images/application/'.$filename;
        }

        $service->update([
            'name' => $request->name,
            'category' => $category,
            'description' => $request->description,
            'image' => $imagePath,
            'price' => $request->price,
            'status' => $request->status,
            'commission_rate' => $request->commission_rate ?? 0,
            'whatsapp_number' => $this->formatWhatsAppNumber($request->whatsapp_number),
            'telegram_bot_id' => $request->telegram_bot_id,
            'telegram_chat_id' => $request->telegram_chat_id,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Clear services cache
        cache()->forget('services.all');
        cache()->forget('services.active');

        return redirect()
            ->route('services.index', $category ? ['category' => $category] : [])
            ->with('success', 'Berhasil memperbarui Layanan!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();

        // Clear services cache
        cache()->forget('services.all');
        cache()->forget('services.active');

        return redirect()->route('services.index')->with('success', 'Berhasil menghapus Layanan!');
    }
}
