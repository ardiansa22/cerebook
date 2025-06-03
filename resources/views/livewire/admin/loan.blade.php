<div>
<div>
@include('layouts.component.swalert')
@include('layouts.component.confirmdelete ')

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Genres</h2>
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
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" wire:model.live="selectAll" wire:click="toggleSelectAll">
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Transaksi</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Peminjam</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Buku</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Peminjaman</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pengembalian</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Buku</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Denda</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($loans as $index => $loan)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <input type="checkbox" wire:model.live="selectedIds" value="{{ $loan->id }}">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $loans->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                           TSAJA {{ $loans->firstItem() + $index }}201EJ
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $loan->user->id}}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <ul class="list-disc ml-4">
                                    @foreach ($loan->items as $detail)
                                        <li>{{ $detail->book->title }}</li>
                                    @endforeach
                                </ul>   
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $loan->rental_date->format('d-m-Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $loan->return_date->format('d-m-Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            @switch($loan->status)
                                @case('rented')
                                    <flux:badge color="blue">Rented</flux:badge>
                                    @break

                                @case('returned')
                                    <flux:badge color="green">Returned</flux:badge>
                                    @break

                                @case('late')
                                    <flux:badge color="yellow">Late</flux:badge>
                                    @break

                                @case('cancelled')
                                    <flux:badge color="red">Cancelled</flux:badge>
                                    @break

                                @default
                                    <flux:badge color="gray">{{ ucfirst($loan->status) }}</flux:badge>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $loan->items->count() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            @if ($loan->fine)
                                Rp {{ number_format($loan->fine->amount, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button
                            wire:click="openEditModal({{ $loan->id }})"
                            class="text-blue-600 hover:text-blue-900 mr-3"
                        >
                            <svg class="h-5 w-5 inline-block mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M13.586 3.586a2 2 0 012.828 2.828l-7.93 7.931a2 2 0 01-2.828 0l-2.828-2.828a2 2 0 010-2.828l7.93-7.931a2 2 0 012.828 0zM1.414 10.586l7.93-7.93a2 2 0 012.828 2.828l-7.93 7.931a2 2 0 01-2.828 0z" />
                            </svg>
                        </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $loans->links() }}
    </div>
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            {{ $modalTitle }}
                        </h3>

                        <div class="space-y-4">
                            <div>
                                    <flux:input type="email" label="Genre" wire:model="fields.name"
                                    id="name"/>
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
</div>
</div>
