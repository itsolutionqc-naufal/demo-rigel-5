<?php

namespace App\Livewire;

use App\Models\PaymentMethod;
use Livewire\Component;

class PaymentMethodSelector extends Component
{
    public $paymentMethods = [];
    public $selectedPaymentMethodId = null;
    public $selectedPaymentMethod = null;

    public function mount($defaultValue = null)
    {
        $this->paymentMethods = PaymentMethod::where('is_active', true)->get();
        $this->selectedPaymentMethodId = $defaultValue;
    }

    public function updatedSelectedPaymentMethodId()
    {
        if ($this->selectedPaymentMethodId) {
            $this->selectedPaymentMethod = PaymentMethod::find($this->selectedPaymentMethodId);
        } else {
            $this->selectedPaymentMethod = null;
        }
        
        $this->dispatch('payment-method-selected', method: $this->selectedPaymentMethod);
    }

    public function render()
    {
        return view('livewire.payment-method-selector');
    }

    public function getSelectedPaymentMethodProperty()
    {
        if ($this->selectedPaymentMethodId) {
            return PaymentMethod::find($this->selectedPaymentMethodId);
        }
        return null;
    }
}