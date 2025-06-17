
<flux:button variant="primary" size="xs" wire:click="openCreateModal">Create New</flux:button>
@if(count($selectedIds) > 0)
    <flux:button variant="danger" size="xs" wire:click="confirmDeleteSelected">
        Delete Selected ({{ count($selectedIds) }})
    </flux:button>
@endif


