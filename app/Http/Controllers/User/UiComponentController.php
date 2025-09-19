<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UiComponent;
use Illuminate\Http\Request;

class UiComponentController extends Controller
{
    public function index(Request $request)
    {
        $query = UiComponent::active()->ordered();

        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        if ($request->has('type')) {
            $query->where('component_type', $request->type);
        }

        $components = $query->get();

        return response()->json([
            'success' => true,
            'components' => $components->map(function ($component) {
                return [
                    'id' => $component->id,
                    'name' => $component->name,
                    'type' => $component->component_type,
                    'category' => $component->category,
                    'description' => $component->description,
                    'icon' => $component->icon,
                    'preview_image' => $component->preview_image,
                    'default_config' => $component->default_config,
                    'field_definitions' => $component->field_definitions,
                ];
            }),
        ]);
    }

    public function show(UiComponent $uiComponent)
    {
        if (!$uiComponent->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Component not found or inactive',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'component' => [
                'id' => $uiComponent->id,
                'name' => $uiComponent->name,
                'type' => $uiComponent->component_type,
                'category' => $uiComponent->category,
                'description' => $uiComponent->description,
                'icon' => $uiComponent->icon,
                'preview_image' => $uiComponent->preview_image,
                'default_config' => $uiComponent->default_config,
                'field_definitions' => $uiComponent->field_definitions,
                'dependencies' => $uiComponent->dependencies,
            ],
        ]);
    }

    public function render(Request $request, UiComponent $uiComponent)
    {
        if (!$uiComponent->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Component not found or inactive',
            ], 404);
        }

        $config = $request->get('config', []);

        try {
            $renderedData = $uiComponent->render($config);

            return response()->json([
                'success' => true,
                'rendered_data' => $renderedData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to render component: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function validate(Request $request, UiComponent $uiComponent)
    {
        if (!$uiComponent->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Component not found or inactive',
            ], 404);
        }

        $config = $request->get('config', []);

        $component = $uiComponent->getComponentInstance();

        if (!$component) {
            return response()->json([
                'success' => false,
                'message' => 'Component class not found',
            ], 500);
        }

        $isValid = $component->validate($config);

        return response()->json([
            'success' => true,
            'is_valid' => $isValid,
            'schema' => $component->getConfigSchema(),
        ]);
    }

    public function categories()
    {
        $categories = UiComponent::active()
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->values();

        return response()->json([
            'success' => true,
            'categories' => $categories,
        ]);
    }
}