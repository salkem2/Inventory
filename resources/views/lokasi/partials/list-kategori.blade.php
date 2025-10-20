<x-tabel-list>
    <x-slot name="header">
        <th>#</th>
        <th>Nama lokasi</th>
        <th>Jumlah Barang</th>
        @can('manage lokasi')
            <th>&nbsp;</th>
        @endcan
    </x-slot>

    @forelse ($lokasis as $index => $lokasi)
        <tr>
            <td>{{ $lokasis->firstItem() + $index }}</td>
            <td>{{ $lokasi->nama_lokasi }}</td>
            <td>
             
                <span class="badge rounded-pill bg-info text-dark px-3 py-2 fs-6" style="font-size:1rem;">
                    <i class="bi bi-box-seam me-1"></i>{{ $lokasi->barang->count() }}
                </span>
                <span class="text-muted" style="font-size:0.95em;"></span>
              
            </td>

            @can('manage lokasi')
                <td>
                    <x-tombol-aksi :href="route('lokasi.edit', $lokasi->id)" type="edit" />
                    <x-tombol-aksi :href="route('lokasi.destroy', $lokasi->id)" type="delete" />
                </td>
            @endcan
        </tr>
    @empty
        <tr>
            <td colspan="3" class="text-center">
                <div class="alert alert-danger">
                    Data user belum tersedia.
                </div>
            </td>
        </tr>
    @endforelse
</x-tabel-list>