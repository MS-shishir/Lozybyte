<?php

namespace App\Services;

use App\Models\EditableElement;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class ElementRegistry
{
    protected $mappings = [
        'services' => \App\Models\Service::class,
        'products' => \App\Models\Product::class,
        'testimonials' => \App\Models\Testimonial::class,
        'teams' => \App\Models\Team::class,
        'posts' => \App\Models\Post::class,
        'nav_items' => \App\Models\NavItem::class,
        'categories' => \App\Models\Category::class,
        'faqs' => \App\Models\Faq::class,
        'pricing_plans' => \App\Models\PricingPlan::class,
        'clients' => \App\Models\Client::class,
        'settings' => \App\Models\Setting::class,
        'homepage_sections' => \App\Models\HomepageSection::class,
    ];

    /**
     * Resolve element_key and return metadata about it.
     */
    public function resolve($key)
    {
        $parts = explode('.', $key);
        if (count($parts) < 2) {
            return [
                'type' => 'generic',
                'key' => $key,
            ];
        }

        $type = $parts[0];
        if (!isset($this->mappings[$type])) {
            return [
                'type' => 'generic',
                'key' => $key,
            ];
        }

        $modelClass = $this->mappings[$type];

        if ($type === 'settings') {
            $field = $parts[1];
            $subfield = isset($parts[2]) ? implode('.', array_slice($parts, 2)) : null;
            return [
                'type' => 'model',
                'class' => $modelClass,
                'lookup_type' => 'first',
                'field' => $field,
                'subfield' => $subfield,
            ];
        }

        if ($type === 'homepage_sections') {
            $sectionKey = $parts[1];
            $field = $parts[2];
            $subfield = isset($parts[3]) ? implode('.', array_slice($parts, 3)) : null;
            return [
                'type' => 'model',
                'class' => $modelClass,
                'lookup_type' => 'key',
                'lookup_value' => $sectionKey,
                'field' => $field,
                'subfield' => $subfield,
            ];
        }

        $id = $parts[1];
        $field = $parts[2];
        $subfield = isset($parts[3]) ? implode('.', array_slice($parts, 3)) : null;

        return [
            'type' => 'model',
            'class' => $modelClass,
            'lookup_type' => 'id',
            'lookup_value' => $id,
            'field' => $field,
            'subfield' => $subfield,
        ];
    }

    /**
     * Get the unified data (content, styles, settings, metadata) for a key.
     */
    public function get($key, $lang = 'en')
    {
        $resolved = $this->resolve($key);
        $editable = EditableElement::where('element_key', $key)->first();

        $content = null;
        $styles = $editable ? ($editable->styles ?: []) : [];
        $settings = $editable ? ($editable->settings ?: []) : [];
        $metadata = $editable ? ($editable->metadata ?: []) : [];

        if ($resolved['type'] === 'generic') {
            $content = $editable ? $editable->content : null;
        } else {
            $record = $this->findRecord($resolved);
            if ($record) {
                $field = $resolved['field'];
                
                // Get translatable content array
                if (method_exists($record, 'getTranslations') && in_array($field, $record->translatable ?? [])) {
                    $content = $record->getTranslations($field);
                } else {
                    $content = $record->{$field};
                }
            }
        }

        return [
            'element_key' => $key,
            'content' => $content,
            'styles' => $styles,
            'settings' => $settings,
            'metadata' => $metadata,
        ];
    }

    /**
     * Save/Update data for an element key.
     */
    public function save($key, $data, $lang = 'en')
    {
        Cache::flush();
        $resolved = $this->resolve($key);
        
        $editable = EditableElement::firstOrNew(['element_key' => $key]);

        if (isset($data['styles'])) {
            $editable->styles = $data['styles'];
        }
        if (isset($data['settings'])) {
            $editable->settings = $data['settings'];
        }
        if (isset($data['metadata'])) {
            $editable->metadata = $data['metadata'];
        }

        if ($resolved['type'] === 'generic') {
            if (isset($data['content'])) {
                $editable->content = $data['content'];
            }
            $editable->save();
        } else {
            $record = $this->findRecord($resolved);
            if ($record) {
                $field = $resolved['field'];
                if (isset($data['content'])) {
                    $value = $data['content'];
                    
                    // Handle translation updates
                    $isTranslatable = false;
                    if (method_exists($record, 'getTranslations') && in_array($field, $record->translatable ?? [])) {
                        $isTranslatable = true;
                    }

                    if ($isTranslatable) {
                        $currentTranslations = $record->getTranslations($field);
                        if (is_array($value)) {
                            $currentTranslations = array_merge($currentTranslations, $value);
                        } else {
                            $currentTranslations[$lang] = $value;
                        }
                        $record->{$field} = $currentTranslations;
                    } else {
                        $record->{$field} = $value;
                    }
                }
                $record->save();
            }
            $editable->save();
        }

        return $this->get($key, $lang);
    }

    /**
     * Internal helper to find record.
     */
    protected function findRecord($resolved)
    {
        $class = $resolved['class'];
        if ($resolved['lookup_type'] === 'first') {
            return $class::first();
        }
        if ($resolved['lookup_type'] === 'key') {
            return $class::where('key', $resolved['lookup_value'])->first();
        }
        if ($resolved['lookup_type'] === 'id') {
            return $class::find($resolved['lookup_value']);
        }
        return null;
    }
}
