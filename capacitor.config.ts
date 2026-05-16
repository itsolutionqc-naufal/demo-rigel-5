import type { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
  appId: 'com.rigel.webview',
  appName: 'AgencyRigel.com',
  webDir: 'public',
  plugins: {
    PushNotifications: {
      presentationOptions: [],
    },
    SplashScreen: {
      launchShowDuration: 2000,
      backgroundColor: "#611f95",
      androidScaleType: "CENTER_INSIDE",
      showSpinner: false,
    },
  },
  server: {
    url: 'https://agencyrigel.com',
    cleartext: false,
  },
};

export default config;
