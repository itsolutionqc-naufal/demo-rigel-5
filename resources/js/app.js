/**
 * Application JavaScript
 * 
 * This file is loaded for all pages.
 * Add your custom JavaScript here.
 */

import { Capacitor } from '@capacitor/core';

async function initNativePushNotifications() {
    if (!Capacitor.isNativePlatform()) {
        return;
    }

    const [{ PushNotifications }, { FCM }] = await Promise.all([
        import('@capacitor/push-notifications'),
        import('@capacitor-community/fcm'),
    ]);

    try {
        const permission = await PushNotifications.requestPermissions();
        if (permission.receive !== 'granted') {
            return;
        }

        await PushNotifications.register();

        PushNotifications.addListener('registrationError', (error) => {
            console.error('[push] registrationError', error);
        });

        PushNotifications.addListener('pushNotificationReceived', (notification) => {
            // Let system handle notification display when app is in foreground
            // No custom handling needed - notification shows in system notification bar
        });

        PushNotifications.addListener('pushNotificationActionPerformed', (action) => {
            const data = action?.notification?.data ?? {};
            const transactionCode = data.transaction_code ?? data.transactionCode ?? null;

            if (transactionCode) {
                window.location.href = `/app/transaction-status/${encodeURIComponent(transactionCode)}`;
                return;
            }

            window.location.href = '/app/notification';
        });

        const tokenResult = await FCM.getToken();
        const fcmToken = tokenResult?.token;

        if (!fcmToken) {
            console.warn('[push] missing FCM token');
            return;
        }

        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrf) {
            console.warn('[push] missing CSRF token meta tag');
            return;
        }

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
    } catch (error) {
        console.error('[push] init error', error);
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
