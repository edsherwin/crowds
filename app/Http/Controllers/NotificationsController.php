<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class NotificationsController extends Controller
{
    public function index() {
    	$unread = Auth::user()->unreadNotifications;
    	return view('notifications', compact('unread'));
    }
}
