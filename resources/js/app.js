import './bootstrap';
import Handsontable from 'handsontable';
import 'handsontable/dist/handsontable.full.css';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
});

// Subscribe to the private channel for the current user
window.Echo.private(`App.Models.User.${window.userId}`)
    .listen('.unfilled.rows.reminder', (data) => {
        console.log('Notification received:', data);
        // Increment the notification counter
        let countEl = document.getElementById('notificationCount');
        if (countEl) {
            let currentCount = parseInt(countEl.innerText) || 0;
            countEl.innerText = currentCount + 1;
        }
        // Show a toastr notification
        toastr.info(data.message, 'Reminder');
        // Optionally, you might also want to prepend the notification in your dropdown:
        // (Assuming you have a container with id "notificationsContainer")
        let container = document.querySelector('.dropdown-menu.custom-dropdown');
        if (container) {
            const notificationItem = document.createElement('a');
            notificationItem.href = "#"; // Adjust the link as needed
            notificationItem.className = "list-group-item list-group-item-action d-flex align-items-center";
            // Use an icon based on your logic (example: green check for non-rejected)
            notificationItem.innerHTML = `<i class="fa fa-check text-success me-2"></i>
                <div>
                  <strong>No Tracking</strong>
                  <div>${data.message}</div>
                  <small class="text-muted">Just now</small>
                </div>`;
            // Prepend the new notification
            container.prepend(notificationItem);
        }
    });
