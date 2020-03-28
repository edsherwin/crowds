importScripts("https://www.gstatic.com/firebasejs/7.13.1/firebase-app.js");
importScripts(
  "https://www.gstatic.com/firebasejs/7.13.1/firebase-messaging.js"
);

firebase.initializeApp({
  apiKey: process.env.MIX_FIREBASE_API_KEY,
  projectId: process.env.MIX_FIREBASE_PROJECT_ID,
  messagingSenderId: process.env.MIX_FIREBASE_MESSAGING_SENDER_ID,
  appId: process.env.MIX_FIREBASE_APP_ID
});

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
 
  const notificationTitle = "Background Message Title";
  const notificationOptions = {
    body: "Background Message body.",
    icon: "/firebase-logo.png"
  };

  return self.registration.showNotification(
    notificationTitle,
    notificationOptions
  );
});
