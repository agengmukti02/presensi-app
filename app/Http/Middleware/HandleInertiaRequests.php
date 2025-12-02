<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    // Email disembunyikan dari inspect element untuk keamanan
                    // 'email' => $user->email,
                    'role' => $user->role,
                    // NIP hanya tampilkan 4 digit terakhir di frontend
                    'nip_masked' => $user->nip ? '****' . substr($user->nip, -4) : null,
                ] : null,
            ],
            'flash' => [
                'success' => $request->session()->get('flash.success'),
                'message' => $request->session()->get('flash.message'),
            ],
            'flash' => [
                'success' => $request->session()->get('flash.success'),
                'message' => $request->session()->get('flash.message'),
            ],
        ];
    }
}
