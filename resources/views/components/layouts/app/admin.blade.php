<x-layouts.app.admin-sidebar :title="$title ?? __('Admin')">
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts.app.admin-sidebar>
