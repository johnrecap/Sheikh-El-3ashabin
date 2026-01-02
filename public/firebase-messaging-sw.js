importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js');
let config = {
        apiKey: "AIzaSyAIWTpgWVVzqTDV1fdJcHNZGLVJLyK7g4E",
        authDomain: "storeking-9a1da.firebaseapp.com",
        projectId: "storeking-9a1da",
        storageBucket: "storeking-9a1da.firebasestorage.app",
        messagingSenderId: "116885060694",
        appId: "1:116885060694:web:e9217095209ea5a1408ff7",
        measurementId: "G-0K9LVW114N",
 };
firebase.initializeApp(config);
const messaging = firebase.messaging();
messaging.onBackgroundMessage((payload) => {
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: '/images/required/firebase-logo.png'
    };
    self.registration.showNotification(notificationTitle, notificationOptions);
});
