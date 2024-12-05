import Echo from "laravel-echo";

import Pusher from "pusher-js";
window.Pusher = Pusher;

window.Echo = new Echo({
    // authEndpoint: "http://127.0.0.1:8000/api/broadcasting/auth",
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    wsPort: 443,
});

console.log("Echo initialized");
// Pusher.logToConsole = true;
