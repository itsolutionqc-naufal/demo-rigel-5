package com.rigel.webview;

import android.os.Bundle;
import android.os.Handler;
import android.os.Looper;
import android.util.Log;
import android.webkit.WebView;
import android.webkit.WebSettings;

import androidx.activity.OnBackPressedCallback;
import androidx.core.view.WindowCompat;

import com.getcapacitor.BridgeActivity;
import com.google.firebase.messaging.FirebaseMessaging;

public class MainActivity extends BridgeActivity {
    
    private static final String TAG = "RigelFCM";
    private static final long BACK_PRESS_INTERVAL_MS = 2000;
    private Handler mainHandler;
    private long lastBackPressedAt = 0L;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        
        mainHandler = new Handler(Looper.getMainLooper());
        
        // Fit content within system windows (avoid status bar overlap)
        WindowCompat.setDecorFitsSystemWindows(getWindow(), true);

        // Handle Android back button / gesture back
        getOnBackPressedDispatcher().addCallback(this, new OnBackPressedCallback(true) {
            @Override
            public void handleOnBackPressed() {
                WebView webView = (getBridge() != null) ? getBridge().getWebView() : null;

                if (webView != null) {
                    // Coba custom navigation via JavaScript (sesuai permintaan: back ke halaman sebelumnya)
                    // Panggil fungsi goBack() yang ada di window.RigelNavigation
                    webView.evaluateJavascript(
                        "window.RigelNavigation && window.RigelNavigation.goBack()",
                        value -> Log.d(TAG, "Custom back navigation called: " + value);
                    );

                    // Langsung cek apakah webView bisa goBack sebagai fallback
                    // Note: evaluateJavascript async, jadi fallback jalan juga
                    if (!webView.canGoBack()) {
                        // Tidak ada custom history dan webView history, exit dengan double tap
                        long now = System.currentTimeMillis();
                        if (now - lastBackPressedAt < BACK_PRESS_INTERVAL_MS) {
                            finishAffinity();
                            return;
                        }
                        lastBackPressedAt = now;
                        android.widget.Toast.makeText(MainActivity.this, "Tap again to exit", android.widget.Toast.LENGTH_SHORT).show();
                    } else {
                        // Ada webView history
                        lastBackPressedAt = 0L;
                        webView.goBack();
                    }
                } else {
                    // WebView null, exit app
                    long now = System.currentTimeMillis();
                    if (now - lastBackPressedAt < BACK_PRESS_INTERVAL_MS) {
                        finishAffinity();
                        return;
                    }

                    lastBackPressedAt = now;
                    android.widget.Toast.makeText(MainActivity.this, "Tap again to exit", android.widget.Toast.LENGTH_SHORT).show();
                }
            }
        });
        
        // Initialize Firebase Cloud Messaging
        initializeFCM();
    }

    private void initializeFCM() {
        // Subscribe ke topic default untuk semua notifikasi
        FirebaseMessaging.getInstance().subscribeToTopic("transactions")
            .addOnCompleteListener(task -> {
                if (task.isSuccessful()) {
                    Log.d(TAG, "Subscribed to transactions topic");
                } else {
                    Log.e(TAG, "Failed to subscribe to transactions topic", task.getException());
                }
            });

        // Get FCM Token
        FirebaseMessaging.getInstance().getToken()
            .addOnCompleteListener(task -> {
                if (!task.isSuccessful()) {
                    Log.w(TAG, "Fetching FCM token failed", task.getException());
                    return;
                }

                // Get the Token
                String token = task.getResult();
                Log.d(TAG, "FCM Token: " + token);
                
                // Kirim token ke webview
                sendTokenToWebView(token);
                
                // Catatan: Token bisa dikirim ke server backend untuk disimpan
                sendTokenToServer(token);
            });
    }

    private void sendTokenToWebView(String token) {
        mainHandler.post(() -> {
            WebView webView = (getBridge() != null) ? getBridge().getWebView() : null;
            if (webView != null) {
                String js = String.format(
                    "window.dispatchEvent(new CustomEvent('rigelFcmToken', {detail: {token: '%s'}}))",
                    token
                );
                if (android.os.Build.VERSION.SDK_INT >= android.os.Build.VERSION_CODES.KITKAT) {
                    webView.evaluateJavascript(js, null);
                } else {
                    webView.loadUrl("javascript:" + js);
                }
                Log.d(TAG, "Token sent to webview");
            } else {
                Log.w(TAG, "Bridge/WebView belum siap, token belum bisa dikirim ke webview");
            }
        });
    }

    private void sendTokenToServer(String token) {
        // Catatan: Implementasi ini perlu disesuaikan dengan backend
        // Contoh: Kirim token ke server untuk disimpan
        // Biasanya akan dilakukan via webview/javascript bridge
        
        // Log untuk debugging
        Log.d(TAG, "Token yang harus dikirim ke server: " + token);
        
        // Bisa juga gunakan Volley atau OkHttp untuk HTTP request
        // Implementation tergantung kebutuhan
    }
}
