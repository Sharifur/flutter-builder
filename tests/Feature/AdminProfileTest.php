<?php

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = Admin::factory()->create([
        'name' => 'Test Admin',
        'email' => 'admin@test.com',
        'is_active' => true,
    ]);
});

test('admin can view edit profile page', function () {
    $response = $this->actingAs($this->admin, 'admin')
        ->get(route('admin.profile.edit'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.profile.edit');
    $response->assertSee('Edit Profile');
    $response->assertSee($this->admin->name);
    $response->assertSee($this->admin->email);
});

test('admin can view change password page', function () {
    $response = $this->actingAs($this->admin, 'admin')
        ->get(route('admin.profile.change-password'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.profile.change-password');
    $response->assertSee('Change Password');
    $response->assertSee('Current Password');
});

test('admin can update their profile', function () {
    $response = $this->actingAs($this->admin, 'admin')
        ->post(route('admin.profile.update'), [
            'name' => 'Updated Admin Name',
            'email' => 'updated@test.com',
        ]);

    $response->assertStatus(302);
    $response->assertRedirect(route('admin.profile.edit'));
    $response->assertSessionHas('success', 'Profile updated successfully.');

    $this->admin->refresh();
    expect($this->admin->name)->toBe('Updated Admin Name');
    expect($this->admin->email)->toBe('updated@test.com');
});

test('admin can change their password', function () {
    $response = $this->actingAs($this->admin, 'admin')
        ->post(route('admin.profile.update-password'), [
            'current_password' => 'password',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

    $response->assertStatus(302);
    $response->assertRedirect(route('admin.profile.change-password'));
    $response->assertSessionHas('success', 'Password changed successfully.');

    $this->admin->refresh();
    expect(\Hash::check('newpassword123', $this->admin->password))->toBeTrue();
});

test('admin profile update validates required fields', function () {
    $response = $this->actingAs($this->admin, 'admin')
        ->post(route('admin.profile.update'), [
            'name' => '',
            'email' => '',
        ]);

    $response->assertStatus(302);
    $response->assertSessionHasErrors(['name', 'email']);
});

test('admin password change validates current password', function () {
    $response = $this->actingAs($this->admin, 'admin')
        ->post(route('admin.profile.update-password'), [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

    $response->assertStatus(302);
    $response->assertSessionHasErrors(['current_password']);
});

test('guest cannot access profile pages', function () {
    $response = $this->get(route('admin.profile.edit'));
    $response->assertStatus(302);
    $response->assertRedirect(route('admin.login'));

    $response = $this->get(route('admin.profile.change-password'));
    $response->assertStatus(302);
    $response->assertRedirect(route('admin.login'));
});