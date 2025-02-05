<ul class="dropdown-menu custom-dropdown dropdown-menu-end" id="notificationList">
    @if(auth()->user()->unreadNotifications()->count() > 0)
        @foreach(auth()->user()->unreadNotifications()->take(5)->get() as $notification)
            <li>
                <a class="dropdown-item" href="{{ url('/notifications', $notification->id) }}">
                    {{ $notification->data['message'] ?? 'Notification received' }}
                    <br>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </a>
            </li>
        @endforeach
    @else
        <li>
            <span class="dropdown-item-text text-muted">No notifications</span>
        </li>
    @endif
</ul>
