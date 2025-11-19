<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NavbarNotifications extends Component
{
    public $notifications = [];
    public $showMenu = false;
    public $userId;

    protected $listeners = [
        'refreshNotifications' => 'loadNotifications',
        // 'echo-private:notifications.{userId},AppNotificationEvent' => 'handleNewNotification'
        'echo-private:notifications.{userId},AppNotificationEvent' => 'handleNewNotification'
    ];

    public function handleNewNotification($payload)
    {
        $this->loadNotifications();
    }
    public function mount()
    {
        $this->userId = auth()->id();
        $this->loadNotifications();
    }

    public function toggleMenu()
    {
        $this->showMenu = !$this->showMenu;
    }

    public function loadNotifications()
    {
        // Load unread notifications
        $this->notifications = auth()->user()
            ->unreadNotifications()
            ->latest()
            ->take(10)
            ->get(); // Keep as collection for easy access to 'data'
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.navbar-notifications');
    }
}
