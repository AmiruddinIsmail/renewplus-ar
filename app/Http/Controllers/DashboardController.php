<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Dashboard', [
            'posts' => Inertia::defer(function (): array {
                sleep(2);

                return ['post 1', 'post 2'];
            }),
        ]);
    }
}
