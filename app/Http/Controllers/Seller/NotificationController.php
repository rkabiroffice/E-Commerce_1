<?php

namespace App\Http\Controllers\Seller;

use App\Models\User;
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

        return view('seller.notification.index', compact('notifications'));
    }
}
