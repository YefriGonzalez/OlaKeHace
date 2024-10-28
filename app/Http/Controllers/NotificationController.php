<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //

    public function getUnreadNotifications(Request $request){
        $unreadNotifications=User::find($request->user()->id)->unreadNotifications()->get();
        return response()->json($unreadNotifications);
    }
}
