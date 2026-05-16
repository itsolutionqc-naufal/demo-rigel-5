import type { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
  appId: 'com.rigel.webview',
  appName: 'Rigel Coins',
  webDir: 'public',
  plugins: {
    PushNotifications: {
      presentationOptions: [],
    },
  },
  server: {
    url: 'https://agencyrigel.com',
    cleartext: false,
  },
};

export default config;
