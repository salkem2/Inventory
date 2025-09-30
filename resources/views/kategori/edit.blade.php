<x-main-layout :title-page="('Edit Kategori')">
    <div class="row">
        <form class="card col-lg-6" action="{{ route('kategori.update', $kategori->id) }}" method="post">
            <div class="card-body">
                @csrf
                @method('PUT')
                @include('kategori.partials._form', ['update' => true])
            </div>
        </form>
    </div>
</x-main-layout>
