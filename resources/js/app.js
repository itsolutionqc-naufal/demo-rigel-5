/**
 * Application JavaScript
 * 
 * This file is loaded for all pages.
 * Add your custom JavaScript here.
 */

import { Capacitor } from '@capacitor/core';

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

    const [{ PushNotifications }, { FCM }, { LocalNotifications }] = await Promise.all([
        import('@capacitor/push-notifications'),
        import('@capacitor-community/fcm'),
        import('@capacitor/local-notifications'),
    ]);

    try {
        const permission = await PushNotifications.requestPermissions();
        if (permission.receive !== 'granted') {
            return;
        }

        await PushNotifications.register();

        try {
            await PushNotifications.deleteChannel({ id: 'rigel_alerts' });
        } catch (_err) {
            // Ignore if channel does not exist yet.
        }

        await PushNotifications.createChannel({
            id: 'rigel_alerts',
            name: 'Rigel Alerts',
            description: 'Notifikasi status transaksi Rigel',
            importance: 5,
            visibility: 1,
            sound: 'default',
            lights: true,
            vibration: true,
        });

        const localPermission = await LocalNotifications.checkPermissions();
        if (localPermission.display !== 'granted') {
            const requestedLocalPermission = await LocalNotifications.requestPermissions();
            if (requestedLocalPermission.display !== 'granted') {
                console.warn('[push] local notification display permission not granted');
            }
        }

        PushNotifications.addListener('registrationError', (error) => {
            console.error('[push] registrationError', error);
        });

        PushNotifications.addListener('pushNotificationReceived', (notification) => {
            const title = notification?.title ?? 'Rigel Alerts';
            const body = notification?.body ?? 'Ada pembaruan transaksi.';
            const data = normalizeNotificationData(notification?.data ?? {});

            if (shouldSkipDuplicateForegroundNotification(title, body, data)) {
                return;
            }

            void LocalNotifications.schedule({
                notifications: [
                    {
                        id: Math.floor(Date.now() % 2147483647),
                        title,
                        body,
                        extra: data,
                        channelId: 'rigel_alerts',
                        sound: 'default',
                    },
                ],
            });
        });

        PushNotifications.addListener('pushNotificationActionPerformed', (action) => {
            const data = normalizeNotificationData(action?.notification?.data ?? {});
            navigateFromNotificationData(data);
        });

        LocalNotifications.addListener('localNotificationActionPerformed', (action) => {
            const data = normalizeNotificationData(
                action?.notification?.extra ?? action?.notification?.data ?? {},
            );
            navigateFromNotificationData(data);
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
        initAndroidBackButton();
    });
}

// Android hardware back button — "Tap again to exit"
function initAndroidBackButton() {
    if (!Capacitor.isNativePlatform()) return;

    import('@capacitor/app').then(({ App }) => {
        let backPressedOnce = false;
        let backToast = null;

        // Dashboard routes — halaman "root" tiap role
        const exitRoutes = ['/dashboard', '/marketing/dashboard', '/app'];

        function isExitPage() {
            const path = window.location.pathname.replace(/\/$/, '') || '/';
            return exitRoutes.some(r => path === r || path.startsWith(r + '/') && r === '/app');
        }

        function showTapAgainToast() {
            if (backToast) {
                backToast.remove();
                backToast = null;
            }
            backToast = document.createElement('div');
            backToast.textContent = 'Tap sekali lagi untuk keluar';
            backToast.style.cssText = `
                position: fixed;
                bottom: calc(24px + env(safe-area-inset-bottom));
                left: 50%;
                transform: translateX(-50%);
                background: rgba(0,0,0,0.78);
                color: #fff;
                padding: 10px 22px;
                border-radius: 24px;
                font-size: 14px;
                z-index: 99999;
                pointer-events: none;
                white-space: nowrap;
            `;
            document.body.appendChild(backToast);
        }

        function hideTapAgainToast() {
            if (backToast) {
                backToast.remove();
                backToast = null;
            }
        }

        App.addListener('backButton', ({ canGoBack }) => {
            if (canGoBack) {
                // Ada history — navigasi balik biasa
                window.history.back();
                return;
            }

            // Tidak ada history / sudah di halaman root
            if (isExitPage()) {
                if (backPressedOnce) {
                    hideTapAgainToast();
                    App.exitApp();
                    return;
                }
                backPressedOnce = true;
                showTapAgainToast();
                setTimeout(() => {
                    backPressedOnce = false;
                    hideTapAgainToast();
                }, 2500);
            } else {
                window.history.back();
            }
        });
    }).catch(() => {
        // @capacitor/app tidak tersedia, skip
    });
}
