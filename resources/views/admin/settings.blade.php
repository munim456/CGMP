@extends('layouts.admin')

@section('page-title', 'Site settings')

@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="admin-settings-layout">
    @csrf @method('PUT')

    <section class="admin-panel">
        <h2>Identity</h2>
        <div class="field">
            <label for="clinic_name">Clinic name <span class="req">*</span></label>
            <input type="text" id="clinic_name" name="clinic_name" value="{{ old('clinic_name', $values['clinic_name']) }}" required>
        </div>
        <div class="field">
            <label for="tagline">Tagline</label>
            <input type="text" id="tagline" name="tagline" value="{{ old('tagline', $values['tagline']) }}" maxlength="200">
        </div>
        <div class="field">
            <label for="palette">Colour theme</label>
<select id="palette" name="palette" data-palette-preview>
<option value="" @selected(old('palette', $values['palette'] ?? '') === '')>Clinical Blue (default)</option>
<option value="teal" @selected(old('palette', $values['palette'] ?? '') === 'teal')>Teal Trust</option>
<option value="ocean" @selected(old('palette', $values['palette'] ?? '') === 'ocean')>Ocean Blue</option>
<option value="green" @selected(old('palette', $values['palette'] ?? '') === 'green')>Warm Green</option>
            </select>
            <p class="help">Changes the whole website colour scheme.</p>
        </div>

        <h3 class="mt-4">Logo & favicon</h3>
        @if(!empty($values['logo_path']))
            <img src="{{ image_url($values['logo_path']) }}" alt="Current logo" class="img-preview img-preview--logo">
        @endif
        <div class="field mt-1">
            <input type="file" id="logo_file" name="logo_file" accept=".jpg,.jpeg,.png,.webp,.svg">
        </div>
        <div class="field mt-2">
            <label for="favicon_file">Favicon (small browser-tab icon)</label>
            <input type="file" id="favicon_file" name="favicon_file" accept=".png,.ico,.svg,.jpg,.jpeg,.webp">
        </div>

        <button type="submit" class="btn btn--primary btn--lg mt-4"><x-icon name="save"/> Save all settings</button>
    </section>

    <div class="admin-settings-grid">
        <section class="admin-panel">
            <h2>Contact details & hours</h2>
            <div class="form-row">
                <div class="field"><label for="phone">Phone</label><input type="text" id="phone" name="phone" value="{{ old('phone', $values['phone']) }}"></div>
                <div class="field"><label for="fax">Fax</label><input type="text" id="fax" name="fax" value="{{ old('fax', $values['fax']) }}"></div>
            </div>
            <div class="field"><label for="contact_email">Contact email</label><input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email', $values['contact_email']) }}"></div>
            <div class="field"><label for="address_line1">Street address</label><input type="text" id="address_line1" name="address_line1" value="{{ old('address_line1', $values['address_line1']) }}"></div>
            <div class="field"><label for="address_suburb">Suburb / state / postcode</label><input type="text" id="address_suburb" name="address_suburb" value="{{ old('address_suburb', $values['address_suburb']) }}"></div>
            <div class="field">
                <label for="opening_hours">Opening hours (one line per row)</label>
                <textarea id="opening_hours" name="opening_hours" rows="5">{{ old('opening_hours', $values['opening_hours']) }}</textarea>
            </div>
            <div class="field">
                <label for="google_map_embed">Google Map embed URL</label>
                <textarea id="google_map_embed" name="google_map_embed" rows="2">{{ old('google_map_embed', $values['google_map_embed']) }}</textarea>
                <p class="help">In Google Maps: Share → Embed a map → copy only the src link inside the code.</p>
            </div>
            <div class="field">
                <label for="emergency_note">Emergency note</label>
                <textarea id="emergency_note" name="emergency_note" rows="2" maxlength="300">{{ old('emergency_note', $values['emergency_note']) }}</textarea>
            </div>
        </section>

        <section class="admin-panel">
            <h2>Online booking (HealthEngine)</h2>
            <div class="field">
                <label for="healthengine_url">HealthEngine booking page URL</label>
                <input type="url" id="healthengine_url" name="healthengine_url" value="{{ old('healthengine_url', $values['healthengine_url']) }}" placeholder="https://healthengine.com.au/...">
                <p class="help">Used by every "Book Appointment" button. Ask HealthEngine support if unsure.</p>
            </div>
            <div class="field">
                <label for="healthengine_embed">Or paste embed/widget code (optional)</label>
                <textarea id="healthengine_embed" name="healthengine_embed" rows="4">{{ old('healthengine_embed', $values['healthengine_embed']) }}</textarea>
                <p class="help">If provided, this widget shows directly inside the booking page instead of a button.</p>
            </div>
            <div class="field">
                <label for="walk_in_note">Walk-in information</label>
                <textarea id="walk_in_note" name="walk_in_note" rows="2" maxlength="400">{{ old('walk_in_note', $values['walk_in_note']) }}</textarea>
            </div>
        </section>

        <section class="admin-panel">
            <h2>Social links</h2>
            <div class="field"><label for="facebook_url">Facebook</label><input type="url" id="facebook_url" name="facebook_url" value="{{ old('facebook_url', $values['facebook_url']) }}"></div>
            <div class="field"><label for="instagram_url">Instagram</label><input type="url" id="instagram_url" name="instagram_url" value="{{ old('instagram_url', $values['instagram_url']) }}"></div>
            <div class="field"><label for="youtube_url">YouTube</label><input type="url" id="youtube_url" name="youtube_url" value="{{ old('youtube_url', $values['youtube_url']) }}"></div>
        </section>

        <section class="admin-panel">
            <h2>SEO & analytics</h2>
            <div class="field">
                <label for="meta_title_template">Default title format</label>
                <input type="text" id="meta_title_template" name="meta_title_template" value="{{ old('meta_title_template', $values['meta_title_template'] ?? ':title | :site') }}">
                <p class="help">:title becomes the page name; :site becomes your clinic name.</p>
            </div>
            <div class="field">
                <label for="meta_description_default">Default description</label>
                <textarea id="meta_description_default" name="meta_description_default" rows="3" maxlength="400">{{ old('meta_description_default', $values['meta_description_default']) }}</textarea>
            </div>
            <div class="field">
                <label for="og_image_file">Social share image (Open Graph)</label>
                @if(!empty($values['og_image_path']))
                    <img src="{{ image_url($values['og_image_path']) }}" alt="" class="img-preview img-preview--wide">
                @endif
                <input type="file" id="og_image_file" name="og_image_file" accept=".jpg,.jpeg,.png,.webp" class="mt-1">
            </div>
            <div class="field">
                <label for="analytics_code">Analytics tracking code</label>
                <textarea id="analytics_code" name="analytics_code" rows="4">{{ old('analytics_code', $values['analytics_code']) }}</textarea>
                <p class="help">Paste the full Google Analytics or similar snippet here.</p>
            </div>
        </section>

        <section class="admin-panel admin-panel--full">
            <h2>Footer & legal text</h2>
            <div class="form-row">
                <div class="field">
                    <label for="footer_text">Footer description</label>
                    <textarea id="footer_text" name="footer_text" rows="3" maxlength="600">{{ old('footer_text', $values['footer_text']) }}</textarea>
                </div>
                <div class="field">
                    <label for="copyright_text">Copyright line</label>
                    <input type="text" id="copyright_text" name="copyright_text" value="{{ old('copyright_text', $values['copyright_text']) }}" maxlength="300">
                </div>
            </div>
            <div class="field">
                <label for="contact_form_disclaimer">Contact form disclaimer</label>
                <textarea id="contact_form_disclaimer" name="contact_form_disclaimer" rows="2" maxlength="600">{{ old('contact_form_disclaimer', $values['contact_form_disclaimer']) }}</textarea>
                <p class="help">Must make clear the form is not for medical advice or emergencies.</p>
            </div>
            <button type="submit" class="btn btn--primary btn--lg mt-2"><x-icon name="save"/> Save all settings</button>
        </section>
    </div>
</form>
@endsection
