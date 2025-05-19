<?php

namespace App\Livewire\Admin;

use App\Livewire\MainBase;
use App\Models\Genre;
use Livewire\Component;

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

    public function render()
    {
        $query = $this->getQuery($this->model);
        $genres = $query->paginate($this->perPage);
        
        return view('livewire.admin.genres', [
            'genres' => $genres
        ]);
    }
}
