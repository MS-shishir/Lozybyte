<?php

namespace App\Traits;

use Illuminate\Support\Facades\App;

trait HasTranslations
{
    /**
     * Intercept attribute retrieval to automatically resolve translatable fields.
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (property_exists($this, 'translatable') && is_array($this->translatable) && in_array($key, $this->translatable)) {
            if (is_string($value)) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $value = $decoded;
                }
            }

            if (is_array($value)) {
                $locale = App::getLocale();
                return $value[$locale] ?? $value['en'] ?? array_values($value)[0] ?? '';
            }
        }

        return $value;
    }

    /**
     * Retrieve the raw translations array for admin forms.
     */
    public function getTranslations(string $key): array
    {
        $value = parent::getAttribute($key);
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return json_last_error() === JSON_ERROR_NONE ? $decoded : [];
        }
        return is_array($value) ? $value : [];
    }

    /**
     * Retrieve a specific translation by key and locale.
     */
    public function getTranslation(string $key, string $locale): string
    {
        $translations = $this->getTranslations($key);
        return $translations[$locale] ?? '';
    }
}
