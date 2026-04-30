package com.rigel.webview;

import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.app.Notification;
import android.content.Context;
import android.content.Intent;
import android.os.Build;
import android.os.Handler;
import android.os.Looper;

import androidx.core.app.NotificationCompat;

import com.getcapacitor.BridgeActivity;
import com.google.firebase.messaging.FirebaseMessagingService;
import com.google.firebase.messaging.RemoteMessage;

import org.json.JSONException;
import org.json.JSONObject;

public class RigelFirebaseMessagingService extends FirebaseMessagingService {

    private static final String CHANNEL_ID = "rigel_transactions";
    private static final String CHANNEL_NAME = "Rigel Transactions";
    private static final String CHANNEL_DESCRIPTION = "Notifikasi transaksi sukses dan gagal";
    
    private static final String EVENT_NAME = "rigelNotification";

    @Override
    public void onNewToken(String token) {
        super.onNewToken(token);
        
        // Kirim token ke webview
        sendTokenToWebView(token);
    }
    
    private void sendTokenToWebView(final String token) {
        new Handler(Looper.getMainLooper()).post(() -> {
            try {
                BridgeActivity activity = (BridgeActivity) getApplicationContext();
                if (activity != null && activity.getBridge() != null) {
                    String js = String.format(
                        "window.dispatchEvent(new CustomEvent('rigelFcmToken', {detail: {token: '%s'}}))",
                        token
                    );
                    if (android.os.Build.VERSION.SDK_INT >= android.os.Build.VERSION_CODES.KITKAT) {
                        activity.getBridge().getWebView().evaluateJavascript(js, null);
                    } else {
                        activity.getBridge().getWebView().loadUrl("javascript:" + js);
                    }
                }
            } catch (Exception e) {
                android.util.Log.e("RigelFCM", "Failed to send token to webview", e);
            }
        });
    }

    @Override
    public void onMessageReceived(RemoteMessage remoteMessage) {
        super.onMessageReceived(remoteMessage);

        // Ambil data dari payload
        String title = remoteMessage.getNotification() != null ? 
            remoteMessage.getNotification().getTitle() : 
            getNotificationTitle(remoteMessage.getData());
        
        String body = remoteMessage.getNotification() != null ? 
            remoteMessage.getNotification().getBody() : 
            getNotificationBody(remoteMessage.getData());

        String transactionType = remoteMessage.getData().get("type");
        String transactionId = remoteMessage.getData().get("transaction_id");

        // Kirim ke webview (in-app notification)
        sendToWebView(title, body, transactionType, transactionId, remoteMessage.getData());

        // Tampilkan native notification (system notification)
        showNotification(title, body, transactionType, transactionId);
    }
    
    private void sendToWebView(String title, String body, String type, String transactionId, java.util.Map<String, String> data) {
        new Handler(Looper.getMainLooper()).post(() -> {
            try {
                JSONObject notificationData = new JSONObject();
                notificationData.put("title", title != null ? title : "Rigel Coins");
                notificationData.put("body", body != null ? body : "Anda mendapat notifikasi baru");
                notificationData.put("type", type != null ? type : "info");
                notificationData.put("transaction_id", transactionId != null ? transactionId : "");
                
                if (data != null) {
                    for (java.util.Map.Entry<String, String> entry : data.entrySet()) {
                        try {
                            notificationData.put(entry.getKey(), entry.getValue());
                        } catch (JSONException e) {
                            // Ignore JSON errors
                        }
                    }
                }
                
                // Emit event ke webview via Capacitor Bridge
                BridgeActivity activity = (BridgeActivity) getApplicationContext();
                if (activity != null && activity.getBridge() != null) {
                    activity.getBridge().getWebView().post(new Runnable() {
                        @Override
                        public void run() {
                            String js = String.format(
                                "window.dispatchEvent(new CustomEvent('%s', {detail: %s}))",
                                EVENT_NAME,
                                notificationData.toString()
                            );
                            if (android.os.Build.VERSION.SDK_INT >= android.os.Build.VERSION_CODES.KITKAT) {
                                activity.getBridge().getWebView().evaluateJavascript(js, null);
                            } else {
                                activity.getBridge().getWebView().loadUrl("javascript:" + js);
                            }
                        }
                    });
                }
            } catch (Exception e) {
                android.util.Log.e("RigelFCM", "Failed to send to webview", e);
            }
        });
    }

