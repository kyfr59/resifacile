<?php

namespace App\Livewire;

use App\Models\Brand;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

class SearchBrand extends Component
{
    public bool $openSearchBox = false;
    public Collection $brands;
    public string $search = '';

    public function mount(): void
    {
        $this->brands = Brand::limit(10)->orderByDesc('views')->get();
    }

    public function updatedSearch(string $value): void
    {
        if($value) {
            $this->brands = Brand::search($value)->get();
        } else {
            $this->brands = Brand::limit(10)->orderByDesc('views')->get();
        }
    }

    public function render(): View
    {
        return view('livewire.search-brand');
    }
}
