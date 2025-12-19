<?php

namespace App\Actions\Letter;

use Carbon\Carbon;
use DOMDocument;
use Illuminate\Support\Str;

class GetLetter
{
    /**
     * @param array $template
     * @return string
     */
    public function handle(array $template): string
    {
        $DOM = new DOMDocument();
        $DOM->loadHTML('<?xml encoding="utf-8" ?>' . Str::markdown($template['model']));

        $paragraphs = [];

        $tags = $DOM->getElementsByTagName('p');
        $varkey = '\w*';

        foreach ($tags as $index => $paragraph) {
            $paragraph = $paragraph->nodeValue;

            if($index > 4 && ($index + 2) < count($tags)) {
                $words = explode(' ', $paragraph);

                foreach ($words as $i => $word) {
                    preg_match('/{{' . $varkey . '}}/', $word, $matches);
                    if(! $matches) {
                        $words[$i] = mb_ereg_replace('\w', '<span class="char"></span>', $word);
                    }
                }

                $paragraph = implode(' ', $words);
            }

            $paragraph = preg_replace('/(Objet)/', '<strong>${1}</strong>', $paragraph);
            $paragraph = preg_replace_callback('/{{(' . $varkey . ')}}/', function ($matches) {
                $field = data_get($template['group_fields']['fields'], $matches[1]);

                if($matches[1] === 'ville') {
                    return '<span class="varkey' . (empty($field['value']) ? '' : ' text-green-500') . '">' . Str::of(empty($field['value']) ? $field['label'] : $field['value'])->lower()->title() . '</span>';
                }

                if(Str::contains($matches[1], 'reference')) {
                    return '<span class="varkey' . (empty($field['value']) ? '' : ' text-green-500') . '">' . Str::upper(empty($field['value']) ? $field['label'] : $field['value']) . '</span>';
                }

                if(Str::contains($matches[1], 'date')) {
                    if($matches[1] === 'date_now') {
                        $field['value'] = now()->format('d/m/Y');
                    } else {
                        $field['value'] = (new Carbon($field['value']))->format('d/m/Y');
                    }
                }

                if($matches[1] === 'complement_document') {
                    $field['value'] = nl2br($field['value']);
                }

                return '<span class="varkey' . (empty($field['value']) ? '' : ' text-green-500') . '">' . Str::lower(empty($field['value']) ? $field['label'] : $field['value']) . '</span>';
            }, $paragraph);

            $className = '';

            if($index === 0) {
                $className = 'text-right pb-16';
            } else if($index === 1) {
                $className = 'mb-0';
            } else if($index === 2) {
                $className = 'pb-9';
            } else if($index === 3) {
                $className = 'pb-3';
            } else if(($index + 1) === count($tags)) {
                $className = 'pt-9';
            }

            $paragraphs[] = '<p class="' . $className . '">' . $paragraph . '</p>';
        }

        return implode($paragraphs);
    }
}
