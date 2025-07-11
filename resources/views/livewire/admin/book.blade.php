<div>
    @include('layouts.component.swalert')
    @include('layouts.component.confirmdelete')

   <div class="mb-6">
    <!-- Judul halaman dan aksi -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Books</h2>

                <!-- Breadcrumb dengan jarak -->
                <div class="mt-2">
                    @include('layouts.component.breadcrumb', [
                        'breadcrumbs' => [
                            ['label' => 'Dashboard', 'url' => route('dashboard')],
                            ['label' => 'Book']
                        ]
                    ])
                </div>
            </div>

            <!-- Tombol aksi -->
            <div class="flex space-x-2">
                @include('layouts.component.createdel')
            </div>
        </div>
    </div>


   

    <!-- Filter Section -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Category</label>
        <select wire:model.live="selectedfilterCategory" 
                class="w-full border rounded-md p-2 text-sm">
            <option value="">All Categories</option>
            @foreach($filtercategories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Genre</label>
        <select wire:model.live="selectedfilterGenre" 
                class="w-full border rounded-md p-2 text-sm">
            <option value="">All Genres</option>
            @foreach($filtergenres as $genre)
                <option value="{{ $genre->id }}">{{ $genre->name }}</option>
            @endforeach
        </select>
    </div>
</div>

    <!-- Reset Filter Button -->
    <div class="mb-6 flex justify-end">
        <button wire:click="resetFilters" 
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md text-sm font-medium text-gray-700">
            <i class="fas fa-filter-circle-xmark mr-1"></i> Reset Filters
        </button>
    </div>
     <!-- Filter dan Search Section -->
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

    <!-- Table Section -->
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
                            Rp.{{ number_format($book->price) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            Rp.{{ number_format($book->rent_price) }}
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
                             <flux:button 
                                class="bg-gradient-to-r from-amber-400 to-yellow-600 text-white px-3 py-1 rounded-md text-xs hover:from-amber-500 hover:to-yellow-500" 
                                wire:click="openEditModal({{ $book->id }})" 
                                size="xs">
                                Edit
                            </flux:button>
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
        <div class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0  bg-opacity-75 transition-opacity" 
                     aria-hidden="true"
                     wire:click="$set('showModal', false)"></div>

                <!-- Modal content -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-[99999]">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            {{ $modalTitle }}
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <flux:input type="text" label="Name" wire:model="fields.name" 
                                    id="name" require   />
                                @error('fields.name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <flux:input type="text" label="Title" wire:model="fields.title" 
                                    id="title"/>
                                @error('fields.title')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea wire:model="fields.description" id="description" rows="3"
                                    class="mt-1 block w-full shadow-sm sm:text-sm rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"></textarea>
                                @error('fields.description')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            {{-- ... bagian lain dari kode Anda ... --}}

                            <div>
                                <flux:input type="number" label="Price Book" wire:model.live.debounce.500ms="fields.price" id="price"/>
                                @error('fields.price')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <flux:input type="number" label="Rent Price" wire:model="fields.rent_price" id="rent_price" readonly
                                    wire:key="rent-price-{{ $fields['price'] ?? 0 }}"
                                />
                                <p class="mt-1 text-sm text-gray-500">Rent price will be automatically calculated as 35% of the book price.</p>
                                @error('fields.rent_price')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <flux:input type="number" label="Fines Price" wire:model="fields.fines_price" id="fines_price" readonly
                                    wire:key="fines-price-{{ $fields['price'] ?? 0 }}"
                                />
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
                                <label for="genres" class="block text-sm font-medium text-gray-700">Genre</label>
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

    <!-- Script Section -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <style>
        .select2-container--open {
            z-index: 999999 !important;
        }
        
        .modal-content {
            pointer-events: auto !important;
        }
        
        textarea, select, input {
            pointer-events: auto !important;
        }
    </style>
    
    <script>
    document.addEventListener('livewire:init', function() {
        // Inisialisasi Select2 saat modal terbuka
        Livewire.on('openModal', () => {
            setTimeout(() => {
                const select = $('#genres');
                
                select.select2({
                    width: '100%',
                    dropdownParent: $('.fixed.inset-0'), // Mengarah ke overlay modal
                    placeholder: "Select genres",
                    allowClear: true
                });
                
                // Handle perubahan nilai
                select.on('change', function(e) {
                    @this.set('selectedGenres', $(this).val());
                });
                
                // Set nilai awal jika ada
                if (@this.get('selectedGenres')) {
                    select.val(@this.get('selectedGenres')).trigger('change');
                }
                
                // Perbaiki fokus
                select.next('.select2-container').find('.select2-search__field').focus();
            }, 300);
        });
        
        // Bersihkan saat modal ditutup
        Livewire.on('closeModal', () => {
            $('#genres').select2('destroy');
        });
    });
    </script>
</div>