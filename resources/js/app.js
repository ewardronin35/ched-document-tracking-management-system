import './bootstrap';
import Handsontable from 'handsontable';
import 'handsontable/dist/handsontable.full.css';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
});

window.Echo.private(`App.Models.User.${userId}`)
    .listen('.document.status.updated', (notification) => {
        console.log('Notification received:', notification);
        // Display the notification using your preferred method, e.g., toastr
        toastr.success(notification.message, 'Document Status Updated');
    });