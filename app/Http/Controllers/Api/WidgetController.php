<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppPage;
use App\Models\Widget;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class WidgetController extends Controller
{
    use AuthorizesRequests;
    public function store(Request $request, AppPage $page)
    {
        $this->authorize('update', $page->project);

        $request->validate([
            'type' => 'required|string|in:Text,Button,Image,Input,Container',
            'config' => 'nullable|array',
            'order' => 'nullable|integer|min:0',
        ]);

        $config = $request->config ?: Widget::getDefaultConfig($request->type);
        $order = $request->order ?? ($page->widgets()->max('order') + 1);

        $widget = $page->widgets()->create([
            'type' => $request->type,
            'config' => $config,
            'order' => $order,
        ]);

        return response()->json([
            'success' => true,
            'data' => $widget,
            'message' => 'Widget added successfully!',
        ], 201);
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

        return response()->json([
            'success' => true,
            'data' => $widget,
            'message' => 'Widget updated successfully!',
        ]);
    }

    public function destroy(Widget $widget)
    {
        $this->authorize('update', $widget->appPage->project);

        $widget->delete();

        return response()->json([
            'success' => true,
            'message' => 'Widget deleted successfully!',
        ]);
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

        return response()->json([
            'success' => true,
            'message' => 'Widget order updated successfully!',
        ]);
    }
}