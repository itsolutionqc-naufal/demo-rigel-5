<?php

namespace App\Livewire;

use App\Models\PaymentMethod;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class PaymentMethodManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $filterType = '';  // Used for filtering
    public $status = '';

    // Form properties
    public $service_id;
    public $name;
    public $type;  // Used for form
    public $account_number;
    public $account_holder;
    public $qr_code;
    public $nmid;
    public $is_active = true;

    public $payment_method_id;
    public $isEditMode = false;
    public $showModal = false;
    public $confirmDelete = false;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'service_id' => 'required|exists:services,id',
        'name' => 'required|string|max:255',
        'type' => 'required|in:bank_account,qris',
        'account_number' => 'nullable|required_if:type,bank_account|string|max:50',
        'account_holder' => 'nullable|required_if:type,bank_account|string|max:255',
        'qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'nmid' => 'nullable|string|max:255',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'service_id.required' => 'Layanan/Brand wajib dipilih',
        'name.required' => 'Nama metode pembayaran wajib diisi',
        'type.required' => 'Tipe metode pembayaran wajib dipilih',
        'account_number.required_if' => 'Nomor rekening wajib diisi untuk tipe rekening bank',
        'account_holder.required_if' => 'Nama pemilik rekening wajib diisi untuk tipe rekening bank',
    ];

    public function render()
    {
        $paymentMethods = PaymentMethod::query()
            ->with('service')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('account_number', 'like', '%' . $this->search . '%')
                    ->orWhere('account_holder', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterType, function ($query) {
                $query->where('type', $this->filterType);
            })
            ->when($this->status !== '', function ($query) {
                $query->where('is_active', $this->status === 'active');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.payment-method-management', [
            'paymentMethods' => $paymentMethods,
            'services' => \App\Models\Service::all(),
        ]);
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditMode = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        $this->payment_method_id = $paymentMethod->id;
        $this->service_id = $paymentMethod->service_id;
        $this->name = $paymentMethod->name;
        $this->type = $paymentMethod->type;
        $this->account_number = $paymentMethod->account_number;
        $this->account_holder = $paymentMethod->account_holder;
        $this->nmid = $paymentMethod->nmid;
        $this->is_active = $paymentMethod->is_active;

        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->isEditMode) {
                $paymentMethod = PaymentMethod::findOrFail($this->payment_method_id);

                // Handle QR code upload if present - save to public/uploads/images/qris
                if ($this->qr_code) {
                    // Delete old QR code if exists
                    if ($paymentMethod->qr_code_path) {
                        $oldFilePath = public_path($paymentMethod->qr_code_path);
                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath);
                        }
                    }

                    $filename = time() . '_' . $this->qr_code->getClientOriginalName();
                    
                    // Create directory if it doesn't exist
                    $uploadPath = public_path('uploads/images/qris');
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    
                    // Move file to public/uploads/images/qris
                    $this->qr_code->move($uploadPath, $filename);
                    $paymentMethod->qr_code_path = 'uploads/images/qris/' . $filename;
                }

                $paymentMethod->update([
                    'service_id' => $this->service_id,
                    'name' => $this->name,
                    'type' => $this->type,
                    'account_number' => $this->type === 'bank_account' ? $this->account_number : null,
                    'account_holder' => $this->type === 'bank_account' ? $this->account_holder : null,
                    'nmid' => $this->nmid,
                    'is_active' => $this->is_active,
                ]);

                session()->flash('success', 'Metode pembayaran berhasil diperbarui!');
            } else {
                // Handle QR code upload if present - save to public/uploads/images/qris
                $qrCodePath = null;
                if ($this->qr_code) {
                    $filename = time() . '_' . $this->qr_code->getClientOriginalName();
                    
                    // Create directory if it doesn't exist
                    $uploadPath = public_path('uploads/images/qris');
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    
                    // Move file to public/uploads/images/qris
                    $this->qr_code->move($uploadPath, $filename);
                    $qrCodePath = 'uploads/images/qris/' . $filename;
                }

                PaymentMethod::create([
                    'service_id' => $this->service_id,
                    'name' => $this->name,
                    'type' => $this->type,
                    'account_number' => $this->type === 'bank_account' ? $this->account_number : null,
                    'account_holder' => $this->type === 'bank_account' ? $this->account_holder : null,
                    'qr_code_path' => $qrCodePath,
                    'nmid' => $this->nmid,
                    'is_active' => $this->is_active,
                ]);

                session()->flash('success', 'Metode pembayaran berhasil ditambahkan!');
            }

            $this->closeModal();
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            logger()->error('Payment method save error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->payment_method_id = $id;
        $this->confirmDelete = true;
    }

    public function delete()
    {
        $paymentMethod = PaymentMethod::findOrFail($this->payment_method_id);

        // Delete QR code if exists
        if ($paymentMethod->qr_code_path) {
            $filePath = public_path($paymentMethod->qr_code_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $paymentMethod->delete();

        $this->resetForm();
        $this->confirmDelete = false;
        session()->flash('success', 'Metode pembayaran berhasil dihapus!');
    }

    public function cancelDelete()
    {
        $this->confirmDelete = false;
        $this->payment_method_id = null;
    }

    #[On('statusChanged')]
    public function toggleStatus($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->update([
            'is_active' => !$paymentMethod->is_active,
        ]);

        session()->flash('success', 'Status metode pembayaran berhasil diubah!');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['service_id', 'name', 'type', 'account_number', 'account_holder', 'qr_code', 'nmid', 'is_active', 'payment_method_id']);
        $this->isEditMode = false;
    }

    public function updatingType()
    {
        // Reset conditional fields when type changes
        if ($this->type === 'qris') {
            $this->account_number = null;
            $this->account_holder = null;
        } else {
            $this->qr_code = null;
        }
    }
}
