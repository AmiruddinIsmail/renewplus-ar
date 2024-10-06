<?php

namespace App\Http\Middleware;

use App\Views\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Middleware;
use Spatie\Permission\Models\Role;
use Tighten\Ziggy\Ziggy;

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
    public function version(Request $request): string|null
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
        $response = [
            ...parent::share($request),
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
        ];

        if($request->user() === null){
            return $response;
        }

        $response['auth']['user'] = $request->user()->load('roles:id,name');
        $response['roles'] = Cache::remember('roles',(60*60*24), fn()=> Role::all());
        $response['menu'] = (new Menu($request->user()))->render();

        return $response;
    }
}
