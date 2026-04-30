/**
 * Rigel Coins - Push Notification Handler
 * Handles Web Push Notifications for PWA
 */

class RigelNotifications {
    constructor() {
        this.swRegistration = null;
        this.vapidPublicKey = null;
        this.subscription = null;
        
        // Initialize on load
        this.init();
    }
    
    async init() {
        // Register service worker
        await this.registerServiceWorker();
        
        // Setup event listeners
        this.setupEventListeners();
        
        // Listen for FCM token from native app (if running in Capacitor)
        this.setupNativeListener();
    }
    
    async registerServiceWorker() {
        if ('serviceWorker' in navigator) {
            try {
                this.swRegistration = await navigator.serviceWorker.register('/service-worker.js');
                console.log('Service Worker registered:', this.swRegistration);
                
                // Check for existing subscription
                await this.checkSubscription();
            } catch (error) {
                console.error('Service Worker registration failed:', error);
            }
        }
    }
    
    async checkSubscription() {
        if (!this.swRegistration) return;
        
        this.subscription = await this.swRegistration.pushManager.getSubscription();
        
        if (!this.subscription) {
            // Auto-subscribe if in Capacitor app or user has granted permission
            await this.subscribe();
        }
    }
    
    async subscribe() {
        if (!this.swRegistration) return;
        
        try {
            // Request notification permission
            const permission = await Notification.requestPermission();
            
            if (permission === 'granted') {
                // Subscribe to push
                this.subscription = await this.swRegistration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: this.urlBase64ToUint8Array(
                        this.vapidPublicKey || 'BEl62iUYgUivxIkv69yViEuiBIa-Ib9-SkvMeAtA3LFgDzkrxZJjSgSnfckjBJuBkr3qBUYIHBQFLXYp5Nksh8U'
                    )
                });
                
                console.log('Push subscription:', this.subscription);
                
                // Send subscription to server
                await this.saveSubscription(this.subscription);
            }
        } catch (error) {
            console.error('Subscribe failed:', error);
        }
    }
    
    async saveSubscription(subscription) {
        try {
            const response = await fetch('/api/device-tokens', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Authorization': `Bearer ${await this.getApiToken()}`
                },
                body: JSON.stringify({
                    token: JSON.stringify(subscription),
                    platform: 'web',
                    subscription: subscription
                })
            });
            
            if (response.ok) {
                console.log('Subscription saved to server');
            }
        } catch (error) {
            console.error('Failed to save subscription:', error);
        }
    }
    
    async getApiToken() {
        // Get API token from page data or localStorage
        return localStorage.getItem('api_token') || '';
    }
    
    setupEventListeners() {
        // Listen for notifications from service worker
        if (navigator.serviceWorker) {
            navigator.serviceWorker.addEventListener('message', (event) => {
                this.handleServiceWorkerMessage(event.data);
            });
        }
        
        // Listen for custom notification events (from Capacitor native app)
        window.addEventListener('rigelNotification', (event) => {
            this.handleRigelNotification(event.detail);
        });
        
        // Listen for FCM token from native app
        window.addEventListener('rigelFcmToken', (event) => {
            this.handleFcmToken(event.detail.token);
        });
    }
    
    setupNativeListener() {
        // If running in Capacitor app, native FCM will send token via events
        // This is already handled in setupEventListeners
    }
    
    handleServiceWorkerMessage(data) {
        if (data.type === 'PUSH_NOTIFICATION') {
            this.showInAppNotification(data);
        }
    }
    
    handleRigelNotification(data) {
        console.log('Rigel Notification received:', data);
        
        // Show in-app notification (toast/alert)
        this.showInAppNotification({
            title: data.title,
            body: data.body,
            type: data.type,
            transaction_id: data.transaction_id
        });
        
        // Also show browser notification if permission granted
        if (Notification.permission === 'granted') {
            this.showBrowserNotification(data);
        }
    }
    
    handleFcmToken(token) {
        console.log('FCM Token received:', token);
        
        // Save token to server
        this.saveTokenToServer(token);
    }
    
    async saveTokenToServer(token) {
        try {
            const response = await fetch('/api/device-tokens', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    token: token,
                    platform: 'android' // or 'ios' based on device
                })
            });
            
            if (response.ok) {
                console.log('Token saved to server');
            }
        } catch (error) {
            console.error('Failed to save token:', error);
        }
    }
    
    showInAppNotification(data) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `rigel-notification rigel-notification-${data.type || 'info'}`;
        notification.innerHTML = `
            <div class="rigel-notification-icon">
                ${this.getIcon(data.type)}
            </div>
            <div class="rigel-notification-content">
                <div class="rigel-notification-title">${data.title || 'Rigel Coins'}</div>
                <div class="rigel-notification-body">${data.body || ''}</div>
            </div>
            <button class="rigel-notification-close">&times;</button>
        `;
        
        // Add styles if not exists
        this.addNotificationStyles();
        
        // Add to body
        document.body.appendChild(notification);
        
        // Show with animation
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
        
        // Close button
        notification.querySelector('.rigel-notification-close').addEventListener('click', () => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        });
        
        // Click to go to transaction
        notification.addEventListener('click', () => {
            if (data.transaction_id) {
                window.location.href = `/sales/${data.transaction_id}`;
            }
        });
    }
    
    showBrowserNotification(data) {
        if (!('Notification' in window)) return;
        
        new Notification(data.title || 'Rigel Coins', {
            body: data.body,
            icon: '/icons/icon-192x192.png',
            badge: '/icons/badge.png',
            tag: data.transaction_id || 'default',
            data: {
                url: data.transaction_id ? `/sales/${data.transaction_id}` : '/'
            }
        });
    }
    
    getIcon(type) {
        const icons = {
            success: '✅',
            failed: '❌',
            error: '❌',
            info: 'ℹ️',
            warning: '⚠️'
        };
        return icons[type] || icons.info;
    }
    
    addNotificationStyles() {
        if (document.getElementById('rigel-notification-styles')) return;
        
        const styles = document.createElement('style');
        styles.id = 'rigel-notification-styles';
        styles.textContent = `
            .rigel-notification {
                position: fixed;
                top: 20px;
                right: 20px;
                max-width: 360px;
                background: #fff;
                border-radius: 12px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.15);
                padding: 16px;
                display: flex;
                align-items: flex-start;
                gap: 12px;
                z-index: 99999;
                transform: translateX(400px);
                transition: transform 0.3s ease;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            }
            
            .rigel-notification.show {
                transform: translateX(0);
            }
            
            .rigel-notification-success {
                border-left: 4px solid #10B981;
            }
            
            .rigel-notification-success .rigel-notification-icon {
                color: #10B981;
            }
            
            .rigel-notification-failed,
            .rigel-notification-error {
                border-left: 4px solid #EF4444;
            }
            
            .rigel-notification-failed .rigel-notification-icon,
            .rigel-notification-error .rigel-notification-icon {
                color: #EF4444;
            }
            
            .rigel-notification-info {
                border-left: 4px solid #3B82F6;
            }
            
            .rigel-notification-info .rigel-notification-icon {
                color: #3B82F6;
            }
            
            .rigel-notification-warning {
                border-left: 4px solid #F59E0B;
            }
            
            .rigel-notification-warning .rigel-notification-icon {
                color: #F59E0B;
            }
            
            .rigel-notification-icon {
                font-size: 24px;
                flex-shrink: 0;
            }
            
            .rigel-notification-content {
                flex: 1;
            }
            
            .rigel-notification-title {
                font-weight: 600;
                font-size: 14px;
                color: #1F2937;
                margin-bottom: 4px;
            }
            
            .rigel-notification-body {
                font-size: 13px;
                color: #6B7280;
                line-height: 1.4;
            }
            
            .rigel-notification-close {
                background: none;
                border: none;
                font-size: 20px;
                color: #9CA3AF;
                cursor: pointer;
                padding: 0;
                line-height: 1;
            }
            
            .rigel-notification-close:hover {
                color: #6B7280;
            }
        `;
        document.head.appendChild(styles);
    }
    
    urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);
        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    window.rigelNotifications = new RigelNotifications();
});

// Also init immediately if DOM already loaded
if (document.readyState === 'complete' || document.readyState === 'interactive') {
    window.rigelNotifications = new RigelNotifications();
}