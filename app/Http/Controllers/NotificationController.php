<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return redirect()->route('login');
        }

        $notifications = $user->notifications()->paginate(15);
        $user->unreadNotifications->markAsRead();

        if ($user->user_type == 'admin') {
            return view('backend.notification.index', compact('notifications'));
        }

        if ($user->user_type == 'seller') {
            return view('seller.notification.index', compact('notifications'));
        }

        if ($user->user_type == 'customer') {
            return view('frontend.user.customer.notification.index', compact('notifications'));
        }
    }
}
