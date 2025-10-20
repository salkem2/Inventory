<x-main-layout :title-page="('Edit Barang')">
    <form class="card" action="{{ route('barang.update', $barang->id) }}" method="post" enctype="multipart/form-data">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @method('put')
            @include('barang.partials._form', ['update' => true])
        </div>
    </form>
</x-main-layout>    