<div wire:poll.5s="loadNotifications" class="dropdown me-4">
    <a href="#" class="text-decoration-none text-dark position-relative" id="notifDropdown" role="button"
        data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-bell fs-4"></i>

        @if (count($notifications) > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ count($notifications) }}
            </span>
        @endif
    </a>

    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notifDropdown">
        @forelse($notifications as $note)
            <li>
                <a class="dropdown-item" href="#" wire:click.prevent="markAsRead('{{ $note->id }}')">
                    {{ $note->data['message'] }}
                    <br>
                    <small class="text-muted">{{ $note->created_at->diffForHumans() }}</small>
                </a>
            </li>
        @empty
            <li>
                <a class="dropdown-item text-center text-muted" href="#">No new notifications</a>
            </li>
        @endforelse
    </ul>
</div>
