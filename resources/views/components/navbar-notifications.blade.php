@php
    $notifications = auth()->user()->unreadNotifications()->take(10)->get();
@endphp

<div class="relative">

    <button id="notifToggle">
        <i class="fa fa-bell text-xl"></i>

        @if ($notifications->count() > 0)
            <span class="absolute top-0 right-0 bg-red-500 text-white rounded-full text-xs px-1">
                {{ $notifications->count() }}
            </span>
        @endif
    </button>

    <div id="notifMenu" class="hidden absolute right-0 mt-2 w-72 bg-white shadow-xl rounded-lg p-2">

        @forelse ($notifications as $note)
            <div class="p-3 border-b hover:bg-gray-100 cursor-pointer">
                <p class="text-sm font-medium">{{ $note->data['message'] }}</p>
                <p class="text-xs text-gray-500">
                    {{ $note->created_at->diffForHumans() }}
                </p>
            </div>
        @empty
            <p class="p-3 text-center text-sm text-gray-500">No new notifications</p>
        @endforelse
    </div>
</div>

<script>
    document.getElementById('notifToggle').addEventListener('click', () => {
        document.getElementById('notifMenu').classList.toggle('hidden');
    });
</script>
