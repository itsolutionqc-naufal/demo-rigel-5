import type { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
  appId: 'com.rigel.webview',
  appName: 'Rigel Coins',
  webDir: 'public',
  server: {
    url: 'https://rigel.demowebjalan.com',
    cleartext: false,
  },
};

export default config;
