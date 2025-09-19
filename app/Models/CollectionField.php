<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollectionField extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_id',
        'name',
        'label',
        'type',
        'default_value',
        'is_required',
        'is_unique',
        'is_searchable',
        'validation_rules',
        'field_options',
        'ui_settings',
        'related_collection_id',
        'relation_config',
        'relation_type',
        'foreign_key',
        'local_key',
        'cascade_delete',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_unique' => 'boolean',
        'is_searchable' => 'boolean',
        'is_active' => 'boolean',
        'cascade_delete' => 'boolean',
        'validation_rules' => 'array',
        'field_options' => 'array',
        'ui_settings' => 'array',
        'relation_config' => 'array',
    ];

    public const FIELD_TYPES = [
        'text' => 'Text',
        'textarea' => 'Textarea',
        'email' => 'Email',
        'url' => 'URL',
        'number' => 'Number',
        'decimal' => 'Decimal',
        'boolean' => 'Boolean',
        'date' => 'Date',
        'datetime' => 'Date & Time',
        'time' => 'Time',
        'select' => 'Select (Dropdown)',
        'multiselect' => 'Multi-Select',
        'checkbox' => 'Checkbox',
        'radio' => 'Radio Buttons',
        'file' => 'File Upload',
        'image' => 'Image Upload',
        'json' => 'JSON',
        'relation' => 'Relation',
        'color' => 'Color Picker',
        'password' => 'Password',
    ];

    public function collection(): BelongsTo
    {
        return $this->belongsTo(DataCollection::class, 'collection_id');
    }

    public function relatedCollection(): BelongsTo
    {
        return $this->belongsTo(DataCollection::class, 'related_collection_id');
    }

    public function data(): HasMany
    {
        return $this->hasMany(CollectionData::class, 'field_id');
    }

    public function getValidationRulesString(): string
    {
        $rules = [];

        if ($this->is_required) {
            $rules[] = 'required';
        }

        if ($this->is_unique) {
            $rules[] = 'unique:collection_data,field_value';
        }

        // Type-specific rules
        switch ($this->type) {
            case 'email':
                $rules[] = 'email';
                break;
            case 'url':
                $rules[] = 'url';
                break;
            case 'number':
                $rules[] = 'integer';
                break;
            case 'decimal':
                $rules[] = 'numeric';
                break;
            case 'boolean':
                $rules[] = 'boolean';
                break;
            case 'date':
                $rules[] = 'date';
                break;
            case 'datetime':
                $rules[] = 'date';
                break;
            case 'time':
                $rules[] = 'date_format:H:i';
                break;
            case 'file':
            case 'image':
                $rules[] = 'file';
                if ($this->type === 'image') {
                    $rules[] = 'image';
                }
                break;
            case 'json':
                $rules[] = 'json';
                break;
        }

        // Custom validation rules
        if ($this->validation_rules) {
            $rules = array_merge($rules, $this->validation_rules);
        }

        return implode('|', $rules);
    }

    public function castValue($value)
    {
        if ($value === null) {
            return null;
        }

        return match ($this->type) {
            'boolean' => (bool) $value || $value === '1',
            'number' => (int) $value,
            'decimal' => (float) $value,
            'json' => is_string($value) ? json_decode($value, true) : $value,
            'date' => \Carbon\Carbon::parse($value)->format('Y-m-d'),
            'datetime' => \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s'),
            'time' => \Carbon\Carbon::parse($value)->format('H:i:s'),
            'multiselect', 'checkbox' => is_string($value) ? json_decode($value, true) : $value,
            default => (string) $value,
        };
    }

    public function getSelectOptions(): array
    {
        if (!in_array($this->type, ['select', 'multiselect', 'radio', 'checkbox'])) {
            return [];
        }

        return $this->field_options['options'] ?? [];
    }

    public function getFileUploadSettings(): array
    {
        if (!in_array($this->type, ['file', 'image'])) {
            return [];
        }

        return [
            'allowed_types' => $this->field_options['allowed_types'] ?? [],
            'max_size' => $this->field_options['max_size'] ?? 2048, // KB
            'path' => $this->field_options['path'] ?? 'uploads',
        ];
    }

    public function isRelationField(): bool
    {
        return $this->type === 'relation' && $this->related_collection_id;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    public function scopeSearchable($query)
    {
        return $query->where('is_searchable', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}