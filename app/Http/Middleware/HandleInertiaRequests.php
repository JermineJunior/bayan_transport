<?php

namespace App\Http\Middleware;

use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        $translations = include lang_path('ar/admin.php');

        $generalSettings = cache()->rememberForever('general_settings', function () {
            return GeneralSetting::first();
        }) ?? new GeneralSetting([
            'name' => 'أميركو العالمية المحدودة',
            'location' => null,
            'phone' => null,
            'phone_2' => null,
            'email' => null,
            'logo' => null,
            'note' => null,
        ]);

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'generalSettings' => [
                'name' => $generalSettings->name,
                'location' => $generalSettings->location,
                'phone' => $generalSettings->phone,
                'phone_2' => $generalSettings->phone_2,
                'email' => $generalSettings->email,
                'logo' => $generalSettings->logo,
                'note' => $generalSettings->note,
            ],
            'auth' => [
                'user' => $user,
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'translations' => $translations,
        ];
    }
}
