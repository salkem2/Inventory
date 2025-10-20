<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Lokasi</th>
            <th>Kondisi</th>
            <th>Status</th>
            <th>Sumber Dana</th>
            <th>Tipe</th>
            <th>Jumlah</th>
            <th>Tgl. Pengadaan</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($barangs as $index => $barang)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $barang->kode_barang }}</td>
                <td>{{ $barang->nama_barang }}</td>
                <td>{{ $barang->kategori->nama_kategori }}</td>
                <td>{{ $barang->lokasi->nama_lokasi }}</td>
                <td>
                    @php
                        $kondisiText = $barang->kondisi_dominan;
                        if ($barang->is_per_unit && $barang->detailBarangs->isEmpty()) {
                            $kondisiText = '–';
                        }
                    @endphp
                    {{ $kondisiText }}
                </td>
                <td>{{ $barang->status_pinjam }}</td>
                <td>{{ $barang->sumber_dana }}</td>
                <td>{{ $barang->is_per_unit ? 'Per Unit' : 'Tidak Per Unit' }}</td>
                <td>
                    @if ($barang->is_per_unit)
                        {{ $barang->detailBarangs->count() }} Unit
                    @else
                        {{ $barang->jumlah }} {{ $barang->satuan }}
                    @endif
                </td>
                <td>{{ date('d-m-Y', strtotime($barang->tanggal_pengadaan)) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="11" style="text-align: center;">Tidak ada data.</td>
            </tr>
        @endforelse
    </tbody>
</table>