import type { CapacitorConfig } from '@capacitor/cli';

const serverUrl =
  process.env.VITE_CAP_SERVER_URL ||
  process.env.CAP_SERVER_URL ||
  'https://agencyrigel.com';

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
    url: serverUrl,
    cleartext: false,
  },
};

export default config;
