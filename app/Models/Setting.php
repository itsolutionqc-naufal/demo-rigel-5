<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        // Cast value based on type
        return match($setting->type) {
            'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'number' => is_numeric($setting->value) ? floatval($setting->value) : $default,
            default => $setting->value,
        };
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, $value, string $type = 'text', string $group = 'general'): void
    {
        self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
            ]
        );
    }

    /**
     * Get WhatsApp support number
     */
    public static function getWhatsAppNumber(): string
    {
        return self::get('whatsapp_number', '6281234567890');
    }

    /**
     * Get WhatsApp message template
     */
    public static function getWhatsAppMessageTemplate(): string
    {
        return self::get('whatsapp_message_template', 'Halo,%20saya%20membutuhkan%20bantuan%20dengan%20sistem%20Rigel%20Agency.%20Mohon%20bantuannya.');
    }

    /**
     * Get WhatsApp message template for wallet withdrawal
     */
    public static function getWhatsAppWalletTemplate(): string
    {
        return self::get('whatsapp_wallet_template', 'Halo%20kak,%20mau%20nanya%20soal%20status%20pencairan%20saya');
    }

    /**
     * Get WhatsApp message template for job history
     */
    public static function getWhatsAppJobTemplate(): string
    {
        return self::get('whatsapp_job_template', 'Halo%20kak,%20mau%20nanya%20soal%20status%20transaksi%20saya');
    }

    /**
     * Set WhatsApp support number
     */
    public static function setWhatsAppNumber(string $number): void
    {
        self::set('whatsapp_number', $number, 'text', 'whatsapp');
    }

    /**
     * Set WhatsApp message template
     */
    public static function setWhatsAppMessageTemplate(string $template): void
    {
        self::set('whatsapp_message_template', $template, 'text', 'whatsapp');
    }

    /**
     * Set WhatsApp message template for wallet withdrawal
     */
    public static function setWhatsAppWalletTemplate(string $template): void
    {
        self::set('whatsapp_wallet_template', $template, 'text', 'whatsapp');
    }

    /**
     * Set WhatsApp message template for job history
     */
    public static function setWhatsAppJobTemplate(string $template): void
    {
        self::set('whatsapp_job_template', $template, 'text', 'whatsapp');
    }
}
