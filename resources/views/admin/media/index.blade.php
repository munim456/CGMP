@extends('layouts.admin')

@section('page-title', 'Media library')

@section('content')
<form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="admin-panel upload-panel">
    @csrf
    <div class="upload-row">
        <input type="file" name="files[]" id="media-files" multiple accept=".jpg,.jpeg,.png,.webp,.gif,.svg" required>
        <input type="text" name="alt_text" placeholder="Describe these images (alt text)">
        <button type="submit" class="btn btn--primary"><x-icon name="upload" class="w-4 h-4"/> Upload</button>
    </div>
    <p class="help">JPG, PNG, WebP, GIF or SVG · up to 6 MB each · large photos are resized automatically.</p>
</form>

@if($media->isNotEmpty())
<div class="media-grid">
    @foreach($media as $item)
        <figure class="media-card">
            <img src="{{ image_url($item->path) }}" alt="{{ $item->alt_text }}" loading="lazy">
            <figcaption>
                <p title="{{ $item->filename }}">{{ \Illuminate\Support\Str::limit($item->filename, 26) }}</p>
                <form method="POST" action="{{ route('admin.media.update', $item) }}" id="save-alt-{{ $item->id }}">
                    @csrf @method('PUT')
                    <input type="text" name="alt_text" value="{{ $item->alt_text }}" placeholder="Alt text" aria-label="Alt text for {{ $item->filename }}">
                </form>
                <div class="media-card__actions">
                    <button type="submit" form="save-alt-{{ $item->id }}" class="icon-btn" title="Save alt text"><x-icon name="save"/></button>
                    <form id="delete-media-{{ $item->id }}" method="POST" action="{{ route('admin.media.destroy', $item) }}" data-confirm="Remove this image from the library?">
                        @csrf @method('DELETE')
                    </form>
                    <button type="submit" form="delete-media-{{ $item->id }}" class="icon-btn icon-btn--danger" title="Delete"><x-icon name="trash"/></button>
                </div>
            </figcaption>
        </figure>
    @endforeach
</div>
{{ $media->links() }}
@else
<p class="muted p-4">No images uploaded yet.</p>
@endif
@endsection
