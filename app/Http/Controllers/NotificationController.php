<?php

namespace App\Http\Controllers;

use App\Notifications\CustomEmailNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function create(): View
    {
        $title = "Send Notification";
        $sub_title = "Send a custom email notification";

        return view('backend.notifications.create', compact('title', 'sub_title'));
    }

    public function send(Request $request): RedirectResponse
    {
        $request->merge([
            'recipients' => array_map('trim', explode(',', $request->recipients))
        ]);

        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'recipients' => 'required|array',
            'recipients.*' => 'email',
        ]);

        foreach ($request->recipients as $email) {
            Notification::route('mail', $email)
                ->notify(new CustomEmailNotification($request->subject, $request->message));
        }

        return Redirect::back()->with('success', 'Emails sent successfully!');
    }
}
