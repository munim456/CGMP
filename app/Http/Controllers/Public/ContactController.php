<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\ContactFormMail;
use App\Models\ContactMessage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('pages.contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:30'],
            'message' => ['required', 'string', 'max:3000'],
            'website' => ['prohibited'],
        ], [
            'website.prohibited' => 'The message could not be sent. If you are human, please try again.',
        ]);

        ContactMessage::create($validated);

        $recipient = config('mail.contact_recipient') ?: setting('contact_email');

        try {
            Mail::to($recipient)->send(new ContactFormMail($validated));
        } catch (\Throwable $e) {
            report($e);
        }

        return back()->with('status', __('Thank you for your enquiry. The practice team will get back to you during opening hours.'));
    }
}
