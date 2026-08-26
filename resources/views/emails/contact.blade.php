@component('mail::message')
# New website enquiry

A visitor submitted the contact form on **{{ config('app.name') }}**.

| | |
|-|-|
| **Name** | {{ $data['name'] }} |
| **Email** | [{{ $data['email'] }}](mailto:{{ $data['email'] }}) |
| **Phone** | {{ $data['phone'] ?? '-' }} |

## Message

{{ $data['message'] }}

---
*This message was sent from the public website contact form. It is not medical advice or an appointment request.*
@endcomponent
