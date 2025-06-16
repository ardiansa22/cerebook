<div>
@include('layouts.component.swalert')
@include('layouts.component.confirmdelete')

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Books</h2>
        <div class="flex space-x-4">
            @include('layouts.component.createdel ')
        </div>
    </div>
    <div class="mb-4">
       @include('layouts.component.searchtable')
        <label class="text-sm text-gray-700">
            Show
            <select wire:model.live="perPage" class="ml-2 border rounded p-1">
                <option value="5">5</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            entries
            </label>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Categories</label>
        <select wire:model.live="selectedfilterCategories" multiple 
                class="w-full border rounded-md p-2 text-sm">
            @foreach($filtercategories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Genres</label>
        <select wire:model.live="selectedfilterGenres" multiple 
                class=" w-full border rounded-md p-2 text-sm">
            @foreach($filtergenres as $genre)
                <option value="{{ $genre->id }}">{{ $genre->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<!-- Tambahkan tombol reset di sini -->
<div class="mb-6 flex justify-end">
    <button wire:click="resetFilters" 
            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md text-sm font-medium text-gray-700">
        <i class="fas fa-filter-circle-xmark mr-1"></i> Reset Filters
    </button>
</div>

    <!-- Tabel Data -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <input type="checkbox" wire:model.live="selectAll" wire:click="toggleSelectAll">
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price Book</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rent Price</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Genre</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($books as $index => $book)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <input type="checkbox" wire:model.live="selectedIds" value="{{ $book->id }}">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        {{ $books->firstItem() + $index }}
                    </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                        @if($book->image)
                            <img src="{{ asset('storage/books/' . $book->image) }}" 
                                alt="Book Image" 
                                class="h-16 w-16 object-cover rounded">
                        @else
                            <div class="h-16 w-16 bg-gray-200 rounded flex items-center justify-center">
                                <span class="text-xs text-gray-500">No Image</span>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $book->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        {{ $book->title }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        {{ number_format($book->price) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        {{ number_format($book->rent_price) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        {{ $book->stock }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        {{ $book->category->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                         @php
                            $genreIds = $bookGenreMap[$book->id]->genre_ids ?? [];
                            $genreNames = \App\Models\Genre::whereIn('id', $genreIds)->pluck('name')->toArray();
                        @endphp
                        {{ implode(', ', $genreNames) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">
                        <button wire:click="openEditModal({{ $book->id }})" class="text-blue-600 hover:text-blue-900 mr-3">
                            <svg class="h-5 w-5 inline-block mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 012.828 2.828l-7.93 7.931a2 2 0 01-2.828 0l-2.828-2.828a2 2 0 010-2.828l7.93-7.931a2 2 0 012.828 0zM1.414 10.586l7.93-7.93a2 2 0 012.828 2.828l-7.93 7.931a2 2 0 01-2.828 0z" />
                            </svg>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $books->links() }}
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 opacity-75"></div>
                </div>

                <!-- Modal content -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            {{ $modalTitle }}
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <flux:input type="text" label="Name" wire:model="fields.name" 
                                    id="name"/>
                                @error('fields.name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <flux:input type="title" label="title" wire:model="fields.title" 
                                    id="title"/>
                                @error('fields.name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <flux:textarea type="description" label="Description" wire:model="fields.description" 
                                    id="description"/>
                                @error('fields.description')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                
                            </div>
                            
                            <div>
                                <flux:input type="number" label="Price Book" wire:model="fields.price" 
                                    id="price"/>
                                @error('fields.price')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                           <div>
                                <flux:input type="number" label="Rent Price" wire:model="fields.rent_price" id="rent_price" readonly />
                                <p class="mt-1 text-sm text-gray-500">Rent price will be automatically calculated as 35% of the book price.</p>
                                @error('fields.rent_price')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <flux:input type="number" label="Fines Price" wire:model="fields.fines_price" id="fines_price" readonly />
                                <p class="text-sm text-gray-500 mt-1">Fines will automatically appear when Price Book is filled</p>
                                @error('fields.fines_price')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>    
                            <div>
                                <flux:input type="number" label="Stock" wire:model="fields.stock" 
                                    id="stock"/>
                                @error('fields.stock')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                                <select 
                                    wire:model="fields.category_id" 
                                    id="category_id"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-700 bg-white"
                                >
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                
                                @error('fields.category_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div wire:ignore>
                                 <label for="category_id" class="block text-sm font-medium text-gray-700">Genre</label>
                                <select 
                                    wire:model="selectedGenres" 
                                    id="genres"
                                    multiple
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-gray-700 bg-white select2-multiple"
                                >
                                    @foreach ($genres as $genre)
                                        <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <flux:input type="file" wire:model="image" label="Image"/>
                                @error('image')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                @if ($isEdit && $oldImage)
                                    <div class="mt-2">
                                       <img src="{{ asset('storage/'.$uploadDirectory.'/' . $oldImage) }}" alt="Old Image" class="h-20">
                                    </div>
                                @elseif ($image)
                                    <div class="mt-2">
                                        <img src="{{ $image->temporaryUrl() }}" alt="New Image Preview" class="h-20">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <div class="flex space-x-4 sm:space-x-reverse">
                            <flux:button wire:click="$set('showModal', false)" variant="filled">
                                Cancel
                            </flux:button>
                            <flux:button wire:click.prevent="save" variant="primary">
                                Save
                            </flux:button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- Di bagian bawah sebelum penutup div -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('livewire:load', function () {
    // Initialize Select2
    function initSelect2() {
        $('.select2-multiple').select2({
            width: '100%',
            placeholder: "Select genres",
            allowClear: true
        }).on('change', function (e) {
            @this.set('selectedGenres', $(this).val());
        });
    }

    initSelect2();

    // Reinitialize when modal is opened
    Livewire.on('openModal', () => {
        setTimeout(() => {
            initSelect2();
        }, 100);
    });
});
</script>
</div>