// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.18.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.18.0/firebase-messaging.js');
/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
*/
firebase.initializeApp({
    apiKey: "AIzaSyAqLYJH-iitSAsLJjrW2MGHs2EDCjxsCGk",
    authDomain: "mensani-e1af8.firebaseapp.com",
    projectId: "mensani-e1af8",
    storageBucket: "mensani-e1af8.appspot.com",
    messagingSenderId: "971751303798",
    appId: "1:971751303798:web:4c82959142d616234ecd57"

});


// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
var myUrl = "";
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) {
    console.log("Message received.", payload);
    const title = "Hello world is awesome";
    const options = {
        body: payload.data.body,
        icon: payload.data.icon,
        data:{
            time: new Date(Date.now()).toString(),
            click_action : payload.data.click_action
        }
    };
    myUrl = payload.data.click_action;
    self.addEventListener('notificationclick', function(event) {
        event.waitUntil(self.clients.openWindow(myUrl));
        event.notification.close();
    });
    return self.registration.showNotification(
        title,
        options,
    );
});


//   self.addEventListener('notificationclick', function(event) {
//     var redirect_url = event.notification.data.click_action;
//     event.notification.close();
//     event.waitUntil(
//       clients
//         .matchAll({
//           type: "window"
//         })
//         .then(function(clientList) {
//           console.log(clientList);
//           for (var i = 0; i < clientList.length; i++) {
//             var client = clientList[i];
//             if (client.url === "/" && "focus" in client) {
//               return client.focus();
//             }
//           }
//           if (clients.openWindow) {
//             return clients.openWindow(redirect_url);
//           }
//         })
//     );
//   });