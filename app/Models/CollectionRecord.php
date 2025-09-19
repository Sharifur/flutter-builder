<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CollectionRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_id',
        'record_uuid',
        'status',
        'created_by',
        'updated_by',
        'published_at',
    ];

    protected $casts = [
        'status' => 'array',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($record) {
            if (empty($record->record_uuid)) {
                $record->record_uuid = (string) Str::uuid();
            }
        });
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(DataCollection::class, 'collection_id');
    }

    public function data(): HasMany
    {
        return $this->hasMany(CollectionData::class, 'record_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(AppUser::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(AppUser::class, 'updated_by');
    }

    public function getFieldValue(string $fieldName)
    {
        $field = $this->collection->getFieldByName($fieldName);
        if (!$field) {
            return null;
        }

        $data = $this->data()->where('field_id', $field->id)->first();
        if (!$data) {
            return $field->default_value;
        }

        return $field->castValue($data->field_value);
    }

    public function setFieldValue(string $fieldName, $value): bool
    {
        $field = $this->collection->getFieldByName($fieldName);
        if (!$field) {
            return false;
        }

        $this->data()->updateOrCreate(
            [
                'field_id' => $field->id,
            ],
            [
                'collection_id' => $this->collection_id,
                'field_value' => $this->collection->castValueForStorage($value, $field->type),
                'field_type' => $field->type,
            ]
        );

        return true;
    }

    public function getAllFieldValues(): array
    {
        $values = [];

        foreach ($this->collection->fields as $field) {
            $values[$field->name] = $this->getFieldValue($field->name);
        }

        return $values;
    }

    public function toApiArray(): array
    {
        return [
            'id' => $this->record_uuid,
            'data' => $this->getAllFieldValues(),
            'meta' => [
                'created_at' => $this->created_at->toISOString(),
                'updated_at' => $this->updated_at->toISOString(),
                'published_at' => $this->published_at?->toISOString(),
                'status' => $this->status,
            ],
        ];
    }

    public function updateRecord(array $data, ?AppUser $user = null): bool
    {
        $this->updated_by = $user?->id;
        $this->save();

        foreach ($data as $fieldName => $value) {
            $this->setFieldValue($fieldName, $value);
        }

        return true;
    }

    public function publishRecord(): bool
    {
        $this->published_at = now();
        return $this->save();
    }

    public function unpublishRecord(): bool
    {
        $this->published_at = null;
        return $this->save();
    }

    public function isPublished(): bool
    {
        return $this->published_at !== null;
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }

    public function scopeDraft($query)
    {
        return $query->whereNull('published_at');
    }

    public function scopeCreatedBy($query, $userId)
    {
        return $query->where('created_by', $userId);
    }
}