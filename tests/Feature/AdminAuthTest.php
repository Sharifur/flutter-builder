<?php

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->admin = Admin::factory()->create([
        'email' => 'admin@test.com',
        'password' => Hash::make('password'),
        'is_active' => true,
    ]);
});

describe('Admin Authentication', function () {
    test('admin can view login form', function () {
        $response = $this->get(route('admin.login'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.auth.login');
        $response->assertSee('Admin Sign In');
    });

    test('admin can login with valid credentials', function () {
        $response = $this->post(route('admin.login'), [
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->admin, 'admin');
    });

    test('admin cannot login with invalid credentials', function () {
        $response = $this->post(route('admin.login'), [
            'email' => 'admin@test.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('admin');
    });

    test('inactive admin cannot login', function () {
        $inactiveAdmin = Admin::factory()->create([
            'email' => 'inactive@test.com',
            'password' => Hash::make('password'),
            'is_active' => false,
        ]);

        $response = $this->post(route('admin.login'), [
            'email' => 'inactive@test.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('admin');
    });

    test('admin can logout', function () {
        $this->actingAs($this->admin, 'admin');

        $response = $this->post(route('admin.logout'));

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest('admin');
    });

    test('logged in admin redirected from login page', function () {
        $this->actingAs($this->admin, 'admin');

        $response = $this->get(route('admin.login'));

        $response->assertRedirect(route('admin.dashboard'));
    });

    test('guest cannot access admin dashboard', function () {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('admin.login'));
    });
});