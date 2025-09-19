<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class DataCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'slug',
        'description',
        'icon',
        'is_active',
        'is_system',
        'settings',
        'permissions',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_system' => 'boolean',
        'settings' => 'array',
        'permissions' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($collection) {
            if (empty($collection->slug)) {
                $collection->slug = Str::slug($collection->name);
            }
        });

        static::updating(function ($collection) {
            if ($collection->isDirty('name') && empty($collection->slug)) {
                $collection->slug = Str::slug($collection->name);
            }
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function fields(): HasMany
    {
        return $this->hasMany(CollectionField::class, 'collection_id')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    public function allFields(): HasMany
    {
        return $this->hasMany(CollectionField::class, 'collection_id')
            ->orderBy('sort_order');
    }

    public function records(): HasMany
    {
        return $this->hasMany(CollectionRecord::class, 'collection_id');
    }

    public function data(): HasMany
    {
        return $this->hasMany(CollectionData::class, 'collection_id');
    }

    public function getApiEndpoint(): string
    {
        return "/api/collections/{$this->slug}";
    }

    public function getFieldByName(string $name): ?CollectionField
    {
        return $this->fields()->where('name', $name)->first();
    }

    public function getRequiredFields(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->fields()->where('is_required', true)->get();
    }

    public function getUniqueFields(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->fields()->where('is_unique', true)->get();
    }

    public function getSearchableFields(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->fields()->where('is_searchable', true)->get();
    }

    public function createRecord(array $data, ?AppUser $user = null): CollectionRecord
    {
        $record = $this->records()->create([
            'record_uuid' => Str::uuid(),
            'created_by' => $user?->id,
            'updated_by' => $user?->id,
            'published_at' => now(),
        ]);

        foreach ($this->fields as $field) {
            $value = $data[$field->name] ?? $field->default_value;

            if ($value !== null) {
                $record->data()->create([
                    'collection_id' => $this->id,
                    'field_id' => $field->id,
                    'field_value' => $this->castValueForStorage($value, $field->type),
                    'field_type' => $field->type,
                ]);
            }
        }

        return $record->load('data.field');
    }

    private function castValueForStorage($value, string $type): string
    {
        return match ($type) {
            'boolean' => $value ? '1' : '0',
            'json' => is_array($value) ? json_encode($value) : $value,
            'date' => $value instanceof \DateTime ? $value->format('Y-m-d') : $value,
            'datetime' => $value instanceof \DateTime ? $value->format('Y-m-d H:i:s') : $value,
            default => (string) $value,
        };
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    public function scopeCustom($query)
    {
        return $query->where('is_system', false);
    }

    public function scopeForProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }
}