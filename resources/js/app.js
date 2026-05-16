/**
 * Application JavaScript
 * 
 * This file is loaded for all pages.
 * Add your custom JavaScript here.
 */

import { Capacitor } from '@capacitor/core';

// Make Capacitor globally available for inline scripts (e.g., download prompt modal)
window.Capacitor = Capacitor;

const foregroundNotificationCache = new Map();

function normalizeNotificationData(input) {
    if (!input || typeof input !== 'object') {
        return {};
    }

    return Object.fromEntries(
        Object.entries(input).map(([key, value]) => [key, String(value ?? '')]),
    );
}

function buildForegroundNotificationFingerprint(title, body, data) {
    const transactionCode = data.transaction_code ?? data.transactionCode ?? '';
    return `${title}|${body}|${transactionCode}`;
}

function shouldSkipDuplicateForegroundNotification(title, body, data) {
    const now = Date.now();
    const dedupeWindowMs = 3000;
    const fingerprint = buildForegroundNotificationFingerprint(title, body, data);
    const previousTs = foregroundNotificationCache.get(fingerprint);

    for (const [key, ts] of foregroundNotificationCache.entries()) {
        if (now - ts > dedupeWindowMs) {
            foregroundNotificationCache.delete(key);
        }
    }

    if (previousTs && now - previousTs < dedupeWindowMs) {
        return true;
    }

    foregroundNotificationCache.set(fingerprint, now);
    return false;
}

function navigateFromNotificationData(data) {
    const transactionCode = data.transaction_code ?? data.transactionCode ?? null;

    if (transactionCode) {
        window.location.href = `/app/transaction-status/${encodeURIComponent(transactionCode)}`;
        return;
    }

    window.location.href = '/app/notification';
}

async function initNativePushNotifications() {
    if (!Capacitor.isNativePlatform()) {
        return;
    }

    try {
        const { PushNotifications } = await import('@capacitor/push-notifications');

        let permStatus = await PushNotifications.checkPermissions();

        if (permStatus.receive === 'prompt') {
            permStatus = await PushNotifications.requestPermissions();
        }

        if (permStatus.receive !== 'granted') {
            console.warn('[push] Push notification permission not granted');
            return;
        }

        await PushNotifications.register();

        PushNotifications.addListener('registration', async (token) => {
            const fcmToken = token.value;
            if (!fcmToken) return;

            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            try {
                await fetch('/device-tokens', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({
                        token: fcmToken,
                        platform: Capacitor.getPlatform(),
                    }),
                });
                console.log('[push] FCM token registered successfully');
            } catch (err) {
                console.error('[push] Failed to send token to server', err);
            }
        });

        PushNotifications.addListener('registrationError', (error) => {
            console.error('[push] Registration error', error);
        });

    } catch (error) {
        console.error('[push] Capacitor PushNotifications plugin not available', error);
    }
}

// Initialize Lucide icons when DOM is ready
if (typeof document !== 'undefined') {
    document.addEventListener('DOMContentLoaded', () => {
        // Lucide icons are loaded via CDN in the layout
        // This will re-initialize icons after dynamic content loads
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        initNativePushNotifications();
    });
}
