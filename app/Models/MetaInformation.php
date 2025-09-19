<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MetaInformation extends Model
{
    use HasFactory;

    protected $table = 'meta_information';
    
    protected $fillable = [
        'metable_type',
        'metable_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'focus_keyword',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
        'og_url',
        'og_site_name',
        'twitter_card',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'twitter_site',
        'twitter_creator',
        'canonical_url',
        'robots',
        'schema_markup',
        'seo_score'
    ];

    public function metable()
    {
        return $this->morphTo();
    }

    public function getEffectiveMetaTitleAttribute()
    {
        if ($this->meta_title) {
            return $this->meta_title;
        }
        
        if ($this->metable && $this->metable->title) {
            $template = config('app.meta.default_title_template', '{{page_title}} - {{site_name}}');
            return str_replace(
                ['{{page_title}}', '{{site_name}}'],
                [$this->metable->title, config('app.name')],
                $template
            );
        }
        
        return config('app.name');
    }

    public function getEffectiveMetaDescriptionAttribute()
    {
        if ($this->meta_description) {
            return Str::limit($this->meta_description, 160);
        }
        
        if ($this->metable && $this->metable->content) {
            return Str::limit(strip_tags($this->metable->content), 160);
        }
        
        return config('app.meta.default_description', '');
    }

    public function getEffectiveOgTitleAttribute()
    {
        return $this->og_title ?: $this->getEffectiveMetaTitleAttribute();
    }

    public function getEffectiveOgDescriptionAttribute()
    {
        return $this->og_description ?: $this->getEffectiveMetaDescriptionAttribute();
    }

    public function getEffectiveTwitterTitleAttribute()
    {
        return $this->twitter_title ?: $this->getEffectiveMetaTitleAttribute();
    }

    public function getEffectiveTwitterDescriptionAttribute()
    {
        return $this->twitter_description ?: $this->getEffectiveMetaDescriptionAttribute();
    }
}