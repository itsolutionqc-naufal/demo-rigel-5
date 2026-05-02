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

        const PLATFORM = String(Capacitor.getPlatform() || 'android');
        const STORAGE_TOKEN = 'rigel_fcm_token';
        const STORAGE_REGISTERED = 'rigel_fcm_token_registered';
        const STORAGE_ATTEMPTS = 'rigel_fcm_token_attempts';

        localStorage.setItem(STORAGE_TOKEN, fcmToken);

        const isAlreadyRegistered = (() => {
            try {
                const existing = JSON.parse(localStorage.getItem(STORAGE_REGISTERED) || 'null');
                return existing && existing.token === fcmToken && existing.platform === PLATFORM;
            } catch {
                return false;
            }
        })();

        const postDeviceToken = async () => {
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrf) {
                throw new Error('missing CSRF token meta tag');
            }

            const response = await fetch('/device-tokens', {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify({
                    token: fcmToken,
                    platform: PLATFORM,
                }),
            });

            if (!response.ok) {
                throw new Error(`device-tokens HTTP ${response.status}`);
            }

            const contentType = response.headers.get('content-type') || '';
            if (!contentType.includes('application/json')) {
                throw new Error('device-tokens returned non-JSON (likely not authenticated)');
            }

            const data = await response.json();
            if (!data?.success) {
                throw new Error('device-tokens returned success=false');
            }

            localStorage.setItem(
                STORAGE_REGISTERED,
                JSON.stringify({ token: fcmToken, platform: PLATFORM, at: Date.now() }),
            );
            localStorage.removeItem(STORAGE_ATTEMPTS);
        };

        const scheduleRetry = (reason) => {
            const maxAttempts = 6;
            const attempts = Number.parseInt(localStorage.getItem(STORAGE_ATTEMPTS) || '0', 10) || 0;
            if (attempts >= maxAttempts) {
                console.warn('[push] device token registration giving up', { reason });
                return;
            }

            localStorage.setItem(STORAGE_ATTEMPTS, String(attempts + 1));
            const delayMs = Math.min(60_000, 3_000 * Math.pow(2, attempts));
            console.warn('[push] device token registration retry scheduled', { reason, attempts: attempts + 1, delayMs });
            setTimeout(() => void postDeviceToken().catch((err) => scheduleRetry(err?.message || String(err))), delayMs);
        };

        if (!isAlreadyRegistered) {
            await postDeviceToken().catch((err) => scheduleRetry(err?.message || String(err)));
        }
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
