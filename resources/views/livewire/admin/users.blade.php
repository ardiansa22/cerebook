<div>
<div>
@include('layouts.component.swalert')
@include('layouts.component.confirmdelete ')

    <div class="mb-6">
    <!-- Judul halaman dan aksi -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">User</h2>

                <!-- Breadcrumb dengan jarak -->
                <div class="mt-2">
                    @include('layouts.component.breadcrumb', [
                        'breadcrumbs' => [
                            ['label' => 'Dashboard', 'url' => route('dashboard')],
                            ['label' => 'User']
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
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" wire:model.live="selectAll" wire:click="toggleSelectAll">
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($users as $index => $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <input type="checkbox" wire:model.live="selectedIds" value="{{ $user->id }}">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $users->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $user->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $user->role }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <flux:button class="text-blue-600 hover:text-blue-900" wire:click="openEditModal({{ $user->id }})" size="xs">
                                Edit
                            </flux:button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $users->links() }}
    </div>
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-opacity-50">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl">
                <div class="border-b px-6 py-4">
                    <h3 class="text-xl font-semibold text-gray-800">{{ $modalTitle }}</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 px-6 py-6">
                    <div>
                        <flux:input
                            type="text"
                            label="Nama Pengguna"
                            wire:model="fields.name"
                            id="name"
                        />
                    </div>

                    <div>
                        <flux:input
                            type="email"
                            label="Email"
                            wire:model="fields.email"
                            id="email"
                        />
                    </div>

                    <div>
                        <flux:input
                            type="password"
                            label="Password Baru"
                            wire:model="fields.password"
                            id="password"
                        />
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select wire:model="fields.role" id="role" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                            <option value="">Pilih Role</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 text-right">
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
@endif

</div>
</div>
