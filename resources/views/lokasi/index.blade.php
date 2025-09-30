<x-main-layout :title-page="('Lokasi')">
    <div class="card">
        <div class="card-body">
            @include('lokasi.partials.toolbar')
        </div>

        <x-notif-alert class="mt-4" />

        @include('lokasi.partials.list-kategori')

        <div class="card-body">
            {{ $lokasis->links() }}
        </div>
    </div>
</x-main-layout>
