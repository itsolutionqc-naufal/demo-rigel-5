// Service Worker for Rigel Coins Push Notifications
const CACHE_NAME = 'rigel-coins-v1';
const OFFLINE_URL = '/';

// Install event
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll([
                OFFLINE_URL,
                '/',
                '/manifest.json'
            ]);
        })
    );
    self.skipWaiting();
});

// Activate event
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((name) => name !== CACHE_NAME)
                    .map((name) => caches.delete(name))
            );
        })
    );
    self.clients.claim();
});

// Push notification event
self.addEventListener('push', (event) => {
    if (!event.data) return;
    
    let data = {};
    try {
        data = event.data.json();
    } catch (e) {
        data = {
            title: 'Rigel Coins',
            body: event.data.text(),
            icon: '/icons/icon-192x192.png'
        };
    }
    
    const options = {
        body: data.body || 'Anda mendapat notifikasi baru',
        icon: data.icon || '/icons/icon-192x192.png',
        badge: '/icons/badge.png',
        vibrate: [100, 50, 100],
        data: {
            dateOfArrival: Date.now(),
            url: data.url || '/',
            transaction_id: data.transaction_id || null,
            type: data.type || 'info'
        },
        actions: [
            {
                action: 'open',
                title: 'Buka App'
            },
            {
                action: 'close',
                title: 'Tutup'
            }
        ],
        tag: data.transaction_id || 'default',
        renotify: true
    };
    
    // Determine notification color based on type
    if (data.type === 'success') {
        options.tag = 'success';
    } else if (data.type === 'failed' || data.type === 'error') {
        options.tag = 'failed';
    }
    
    event.waitUntil(
        self.registration.showNotification(data.title || 'Rigel Coins', options)
    );
});

// Notification click event
self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    
    if (event.action === 'close') return;
    
    const urlToOpen = event.notification.data?.url || '/';
    
    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((windowClients) => {
            // Check if there's already a window open
            for (const client of windowClients) {
                if (client.url.includes(self.location.origin) && 'focus' in client) {
                    client.navigate(urlToOpen);
                    return client.focus();
                }
            }
            // If not, open new window
            if (clients.openWindow) {
                return clients.openWindow(urlToOpen);
            }
        })
    );
});

// Background sync for offline actions
self.addEventListener('sync', (event) => {
    if (event.tag === 'sync-transactions') {
        event.waitUntil(syncTransactions());
    }
});

async function syncTransactions() {
    // Implement offline sync if needed
    console.log('Syncing transactions...');
}

// Periodic background sync (if supported)
self.addEventListener('periodicsync', (event) => {
    if (event.tag === 'check-transactions') {
        event.waitUntil(checkForNewTransactions());
    }
});

async function checkForNewTransactions() {
    console.log('Checking for new transactions...');
}