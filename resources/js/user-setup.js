const firebaseConfig = {
  apiKey: process.env.MIX_FIREBASE_API_KEY,
  projectId: process.env.MIX_FIREBASE_PROJECT_ID,
  messagingSenderId: process.env.MIX_FIREBASE_MESSAGING_SENDER_ID,
  appId: process.env.MIX_FIREBASE_APP_ID
};

firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

$('#enable-notifications').click(function() {
  messaging.getToken()
    .then(function(token) {
      $('#_fcm_token').val(token);
      $('#notifications-form').trigger('submit');
    });
});