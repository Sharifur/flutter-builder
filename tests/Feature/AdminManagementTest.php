<?php

use App\Models\Admin;

beforeEach(function () {
    $this->admin = Admin::factory()->create([
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
        'is_active' => true,
        'role' => 'admin'
    ]);
    
    $this->actingAs($this->admin, 'admin');
});

describe('Admin Management', function () {
    
    test('admin can view admins index', function () {
        $admins = Admin::factory()->count(3)->create();
        
        $response = $this->get(route('admin.admins.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.admins.index');
        $response->assertSee('Admin Management');
    });

    test('admin can create a new admin via API', function () {
        $adminData = [
            'name' => 'New Admin',
            'email' => 'newadmin@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'manager',
            'is_active' => true
        ];

        $response = $this->postJson(route('admin.admins.store'), $adminData);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Admin created successfully.'
        ]);

        $this->assertDatabaseHas('admins', [
            'name' => 'New Admin',
            'email' => 'newadmin@test.com',
            'role' => 'manager',
            'is_active' => true
        ]);
    });

    test('admin creation validates required fields', function () {
        $response = $this->postJson(route('admin.admins.store'), []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'password', 'role']);
    });

    test('admin creation validates unique email', function () {
        Admin::factory()->create(['email' => 'existing@test.com']);

        $adminData = [
            'name' => 'New Admin',
            'email' => 'existing@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin'
        ];

        $response = $this->postJson(route('admin.admins.store'), $adminData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    });

    test('admin can view another admin details', function () {
        $targetAdmin = Admin::factory()->create();

        $response = $this->getJson(route('admin.admins.show', $targetAdmin));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'admin' => ['id', 'name', 'email', 'role', 'is_active'],
            'stats' => ['pages_created', 'pages_updated']
        ]);
    });

    test('admin can update another admin', function () {
        $targetAdmin = Admin::factory()->create();

        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@test.com',
            'role' => 'editor',
            'is_active' => false
        ];

        $response = $this->putJson(route('admin.admins.update', $targetAdmin), $updateData);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Admin updated successfully.'
        ]);

        $targetAdmin->refresh();
        $this->assertEquals('Updated Name', $targetAdmin->name);
        $this->assertEquals('updated@test.com', $targetAdmin->email);
        $this->assertEquals('editor', $targetAdmin->role);
        $this->assertFalse($targetAdmin->is_active);
    });

    test('admin cannot delete themselves', function () {
        $response = $this->deleteJson(route('admin.admins.destroy', $this->admin));

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'You cannot delete your own account.'
        ]);

        $this->assertDatabaseHas('admins', ['id' => $this->admin->id]);
    });

    test('admin can delete another admin without pages', function () {
        $targetAdmin = Admin::factory()->create();

        $response = $this->deleteJson(route('admin.admins.destroy', $targetAdmin));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Admin deleted successfully.'
        ]);

        $this->assertDatabaseMissing('admins', ['id' => $targetAdmin->id]);
    });

    test('admin cannot delete admin with created pages', function () {
        $targetAdmin = Admin::factory()->create();
        $targetAdmin->createdPages()->create([
            'title' => 'Test Page',
            'slug' => 'test-page',
            'content' => 'Content',
            'status' => 'published',
            'updated_by' => $targetAdmin->id
        ]);

        $response = $this->deleteJson(route('admin.admins.destroy', $targetAdmin));

        $response->assertStatus(422);
        $response->assertJsonFragment(['success' => false]);
        $response->assertJsonFragment(['Cannot delete admin who has created']);
    });

    test('admin can change another admin password', function () {
        $targetAdmin = Admin::factory()->create();

        $passwordData = [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ];

        $response = $this->postJson(route('admin.admins.change-password', $targetAdmin), $passwordData);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Password changed successfully.'
        ]);

        $targetAdmin->refresh();
        $this->assertTrue(Hash::check('newpassword123', $targetAdmin->password));
    });

    test('admin can change own password with current password', function () {
        $passwordData = [
            'current_password' => 'password',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ];

        $response = $this->postJson(route('admin.admins.change-password', $this->admin), $passwordData);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Password changed successfully.'
        ]);
    });

    test('admin cannot change own password with wrong current password', function () {
        $passwordData = [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ];

        $response = $this->postJson(route('admin.admins.change-password', $this->admin), $passwordData);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Current password is incorrect.'
        ]);
    });

    test('admin can update own profile', function () {
        $profileData = [
            'name' => 'Updated Profile Name',
            'email' => 'updated.profile@test.com'
        ];

        $response = $this->postJson(route('admin.profile.update'), $profileData);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Profile updated successfully.'
        ]);

        $this->admin->refresh();
        $this->assertEquals('Updated Profile Name', $this->admin->name);
        $this->assertEquals('updated.profile@test.com', $this->admin->email);
    });

    test('admin index supports search and filtering', function () {
        Admin::factory()->create(['name' => 'John Manager', 'role' => 'manager']);
        Admin::factory()->create(['name' => 'Jane Editor', 'role' => 'editor']);
        Admin::factory()->create(['name' => 'Bob Admin', 'is_active' => false]);

        // Test search
        $response = $this->get(route('admin.admins.index', ['search' => 'John']));
        $response->assertStatus(200);
        $response->assertSee('John Manager');

        // Test role filter
        $response = $this->get(route('admin.admins.index', ['role' => 'manager']));
        $response->assertStatus(200);
        $response->assertSee('John Manager');

        // Test status filter
        $response = $this->get(route('admin.admins.index', ['status' => 'inactive']));
        $response->assertStatus(200);
        $response->assertSee('Bob Admin');
    });
});

describe('Admin Access Control', function () {
    
    test('unauthenticated users cannot access admin management routes', function () {
        auth('admin')->logout();
        
        $response = $this->get(route('admin.admins.index'));
        $response->assertRedirect(route('admin.login'));
    });

    test('inactive admin cannot access admin management routes', function () {
        $this->admin->update(['is_active' => false]);
        
        $response = $this->get(route('admin.admins.index'));
        $response->assertRedirect(route('admin.login'));
    });
});