<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Support\MediaUploader;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(): View
    {
        return view('admin.doctors.index', [
            'doctors' => Doctor::query()->orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return $this->form(new Doctor());
    }

    public function store(Request $request): RedirectResponse
    {
        $doctor = new Doctor();
        $this->save($doctor, $request);

        return redirect()->route('admin.doctors.edit', $doctor)->with('status', __('Doctor profile created.'));
    }

    public function edit(Doctor $doctor): View
    {
        return $this->form($doctor);
    }

    public function update(Request $request, Doctor $doctor): RedirectResponse
    {
        $this->save($doctor, $request);

        return redirect()->route('admin.doctors.edit', $doctor)->with('status', __('Doctor profile updated.'));
    }

    public function destroy(Doctor $doctor): RedirectResponse
    {
        MediaUploader::delete($doctor->photo);
        $doctor->delete();

        return redirect()->route('admin.doctors.index')->with('status', __('Doctor profile deleted.'));
    }

    protected function form(Doctor $doctor): View
    {
        return view('admin.doctors.form', ['doctor' => $doctor]);
    }

    protected function save(Doctor $doctor, Request $request): void
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'role' => ['nullable', 'string', 'max:150'],
            'qualifications' => ['nullable', 'string', 'max:300'],
            'special_interests' => ['nullable', 'string', 'max:300'],
            'bio' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
            'photo' => ['nullable', 'image', 'mimes:' . MediaUploader::ALLOWED_MIMES, 'max:4096'],
            'remove_photo' => ['nullable', 'boolean'],
        ]);

        $data = [
            'name' => $validated['name'],
            'role' => $validated['role'] ?? null,
            'qualifications' => $validated['qualifications'] ?? null,
            'special_interests' => $validated['special_interests'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->boolean('remove_photo')) {
            MediaUploader::delete($doctor->photo);
            $data['photo'] = null;
        } elseif ($request->hasFile('photo')) {
            MediaUploader::delete($doctor->photo);
            $data['photo'] = MediaUploader::handle($request->file('photo'), 'media/doctors')['path'];
        }

        $doctor->fill($data)->save();
    }
}
