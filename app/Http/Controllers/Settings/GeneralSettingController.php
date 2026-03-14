<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GeneralSettingController extends Controller
{
    public function index()
    {
        $settings = GeneralSetting::query()->firstOrCreate(['id' => 1], [
            'name' => 'السيدابي مبارك عمر السيدابي للترحيلات',
            'location' => null,
            'phone' => '0912711162',
            'phone_2' => null,
            'email' => null,
            'logo' => null,
            'note' => null,
        ]);

        return inertia('settings/General', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'phone_2' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'note' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,svg', 'max:2048'],
        ]);

        $settings = GeneralSetting::query()->firstOrCreate(['id' => 1], [
            'name' => 'السيدابي مبارك عمر السيدابي للترحيلات',
        ]);

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time().'.'.$logo->getClientOriginalExtension();
            $logo->move(public_path('image/logo'), $logoName);
            $validated['logo'] = $logoName;
        } else {
            unset($validated['logo']);
        }

        $settings->update($validated);

        Cache::forget('general_settings');

        return back()->with('success', 'تم تحديث الإعدادات بنجاح');
    }
}
