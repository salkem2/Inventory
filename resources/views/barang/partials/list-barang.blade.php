<style>
    .table-modern {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }
    
    .table-modern thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .table-modern thead th {
        color: #fff;
        font-weight: 600;
        font-size: 13px;
        padding: 14px 12px;
        border: none;
    }
    
    .table-modern tbody tr {
        border-bottom: 1px solid #f0f0f0;
        transition: background 0.2s ease;
    }
    
    .table-modern tbody tr:hover {
        background: #f8f9ff;
    }
    
    .table-modern tbody td {
        padding: 12px;
        vertical-align: middle;
        font-size: 14px;
    }
    
    .badge-clean {
        padding: 5px 12px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 12px;
    }
    
    .code-text {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 700;
    }
    
    .btn-action {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .unit-toggle {
        background: linear-gradient(90deg, #f8f9ff 0%, #e7f1ff 100%);
        border: none;
        padding: 10px 12px;
        font-size: 13px;
        font-weight: 500;
        color: #495057;
        transition: all 0.2s ease;
    }
    
    .unit-toggle:hover {
        background: linear-gradient(90deg, #e7f1ff 0%, #d0e7ff 100%);
    }
    
    .unit-detail {
        background: #fafbfc;
        padding: 20px;
        border-left: 3px solid #667eea;
    }
    
    .unit-header {
        font-weight: 600;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e9ecef;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .count-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        padding: 3px 10px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 13px;
    }
    
    .empty-state {
        padding: 50px 20px;
        text-align: center;
    }
    
    .empty-state i {
        font-size: 56px;
        color: #dee2e6;
        margin-bottom: 12px;
    }
</style>

<x-tabel-list class="table-modern">
    <x-slot name="header">
        <tr>
            <th width="40">#</th>
            <th width="100">Kode</th>
            <th>Nama Barang</th>
            <th width="120">Kategori</th>
            <th width="100">Lokasi</th>
            <th width="90">Kondisi</th>
            <th width="110">Status</th>
            <th width="100">Sumber</th>
            <th width="80">Tipe</th>
            <th width="70">Jumlah</th>
            <th width="120" class="text-end">Aksi</th>
        </tr>
    </x-slot>

    @forelse ($barangs as $index => $barang)
        <tr>
            <td>{{ $barangs->firstItem() + $index }}</td>
            <td>
                <span class="code-text">{{ $barang->kode_barang }}</span>
            </td>
            <td><strong>{{ $barang->nama_barang }}</strong></td>
            <td>
                <small class="text-muted">
                    <i class="bi bi-tag"></i> {{ $barang->kategori->nama_kategori }}
                </small>
            </td>
            <td>
                <small class="text-muted">
                    <i class="bi bi-geo-alt"></i> {{ $barang->lokasi->nama_lokasi }}
                </small>
            </td>
            <td>
                @php
                    $badgeClass = 'bg-success';
                    $kondisiText = $barang->kondisi_dominan;
                @endphp
                @if($barang->is_per_unit && $barang->detailBarangs->isEmpty())
                    <span class="badge badge-clean bg-secondary">-</span>
                @elseif($kondisiText == 'Rusak ringan')
                    <span class="badge badge-clean bg-warning text-dark">{{ $kondisiText }}</span>
                @elseif($kondisiText == 'Rusak berat')
                    <span class="badge badge-clean bg-danger">{{ $kondisiText }}</span>
                @else
                    <span class="badge badge-clean bg-success">{{ $kondisiText }}</span>
                @endif
            </td>
            <td>
                <span class="badge badge-clean {{ $barang->status_pinjam == 'Dapat Dipinjam' ? 'bg-success' : 'bg-secondary' }}">
                    {{ $barang->status_pinjam == 'Dapat Dipinjam' ? 'Bisa Dipinjam' : 'Tidak Bisa' }}
                </span>
            </td>
            <td>
                <small class="text-muted">{{ $barang->sumber_dana }}</small>
            </td>
            <td>
                @if ($barang->is_per_unit)
                    <span class="badge badge-clean bg-primary">
                        <i class="bi bi-box-seam"></i> Per Unit
                    </span>
                @else
                    <span class="badge badge-clean bg-secondary">
                        <i class="bi bi-stack"></i> Non-Unit
                    </span>
                @endif
            </td>
            <td>
                @if ($barang->is_per_unit)
                    <span class="count-badge">{{ $barang->jumlah_unit }}</span>
                @else
                    <span class="count-badge">{{ $barang->jumlah }}</span>
                @endif
                <small class="text-muted d-block mt-1">{{ $barang->satuan }}</small>
            </td>
            <td class="text-end">
                <div class="d-flex gap-1 justify-content-end">
                    @can('manage barang')
                        @if ($barang->is_per_unit)
                            <a href="{{ route('barang.units.index', $barang->id) }}" 
                                class="btn btn-action btn-primary" title="Kelola Unit">
                                <i class="bi bi-box-seam"></i>
                            </a>
                        @else
                            <a href="{{ route('barang.show', $barang->id) }}" 
                                class="btn btn-action btn-info" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                        @endif

                        <a href="{{ route('barang.edit', $barang->id) }}" 
                            class="btn btn-action btn-warning" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                    @endcan

                    @can('delete barang')
                        <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-action btn-danger" 
                                title="Hapus" onclick="return confirm('Yakin ingin menghapus?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    @endcan
                </div>
            </td>
        </tr>

        {{-- Detail Unit --}}
        @if ($barang->is_per_unit && $barang->detailBarangs->isNotEmpty())
            @php
                $totalUnit = $barang->detailBarangs->count();
                $unitTersedia = $barang->detailBarangs->where('status', 'Tersedia')->count();
                $unitDipinjam = $barang->detailBarangs->where('status', 'Dipinjam')->count();
            @endphp
            
            <tr>
                <td colspan="11" class="p-0">
                    <button class="unit-toggle w-100 text-start" 
                        type="button" data-bs-toggle="collapse" data-bs-target="#units{{ $barang->id }}">
                        <i class="bi bi-chevron-down"></i> 
                        <strong>Detail Unit ({{ $totalUnit }})</strong> - 
                        <span class="badge badge-clean bg-success">{{ $unitTersedia }} Tersedia</span>
                        <span class="badge badge-clean bg-warning text-dark">{{ $unitDipinjam }} Dipinjam</span>
                    </button>
                </td>
            </tr>

            <tr class="collapse" id="units{{ $barang->id }}">
                <td colspan="11" class="p-0">
                    <div class="unit-detail">
                        <div class="unit-header">
                            <i class="bi bi-box-seam text-primary"></i>
                            <span>Unit - {{ $barang->nama_barang }}</span>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered bg-white">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="15%">Kode Unit</th>
                                        <th width="12%">Kondisi</th>
                                        <th width="12%">Status</th>
                                        <th width="25%">Keterangan</th>
                                        <th width="31%">Info Peminjaman</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($barang->detailBarangs->take(5) as $idx => $detail)
                                        <tr>
                                            <td>{{ $idx + 1 }}</td>
                                            <td>
                                                <span class="badge bg-dark">{{ $detail->sub_kode }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $kondisiBadge = 'bg-success';
                                                    if ($detail->kondisi == 'Rusak ringan') $kondisiBadge = 'bg-warning text-dark';
                                                    if ($detail->kondisi == 'Rusak berat') $kondisiBadge = 'bg-danger';
                                                @endphp
                                                <span class="badge badge-clean {{ $kondisiBadge }}">
                                                    {{ $detail->kondisi }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($detail->status === 'Tersedia')
                                                    <span class="badge badge-clean bg-success">
                                                        <i class="bi bi-check-circle"></i> Tersedia
                                                    </span>
                                                @elseif ($detail->status === 'Dipinjam')
                                                    <span class="badge badge-clean bg-warning text-dark">
                                                        <i class="bi bi-clock"></i> Dipinjam
                                                    </span>
                                                @else
                                                    <span class="badge badge-clean bg-danger">
                                                        <i class="bi bi-x-circle"></i> Rusak
                                                    </span>
                                                @endif
                                            </td>
                                            <td><small>{{ $detail->keterangan ?? '-' }}</small></td>
                                            <td>
                                                @php
                                                    $peminjamanAktif = $detail->peminjamanAktif;
                                                @endphp
                                                
                                                @if ($peminjamanAktif)
                                                    <div class="d-flex flex-column gap-1">
                                                        <small>
                                                            <i class="bi bi-person-fill text-primary"></i> 
                                                            <strong>{{ $peminjamanAktif->nama_peminjam }}</strong>
                                                        </small>
                                                        <small class="text-muted">
                                                            <i class="bi bi-calendar"></i> 
                                                            {{ $peminjamanAktif->tanggal_pinjam->format('d/m/Y') }}
                                                        </small>
                                                        @if ($peminjamanAktif->kontak)
                                                            <small class="text-muted">
                                                                <i class="bi bi-phone"></i> 
                                                                {{ $peminjamanAktif->kontak }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                @else
                                                    <small class="text-muted">-</small>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    
                                    @if ($totalUnit > 5)
                                        <tr class="table-light">
                                            <td colspan="6" class="text-center">
                                                <small>
                                                    Menampilkan 5 dari {{ $totalUnit }} unit. 
                                                    <a href="{{ route('barang.units.index', $barang->id) }}" class="fw-bold">
                                                        Lihat Semua <i class="bi bi-arrow-right"></i>
                                                    </a>
                                                </small>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
        @endif
    @empty
        <tr>
            <td colspan="11">
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h5 class="text-muted mt-2 mb-1">Belum Ada Data</h5>
                    <p class="text-muted small mb-0">Data barang belum tersedia dalam sistem</p>
                </div>
            </td>
        </tr>
    @endforelse
</x-tabel-list>