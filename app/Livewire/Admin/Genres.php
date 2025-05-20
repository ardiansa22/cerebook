<?php

namespace App\Livewire\Admin;

use App\Livewire\MainBase;
use App\Models\Genre;

class Genres extends MainBase
{
    public function mount()
    {
        $this->model = Genre::class;
        $this->fields = [
            'name' => '',
        ];
        $this->searchableFields = ['name'];
    }

    // Override rules khusus genre (tidak perlu validasi selectedGenres)
    protected function getValidationRules()
    {
        return [
            'fields.name' => 'required|string|max:255',
        ];
    }

    public function render()
    {
        $query = $this->getQuery($this->model);
        $genres = $query->paginate($this->perPage);
        
        return view('livewire.admin.genres', [
            'genres' => $genres
        ]);
    }
}