    private String getNotificationTitle(java.util.Map<String, String> data) {
        if (data == null) return "Rigel Coins";
        return data.getOrDefault("title", "Rigel Coins");
    }

    private String getNotificationBody(java.util.Map<String, String> data) {
        if (data == null) return "Anda mendapat notifikasi baru";
        return data.getOrDefault("body", "Anda mendapat notifikasi baru");
    }

    private void showNotification(String title, String body, String type, String transactionId) {
        Context context = getApplicationContext();
        
        // Buat channel untuk Android 8.0+
        createNotificationChannel();

        // Intent untuk membuka app saat notifikasi diklik
        Intent intent = new Intent(this, BridgeActivity.class);
        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TOP);
        intent.putExtra("transaction_id", transactionId);
        intent.putExtra("transaction_type", type);

        PendingIntent pendingIntent = PendingIntent.getActivity(
            this, 
            0, 
            intent, 
            PendingIntent.FLAG_ONE_SHOT | PendingIntent.FLAG_IMMUTABLE
        );

        // Set icon dan warna berdasarkan tipe transaksi
        int icon = android.R.drawable.ic_dialog_info;
        int color = 0xFF6200EE; // Default purple

        if ("success".equalsIgnoreCase(type)) {
            icon = android.R.drawable.ic_dialog_info;
            color = 0xFF4CAF50; // Green untuk sukses
        } else if ("failed".equalsIgnoreCase(type) || "gagal".equalsIgnoreCase(type)) {
            icon = android.R.drawable.ic_dialog_alert;
            color = 0xFFF44336; // Red untuk gagal
        }

        NotificationCompat.Builder builder = new NotificationCompat.Builder(this, CHANNEL_ID)
            .setSmallIcon(icon)
            .setContentTitle(title != null ? title : "Rigel Coins")
            .setContentText(body != null ? body : "Anda mendapat notifikasi baru")
            .setAutoCancel(true)
            .setContentIntent(pendingIntent)
            .setPriority(NotificationCompat.PRIORITY_HIGH)
            .setColor(color)
            .setCategory(NotificationCompat.CATEGORY_MESSAGE);

        // Tambahkan big text style untuk detail transaksi
        if (transactionId != null) {
            builder.setStyle(new NotificationCompat.BigTextStyle()
                .bigText(body != null ? body : "Anda mendapat notifikasi baru")
                .setBigContentTitle(title != null ? title : "Rigel Coins"));
        }

        NotificationManager notificationManager = (NotificationManager) 
            getSystemService(Context.NOTIFICATION_SERVICE);
        
        if (notificationManager != null) {
            // Gunakan transactionId sebagai notification ID jika ada, jika tidak gunakan hash
            int notificationId = transactionId != null ? transactionId.hashCode() : (int) System.currentTimeMillis();
            notificationManager.notify(notificationId, builder.build());
        }
    }

    private void createNotificationChannel() {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            NotificationChannel channel = new NotificationChannel(
                CHANNEL_ID,
                CHANNEL_NAME,
                NotificationManager.IMPORTANCE_HIGH
            );
            channel.setDescription(CHANNEL_DESCRIPTION);
            channel.enableVibration(true);
            channel.enableLights(true);

            NotificationManager notificationManager = getSystemService(NotificationManager.class);
            if (notificationManager != null) {
                notificationManager.createNotificationChannel(channel);
            }
        }
    }
}
