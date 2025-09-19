<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function appPages(): HasMany
    {
        return $this->hasMany(AppPage::class);
    }

    public function dataCollections(): HasMany
    {
        return $this->hasMany(DataCollection::class);
    }

    public function getAllWidgets()
    {
        return Widget::whereIn('app_page_id', $this->appPages->pluck('id'))->get();
    }

    public function toSchemaArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'pages' => $this->appPages->map(function ($page) {
                return [
                    'id' => $page->slug,
                    'name' => $page->name,
                    'widgets' => $page->widgets->map(function ($widget) {
                        return array_merge([
                            'id' => $widget->id,
                            'type' => $widget->type,
                        ], $widget->config);
                    })->toArray(),
                ];
            })->toArray(),
        ];
    }
}
