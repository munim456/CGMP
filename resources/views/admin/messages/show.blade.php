@extends('layouts.admin')

@section('page-title', 'Message from ' . $message->name)

@section('content')
<section class="admin-panel admin-panel--narrow">
    <div class="admin-panel__head">
        <h2>{{ $message->name }}</h2>
        <a href="{{ route('admin.messages.index') }}">← All messages</a>
    </div>

    <dl class="detail-list">
        <div><dt>Received</dt><dd>{{ $message->created_at->format('j F Y, g:ia') }}</dd></div>
        <div><dt>Email</dt><dd><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></dd></div>
        @if($message->phone)<div><dt>Phone</dt><dd><a href="tel:{{ $message->phone }}">{{ $message->phone }}</a></dd></div>@endif
    </dl>

    <div class="prose message-body">{{ $message->message }}</div>

    <div class="mt-4 flex-gap">
        <a href="mailto:{{ $message->email }}?subject=Re: Your enquiry to {{ setting('clinic_name') }}" class="btn btn--primary"><x-icon name="mail" class="w-4 h-4"/> Reply by email</a>
        <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" data-confirm="Delete this message permanently?">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn--danger"><x-icon name="trash" class="w-4 h-4"/> Delete</button>
        </form>
    </div>
    <p class="help mt-2">Reminder: this inbox is for general enquiries only — never share patient information here.</p>
</section>
@endsection
