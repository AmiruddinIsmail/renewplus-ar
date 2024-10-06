<?php

namespace App\Views;

use App\Models\User;

class Menu
{
    public function __construct(public User $user)
    {

    }

    public function render()
    {
        $menu = [
            [
                'label' => 'Dashboard',
                'route' => route('dashboard'),
                'url' => '/dashboard',
                'icon' => 'Home',
                'badge' => null,
            ],
            [
                'label' => 'Customers',
                'route' => route('customers.index'),
                'url' => '/customers',
                'icon' => 'Users',
                'badge' => null,
            ],
            [
                'label' => 'Invoices',
                'route' => route('invoices.index'),
                'url' => '/invoices',
                'icon' => 'Users',
                'badge' => null,
            ],
        ];
        if($this->user->hasRole('admin')){
            $menu [] =[
                'label' => 'Users',
                'route' => route('users.index'),
                'url' => '/users',
                'icon' => 'Users',
                'badge' => 5,
            ];
        }

        return $menu;
    }
}
