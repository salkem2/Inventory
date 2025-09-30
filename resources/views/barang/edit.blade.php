<x-main-layout :title-page="('Edit Barang')">
    <form class="card" action="{{ route('barang.update', $barang->id) }}" method="post" 
    enctype="multipart/form-data">
 
        <div class="card-body">
            @method ('put')
           @include('barang.partials._form', ['update' => true])

    </form>
</x-main-layout>    