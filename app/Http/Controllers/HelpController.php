<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    /**
     * Display the help page with WhatsApp message settings.
     */
    public function index()
    {
        $whatsappNumber = Setting::getWhatsAppNumber();
        $whatsappMessageTemplate = Setting::getWhatsAppMessageTemplate();
        $whatsappWalletTemplate = Setting::getWhatsAppWalletTemplate();
        $whatsappJobTemplate = Setting::getWhatsAppJobTemplate();

        return view('help.index', compact(
            'whatsappNumber', 
            'whatsappMessageTemplate',
            'whatsappWalletTemplate',
            'whatsappJobTemplate'
        ));
    }

    /**
     * Update WhatsApp message settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'whatsapp_number' => 'required|string|max:20',
            'whatsapp_message_template' => 'required|string|max:500',
            'whatsapp_wallet_template' => 'required|string|max:500',
            'whatsapp_job_template' => 'required|string|max:500',
        ]);

        Setting::setWhatsAppNumber($request->whatsapp_number);
        Setting::setWhatsAppMessageTemplate($request->whatsapp_message_template);
        Setting::setWhatsAppWalletTemplate($request->whatsapp_wallet_template);
        Setting::setWhatsAppJobTemplate($request->whatsapp_job_template);

        return redirect()->route('help.index')->with('success', 'Pengaturan WhatsApp berhasil diperbarui!');
    }
}
