<?php

namespace App\Http\Middleware;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
        $menu = Cache::remember('menu', now()->addDay(), function () {
            return Menu::all();
        });

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'roles' => $request->user() ? Cache::remember('roles_' . $request->user()->id, now()->addHour(), fn () => $request->user()->getRoleNames()) : [],
            ],
            'menu' => $menu,
            'flash' => [
                'error' => fn () => $request->session()->get('error') ?? false,
                'message' => fn () => $request->session()->get('message'),
            ],
        ];
    }
}
