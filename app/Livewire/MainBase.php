<?php

namespace App\Livewire;

use App\Models\BookGenreCustom;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

abstract class MainBase extends Component
{
    use WithPagination, WithFileUploads;

    public $model;
    public $fields = [];
    public $searchableFields = [];

    public $search = '';
    public $perPage = 5;

    public $isEdit = false;
    public $editingId = null;
    public $showModal = false;
    public $showBuyModal = false;

    public $modalTitle = '';

    public $selectedIds = [];
    public $selectAll = false;

    public $notifyMessage = '';
    public $notifyType = 'success';
    public $showNotification = false;

    public $categori = [];
    public $selectedGenres = [];

    public $image;
    public $oldImage;
    public $uploadDirectory = '';

    // Reset pagination when search changes
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Generic query builder with optional search
    public function getQuery($model)
    {
        return $model::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    foreach ($this->searchableFields as $field) {
                        $q->orWhere($field, 'like', '%' . $this->search . '%');
                    }
                });
            });
    }
    public function showModal()
    {
        $this->showModal = true;
    }


    // Generic image upload function
    public function uploadImage($imageFile, $directory, $oldFile = null)
{
    if ($imageFile) {
        // Gunakan path relatif tanpa 'private/public/'
        $path = $directory; // Contoh: 'books'
        
        // Hapus file lama jika ada (gunakan disk 'public')
        if ($oldFile) {
            Storage::disk('public')->delete($path . '/' . $oldFile);
        }
        
        // Generate nama file unik
        $originalName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $imageFile->getClientOriginalExtension();
        $uniqueName = $originalName . '_' . uniqid() . '.' . $extension;
        
        // Simpan menggunakan disk 'public'
        $imageFile->storeAs($path, $uniqueName, 'public');
        
        return $uniqueName;
    }
    
    return null;
}


    // Sync many-to-many relation (e.g., genres)
    protected function syncRelations($record, $relation, $selectedIds)
    {
        if (method_exists($record, $relation)) {
            $record->$relation()->sync($selectedIds);
        }
    }

    // Default validation rules (override this in the child class if needed)
    protected function getValidationRules()
    {
        return [
            'selectedGenres' => 'required|array|min:1',
            'selectedGenres.*' => 'exists:genres,id',
        ];
    }

    // Store function
    public function store()
{
    try {
        // $this->validate($this->getValidationRules());
        
        $data = $this->fields;
        // dd($data);
        // Buat record utama
        $record = $this->model::create($data);

        // Simpan genre custom jika dipilih
        if (!empty($this->selectedGenres)) {
            \App\Models\BookGenreCustom::create([
                'book_id' => $record->id,
                'genre_ids' => $this->selectedGenres,
            ]);
        }

        // Sinkronisasi relasi genres
        if (!empty($this->selectedGenres) && method_exists($record, 'genres')) {
            $this->syncRelations($record, 'genres', $this->selectedGenres);
        }

        // Upload image jika ada
        if ($this->image) {
            $record->update([
                'image' => $this->uploadImage($this->image, $this->uploadDirectory)
            ]);
        }

        $this->resetInput();
        $this->showNotification(class_basename($this->model) . ' created successfully.');
    } catch (\Throwable $e) {
        $this->showNotification('Error: ' . $e->getMessage(), 'error');
    }
}
public function update($id)
{
    try {
        $record = $this->model::findOrFail($id);

        // $this->validate($this->getValidationRules());

        $data = $this->fields;

        // Upload image jika ada
        if ($this->image) {
            $data['image'] = $this->uploadImage($this->image, $this->uploadDirectory, $this->oldImage);
        }

        $record->update($data);

        // Update atau hapus BookGenreCustom
        if (!empty($this->selectedGenres)) {
            \App\Models\BookGenreCustom::updateOrCreate(
                ['book_id' => $record->id],
                ['genre_ids' => $this->selectedGenres]
            );
        } else {
            \App\Models\BookGenreCustom::where('book_id', $record->id)->delete();
        }

        // Sinkronisasi relasi genres
        if (!empty($this->selectedGenres) && method_exists($record, 'genres')) {
            $this->syncRelations($record, 'genres', $this->selectedGenres);
        }

        $this->resetInput();
        $this->showNotification(class_basename($this->model) . ' updated successfully.');
    } catch (\Throwable $e) {
        $this->showNotification('Error: ' . $e->getMessage(), 'error');
    }
}


    // Open modal for creating
    public function openCreateModal()
    {
        $this->resetInput();
        $this->modalTitle = 'Create ' . class_basename($this->model);
        $this->isEdit = false;
        $this->categori = Category::where('is_active', true)->get();
        $this->showModal = true;
        
        
    }

    // Open modal for editing
    public function openEditModal($id)
    {
        $record = $this->model::findOrFail($id);
        // dd($record);
        $this->fields = $record->toArray();
        $this->editingId = $id;
        $this->isEdit = true;
        $this->categori = Category::where('is_active', true)->get();
        $this->showModal = true;

        if (method_exists($record, 'genres')) {
            $this->selectedGenres = $record->genres()->pluck('id')->toArray();
        }

        if (isset($record->image)) {
            $this->oldImage = $record->image;
        }
    }

    // Save (store or update)
    public function save()
    {
        if ($this->isEdit) {
            $this->update($this->editingId);
        } else {
            $this->store();
        }

        $this->showModal = false;
    }

    // Delete one
    public function delete($id)
    {
        $this->model::destroy($id);
    }

    // Delete multiple
    public function deleteSelected()
    {
        if (!empty($this->selectedIds)) {
            $this->model::destroy($this->selectedIds);
            $this->selectedIds = [];
            $this->selectAll = false;
        }
    }
    public function confirmDeleteSelected()
    {
        // Emit event ke browser untuk menampilkan konfirmasi JS
        $this->dispatch('confirm-delete');
    }


    // Select all
    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedIds = $this->getQuery($this->model)->pluck('id')->toArray();
        } else {
            $this->selectedIds = [];
        }
    }

    // Cancel editing
    public function cancelEdit()
    {
        $this->resetInput();
        $this->isEdit = false;
        $this->editingId = null;
    }

    // Reset form input
    public function resetInput()
    {
        foreach (array_keys($this->fields) as $key) {
            $this->fields[$key] = null;
        }

        $this->image = null;
        $this->oldImage = null;
        $this->selectedGenres = [];
    }

    // Notification
    // public function showNotification($message, $type = 'success')
    // {
    //     $this->notifyMessage = $message;
    //     $this->notifyType = $type;
    //     $this->showNotification = true;
    // }
    public function showNotification($message, $type = 'success')
    {
        $this->dispatch('swal:notification', [
            'type' => $type,
            'message' => $message,
        ]);
    }


    public function resetNotification()
    {
        $this->notifyMessage = '';
        $this->notifyType = 'success';
        $this->showNotification = false;
    }

    // Toggle active status
    public function toggleStatus($id = null)
{
    $ids = $id ? [$id] : $this->selectedIds;

    if (empty($ids)) {
        $this->showNotification('Tidak ada item yang dipilih.', 'error');
        return;
    }

    $records = $this->model::whereIn('id', $ids)->get();

    foreach ($records as $record) {
        if (isset($record->is_active)) {
            $record->is_active = !$record->is_active;
            $record->save();
        }
    }

    $this->showNotification(count($ids) . ' item status berhasil diperbarui.');

    // Reset pilihan setelah toggle massal
    if (!$id) {
        $this->selectedIds = [];
        $this->selectAll = false;
    }
}

    
}
