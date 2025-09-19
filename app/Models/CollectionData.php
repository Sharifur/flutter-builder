<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollectionData extends Model
{
    use HasFactory;

    protected $table = 'collection_data';

    protected $fillable = [
        'collection_id',
        'record_id',
        'field_id',
        'field_value',
        'field_type',
        'field_metadata',
    ];

    protected $casts = [
        'field_metadata' => 'array',
    ];

    public function collection(): BelongsTo
    {
        return $this->belongsTo(DataCollection::class, 'collection_id');
    }

    public function record(): BelongsTo
    {
        return $this->belongsTo(CollectionRecord::class, 'record_id');
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(CollectionField::class, 'field_id');
    }

    public function getCastedValue()
    {
        if (!$this->field) {
            return $this->field_value;
        }

        return $this->field->castValue($this->field_value);
    }

    public function scopeForField($query, $fieldId)
    {
        return $query->where('field_id', $fieldId);
    }

    public function scopeForRecord($query, $recordId)
    {
        return $query->where('record_id', $recordId);
    }

    public function scopeForCollection($query, $collectionId)
    {
        return $query->where('collection_id', $collectionId);
    }

    public function scopeWithFieldValue($query, $value)
    {
        return $query->where('field_value', $value);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('field_type', $type);
    }
}