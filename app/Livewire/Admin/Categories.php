<?php

namespace App\Livewire\Admin;

use App\Livewire\MainBase;
use App\Models\Category;

class Categories extends MainBase
{
    public function mount()
    {
        $this->model = Category::class;
        $this->fields = [
            'name' => '',
        ];
        $this->searchableFields = ['name'];
    }

    public function getValidationRules()
    {
        return [
            'fields.name' => 'required|string|max:255|unique:categories,name,' . $this->editingId,
        ];
    }

    public function render()
    {
        $query = $this->getQuery($this->model);
        $categories = $query->paginate($this->perPage);

        return view('livewire.admin.categories', [
            'categories' => $categories
        ]);
    }
}
