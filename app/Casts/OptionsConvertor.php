<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class OptionsConvertor implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if(!$value) return null;
        return Arr::map(json_decode($value), static function ($option, $key) {
            if($option->name === 'black_print' || $option->name === 'color_print') {
                $option->price = collect($option->price)->each(fn ($price) => $price)->toArray();
            } else {
                $option->price = (int)($option->price);
            }

            return $option;
        });
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, string $key, $value, array $attributes): string
    {
        return json_encode(Arr::map($value->toArray(), static function($option, $key) {
            if($option['name'] === 'black_print' || $option['name'] === 'color_print') {
                $option['price'] = collect($option['price'])->map(fn ($price) => $price)->toArray();
            } else {
                $option['price'] = (int)($option['price']);
            }

            return $option;
        }));
    }
}
