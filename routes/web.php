<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\User\AuthController as UserAuthController;
use App\Http\Controllers\User\ProjectController as UserProjectController;
use App\Http\Controllers\User\AppPageController as UserAppPageController;
use App\Http\Controllers\User\WidgetController as UserWidgetController;
use App\Http\Controllers\User\PreviewController;
use App\Http\Controllers\User\DataCollectionController;
use App\Models\Project;

Route::get('/', function () {
    return view('home');
});

// Frontend Routes
Route::get('/page/{page}', [PageController::class, 'show'])->name('page.show');

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Protected Admin Routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('pages', AdminPageController::class);
        Route::post('/pages/analyze-seo', [AdminPageController::class, 'analyzeSEO'])->name('pages.analyze-seo');
        
        // Admin Management
        Route::resource('admins', AdminController::class);
        Route::post('/admins/{admin}/change-password', [AdminController::class, 'changePassword'])->name('admins.change-password');
        
        // Profile Management for Current Admin
        Route::get('/profile/edit', [AdminController::class, 'editProfile'])->name('profile.edit');
        Route::post('/profile/update', [AdminController::class, 'updateProfile'])->name('profile.update');
        Route::get('/profile/change-password', [AdminController::class, 'showChangePassword'])->name('profile.change-password');
        Route::post('/profile/change-password', [AdminController::class, 'updatePassword'])->name('profile.update-password');
        
        // User Management
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/change-password', [UserController::class, 'changePassword'])->name('users.change-password');
    });
});

// User Panel Routes
Route::prefix('user')->name('user.')->group(function () {
    Route::get('/login', [UserAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::get('/register', [UserAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [UserAuthController::class, 'register']);
    Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');

    // Protected User Routes
    Route::middleware(['user'])->group(function () {
        Route::get('/dashboard', function () {
            $user = auth()->user();
            $projects = $user->projects()->with('appPages.widgets')->get();
            $totalProjects = $projects->count();
            $totalPages = $projects->sum(fn($project) => $project->appPages->count());
            $totalWidgets = $projects->sum(fn($project) => $project->appPages->sum(fn($page) => $page->widgets->count()));

            return view('user.dashboard', compact('totalProjects', 'totalPages', 'totalWidgets', 'projects'));
        })->name('dashboard');

        // Project Management
        Route::resource('projects', UserProjectController::class);
        Route::get('/projects/{project}/builder', [UserProjectController::class, 'builder'])->name('projects.builder');

        // Preview Routes
        Route::get('/projects/{project}/preview', [PreviewController::class, 'show'])->name('projects.preview');
        Route::get('/projects/{project}/preview/iframe', [PreviewController::class, 'iframe'])->name('projects.preview.iframe');
        Route::get('/projects/{project}/preview/{path}', [PreviewController::class, 'assets'])->name('projects.preview.assets')->where('path', '.*');

        // Page Management
        Route::resource('projects.pages', UserAppPageController::class);

        // Data Collection Management - React Interface
        Route::get('/projects/{project}/data-collections', [DataCollectionController::class, 'react'])->name('projects.data-collections.react');
        Route::get('/projects/{project}/data-collections-mapping', [DataCollectionController::class, 'getCollectionsForMapping'])->name('projects.data-collections.mapping');
        Route::get('/projects/{project}/data-collections/{dataCollection}/user-data', [DataCollectionController::class, 'getUserData'])->name('projects.data-collections.user-data');
        Route::get('/projects/{project}/related-collections', [DataCollectionController::class, 'getRelatedCollections'])->name('projects.related-collections');
        Route::get('/projects/{project}/data-collections-index', [DataCollectionController::class, 'index'])->name('projects.data-collections.index');
        Route::resource('projects.data-collections', DataCollectionController::class)->except(['index']);
        Route::post('/projects/{project}/data-collections/{dataCollection}/fields', [DataCollectionController::class, 'addField'])->name('projects.data-collections.fields.store');
        Route::put('/projects/{project}/data-collections/{dataCollection}/fields/{field}', [DataCollectionController::class, 'updateField'])->name('projects.data-collections.fields.update');
        Route::delete('/projects/{project}/data-collections/{dataCollection}/fields/{field}', [DataCollectionController::class, 'deleteField'])->name('projects.data-collections.fields.destroy');
        Route::get('/projects/{project}/data-collections/{dataCollection}/records', [DataCollectionController::class, 'records'])->name('projects.data-collections.records');
        Route::get('/projects/{project}/data-collections/{dataCollection}/api', [DataCollectionController::class, 'apiInfo'])->name('projects.data-collections.api');

        // Widget Management
        Route::resource('pages.widgets', UserWidgetController::class)->only(['store', 'update', 'destroy']);
        Route::post('/pages/{page}/widgets/reorder', [UserWidgetController::class, 'reorder'])->name('pages.widgets.reorder');

        // Direct widget routes for builder interface
        Route::get('/widgets/{widget}/edit', [UserWidgetController::class, 'edit'])->name('widgets.edit');
        Route::put('/widgets/{widget}', [UserWidgetController::class, 'updateDirect'])->name('widgets.update');
        Route::delete('/widgets/{widget}', [UserWidgetController::class, 'destroyDirect'])->name('widgets.destroy');

        // UI Components API
        Route::prefix('api/ui-components')->name('api.ui-components.')->group(function () {
            Route::get('/', [App\Http\Controllers\User\UiComponentController::class, 'index'])->name('index');
            Route::get('/categories', [App\Http\Controllers\User\UiComponentController::class, 'categories'])->name('categories');
            Route::get('/{uiComponent}', [App\Http\Controllers\User\UiComponentController::class, 'show'])->name('show');
            Route::post('/{uiComponent}/render', [App\Http\Controllers\User\UiComponentController::class, 'render'])->name('render');
            Route::post('/{uiComponent}/validate', [App\Http\Controllers\User\UiComponentController::class, 'validate'])->name('validate');
        });

        // API Routes for React Builder
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/projects/{project}', [UserProjectController::class, 'show'])->name('projects.show');
            Route::get('/projects/{project}/data-collections', [DataCollectionController::class, 'index'])->name('projects.data-collections.index');
            Route::get('/data-collections/{dataCollection}/fields', [DataCollectionController::class, 'getFields'])->name('data-collections.fields');
            Route::post('/pages/{page}/widgets', [UserWidgetController::class, 'store'])->name('pages.widgets.store');
            Route::put('/widgets/{widget}', [UserWidgetController::class, 'updateDirect'])->name('widgets.update');
            Route::delete('/widgets/{widget}', [UserWidgetController::class, 'destroyDirect'])->name('widgets.destroy');
            Route::post('/pages/{page}/widgets/reorder', [UserWidgetController::class, 'reorder'])->name('pages.widgets.reorder');
        });
    });
});
