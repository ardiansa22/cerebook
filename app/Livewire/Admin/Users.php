<?php

namespace App\Livewire\Admin;

use App\Livewire\MainBase;
use App\Models\User;
use Livewire\Component;

class Users extends MainBase
{
    public function mount()
    {
        $this->model = User::class;
        $this->fields = [
            'name' => '',
            'email' => '',
            'password' => '',
            'role' => '',
        ];
        $this->searchableFields = ['name'];
    }
      protected function getValidationRules()
    {
        return [
           'fields.name' => 'required|string|max:255',
            'fields.email' => 'required|email',
            'fields.password' => 'nullable|min:6|same:fields.password_confirmation',
            'fields.role' => 'required|in:admin,user',
        ];
    }

    public function render()
    {
        
        $query = $this->getQuery($this->model)->where('role','admin');
        $users = $query->paginate($this->perPage);
        return view('livewire.admin.users',[
            'users' => $users,
        ]);
    }
}
