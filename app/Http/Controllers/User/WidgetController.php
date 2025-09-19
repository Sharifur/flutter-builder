<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AppPage;
use App\Models\Widget;
use App\Models\UiComponent;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class WidgetController extends Controller
{
    use AuthorizesRequests;
    public function store(Request $request, AppPage $page)
    {
        $this->authorize('update', $page->project);

        // Get available component types from the database
        $availableTypes = UiComponent::active()->pluck('component_type')->toArray();

        $request->validate([
            'type' => 'required|string|in:' . implode(',', $availableTypes),
            'config' => 'nullable|array',
            'order' => 'nullable|integer|min:0',
        ]);

        // Check for duplicate authentication forms
        $authFormTypes = ['LoginForm', 'RegisterForm', 'UnifiedForm'];
        if (in_array($request->type, $authFormTypes)) {
            $existingAuthForm = $page->widgets()->whereIn('type', $authFormTypes)->first();
            if ($existingAuthForm) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only one authentication form allowed per page. Remove the existing ' . $existingAuthForm->type . ' first.'
                ], 400);
            }
        }

        // Get component from database
        $uiComponent = UiComponent::where('component_type', $request->type)
            ->where('is_active', true)
            ->first();

        if (!$uiComponent) {
            return response()->json([
                'success' => false,
                'message' => 'Component type not found or inactive'
            ], 400);
        }

        // Use component's default config or provided config
        $config = $request->config ?: $uiComponent->default_config;
        $order = $request->order ?? ($page->widgets()->max('order') + 1);

        // Validate config using the component
        $componentInstance = $uiComponent->getComponentInstance();
        if ($componentInstance && !$componentInstance->validate($config)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid configuration for this component type'
            ], 400);
        }

        $widget = $page->widgets()->create([
            'type' => $request->type,
            'config' => $config,
            'order' => $order,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'widget' => $widget,
                'message' => 'Widget added successfully!'
            ]);
        }

        return back()->with('success', 'Widget added successfully!');
    }

    public function update(Request $request, Widget $widget)
    {
        $this->authorize('update', $widget->appPage->project);

        $request->validate([
            'config' => 'required|array',
            'order' => 'nullable|integer|min:0',
        ]);

        $widget->update([
            'config' => $request->config,
            'order' => $request->order ?? $widget->order,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'widget' => $widget,
                'message' => 'Widget updated successfully!'
            ]);
        }

        return back()->with('success', 'Widget updated successfully!');
    }

    public function destroy(Widget $widget)
    {
        $this->authorize('update', $widget->appPage->project);

        $widget->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Widget deleted successfully!'
            ]);
        }

        return back()->with('success', 'Widget deleted successfully!');
    }

    public function edit(Widget $widget)
    {
        $this->authorize('update', $widget->appPage->project);

        if (request()->expectsJson()) {
            return response()->json($widget);
        }

        return response()->json($widget);
    }

    public function updateDirect(Request $request, Widget $widget)
    {
        $this->authorize('update', $widget->appPage->project);

        $request->validate([
            'config' => 'required|array',
        ]);

        $widget->update([
            'config' => $request->config,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'widget' => $widget,
                'message' => 'Widget updated successfully!'
            ]);
        }

        return back()->with('success', 'Widget updated successfully!');
    }

    public function destroyDirect(Widget $widget)
    {
        // This is the same as destroy but directly accessible for builder interface
        return $this->destroy($widget);
    }

    public function reorder(Request $request, AppPage $page)
    {
        $this->authorize('update', $page->project);

        $request->validate([
            'widgets' => 'required|array',
            'widgets.*.id' => 'required|exists:widgets,id',
            'widgets.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->widgets as $widgetData) {
            Widget::where('id', $widgetData['id'])
                ->where('app_page_id', $page->id)
                ->update(['order' => $widgetData['order']]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Widget order updated successfully!'
            ]);
        }

        return back()->with('success', 'Widget order updated successfully!');
    }
}