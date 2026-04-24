<?php

namespace App\Support;

class TelegramMessageFormatter
{
    public const DIVIDER_WIDTH = 32;

    public static function divider(): string
    {
        return str_repeat('=', self::DIVIDER_WIDTH)."\n\n";
    }

    public static function heading(string $title): string
    {
        $cleanTitle = self::sanitizePlain($title);
        return "*== {$cleanTitle} ==*\n".self::divider();
    }

    public static function bullet(string $label, string $value, bool $code = true): string
    {
        $safeLabel = self::sanitizePlain($label);
        $safeValue = self::code($value);

        if ($code) {
            return "- *{$safeLabel}:* `{$safeValue}`\n";
        }

        return "- *{$safeLabel}:* ".self::sanitizePlain($value)."\n";
    }

    public static function code(string $value): string
    {
        $clean = self::sanitizePlain($value);
        return str_replace('`', "'", $clean);
    }

    protected static function sanitizePlain(string $value): string
    {
        $clean = str_replace(["\r\n", "\r", "\n", "\t"], ' ', $value);
        $clean = trim(preg_replace('/\s+/', ' ', $clean) ?? '');
        return $clean;
    }
}
