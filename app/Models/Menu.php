<?php

namespace App\Models;

use Illuminate\Support\Collection;

class Menu
{
    public static function all()
    {
        return [
            self::create('Dashboard', route('dashboard'), 'dashboard', 'layout-grid'),
            self::create('Customers', route('customers.index'), 'customers', 'users'),
            self::create('Invoices', route('invoices.index'), 'invoices', 'file-text'),
        ];
    }

    public static function create(string $title, string $link, string $name, string $icon): Collection
    {
        return collect([
            'title' => $title,
            'link' => $link,
            'name' => $name,
            'icon' => $icon,
        ]);
    }
}
