<style>
    .info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 24px;
        color: #fff;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        margin-bottom: 24px;
    }
    
    .info-card h5 {
        font-weight: 700;
        margin-bottom: 12px;
    }
    
    .info-card .info-detail {
        display: flex;
        gap: 24px;
        flex-wrap: wrap;
    }
    
    .info-card .info-item {
        display: flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.15);
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 14px;
    }
    
    .form-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 24px;
    }
    
    .form-card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 16px 20px;
        color: #fff;
    }
    
    .form-card-header h6 {
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .nav-tabs-modern {
        border-bottom: 2px solid #e9ecef;
        padding: 0 20px;
        background: #f8f9fa;
    }
    
    .nav-tabs-modern .nav-link {
        border: none;
        padding: 12px 20px;
        color: #6c757d;
        font-weight: 500;
        border-bottom: 3px solid transparent;
        transition: all 0.2s ease;
    }
    
    .nav-tabs-modern .nav-link:hover {
        color: #667eea;
        background: transparent;
    }
    
    .nav-tabs-modern .nav-link.active {
        color: #667eea;
        background: transparent;
        border-bottom-color: #667eea;
    }
    
    .form-content {
        padding: 24px;
    }
    
    .btn-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: #fff;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    
    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        color: #fff;
    }
    
    .info-alert {
        background: linear-gradient(90deg, #e7f1ff 0%, #f0f7ff 100%);
        border-left: 4px solid #667eea;
        padding: 16px;
        border-radius: 8px;
        margin-top: 16px;
    }
    
    .list-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .list-card-header {
        background: #f8f9fa;
        padding: 16px 20px;
        border-bottom: 2px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .list-card-header h6 {
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .stat-badges {
        display: flex;
        gap: 8px;
    }
    
    .stat-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }
    
    .table-modern {
        margin: 0;
    }
    
    .table-modern thead {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }
    
    .table-modern thead th {
        font-weight: 600;
        font-size: 13px;
        color: #495057;
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
    }
    
    .badge-clean {
        padding: 5px 12px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 12px;
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
    
    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }
    
    .empty-state i {
        font-size: 64px;
        color: #dee2e6;
        margin-bottom: 16px;
    }
    
    .modal-header-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
    }
    
    .modal-header-modern .btn-close {
        filter: brightness(0) invert(1);
    }
</style>

<x-main-layout title-page="Kelola Unit Barang">
    {{-- Info Barang --}}
    <div class="info-card">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h5>
                    <i class="bi bi-box-seam"></i> {{ $barang->nama_barang }}
                </h5>
                <div class="info-detail">
                    <div class="info-item">
                        <i class="bi bi-upc-scan"></i>
                        <strong>{{ $barang->kode_barang }}</strong>
                    </div>
                    <div class="info-item">
                        <i class="bi bi-tag"></i>
                        {{ $barang->kategori->nama_kategori }}
                    </div>
                    <div class="info-item">
                        <i class="bi bi-geo-alt"></i>
                        {{ $barang->lokasi->nama_lokasi }}
                    </div>
                </div>
            </div>
            <x-tombol-kembali :href="route('barang.index')" class="btn-light" />
        </div>
    </div>

    {{-- Alert Messages --}}
    <x-notif-alert class="mb-4" />

    {{-- Form Tambah Unit --}}
    <div class="form-card">
        <div class="form-card-header">
            <h6><i class="bi bi-plus-circle"></i> Tambah Unit Baru</h6>
        </div>
        
        {{-- Tabs --}}
        <ul class="nav nav-tabs-modern" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="single-tab" data-bs-toggle="tab" 
                    data-bs-target="#single-unit" type="button" role="tab">
                    <i class="bi bi-file-plus"></i> Tambah 1 Unit
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="bulk-tab" data-bs-toggle="tab" 
                    data-bs-target="#bulk-unit" type="button" role="tab">
                    <i class="bi bi-files"></i> Tambah Banyak Unit
                </button>
            </li>
        </ul>

        {{-- Tab Content --}}
        <div class="tab-content">
            {{-- Single Unit Form --}}
            <div class="tab-pane fade show active" id="single-unit" role="tabpanel">
                <div class="form-content">
                    <form method="POST" action="{{ route('barang.units.store', $barang) }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-3">
                                <x-form-input label="Kode Unit" name="sub_kode" 
                                    placeholder="Contoh: {{ $barang->kode_barang }}-001" required />
                                <small class="text-muted">Format: {{ $barang->kode_barang }}-XXX</small>
                            </div>
                            <div class="col-md-3">
                                @php
                                    $kondisiOptions = [
                                        ['kondisi' => 'Baik'], 
                                        ['kondisi' => 'Rusak ringan'], 
                                        ['kondisi' => 'Rusak berat']
                                    ];
                                @endphp
                                <x-form-select label="Kondisi" name="kondisi" :option-data="$kondisiOptions" 
                                    option-label="kondisi" option-value="kondisi" value="Baik" required />
                            </div>
                            <div class="col-md-3">
                                @php
                                    $statusOptions = [
                                        ['status' => 'Tersedia'], 
                                        ['status' => 'Rusak']
                                    ];
                                @endphp
                                <x-form-select label="Status" name="status" :option-data="$statusOptions" 
                                    option-label="status" option-value="status" value="Tersedia" required />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Keterangan</label>
                                <div class="input-group">
                                    <input type="text" name="keterangan" class="form-control" 
                                        placeholder="Keterangan...">
                                    <button type="submit" class="btn btn-gradient">
                                        <i class="bi bi-save"></i> Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Bulk Unit Form --}}
            <div class="tab-pane fade" id="bulk-unit" role="tabpanel">
                <div class="form-content">
                    <form method="POST" action="{{ route('barang.units.storeBulk', $barang) }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Jumlah Unit <span class="text-danger">*</span></label>
                                <input type="number" name="jumlah_unit" class="form-control" 
                                    placeholder="Contoh: 50" min="1" max="500" required>
                                <small class="text-muted">Maksimal 500 unit</small>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kode Mulai <span class="text-danger">*</span></label>
                                <input type="text" name="kode_mulai" class="form-control" 
                                    placeholder="Contoh: 001" required>
                                <small class="text-muted">Nomor awal</small>
                            </div>
                            <div class="col-md-2">
                                @php
                                    $kondisiOptions = [
                                        ['kondisi' => 'Baik'], 
                                        ['kondisi' => 'Rusak ringan'], 
                                        ['kondisi' => 'Rusak berat']
                                    ];
                                @endphp
                                <x-form-select label="Kondisi" name="kondisi" :option-data="$kondisiOptions" 
                                    option-label="kondisi" option-value="kondisi" value="Baik" required />
                            </div>
                            <div class="col-md-2">
                                @php
                                    $statusOptions = [
                                        ['status' => 'Tersedia'], 
                                        ['status' => 'Rusak']
                                    ];
                                @endphp
                                <x-form-select label="Status" name="status" :option-data="$statusOptions" 
                                    option-label="status" option-value="status" value="Tersedia" required />
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-gradient w-100">
                                    <i class="bi bi-plus-circle"></i> Tambah
                                </button>
                            </div>
                        </div>
                        
                        <div class="info-alert">
                            <i class="bi bi-info-circle"></i> 
                            <strong>Contoh:</strong> Jika jumlah = 50 dan kode mulai = 004, maka akan dibuat: 
                            <code>{{ $barang->kode_barang }}-004</code> sampai 
                            <code>{{ $barang->kode_barang }}-053</code>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Unit --}}
    <div class="list-card">
        <div class="list-card-header">
            <h6>
                <i class="bi bi-list-ul"></i> Daftar Unit ({{ $barang->detailBarangs->count() }})
            </h6>
            <div class="stat-badges">
                @php
                    $tersedia = $barang->detailBarangs->where('status', 'Tersedia')->count();
                    $dipinjam = $barang->detailBarangs->where('status', 'Dipinjam')->count();
                    $rusak = $barang->detailBarangs->where('status', 'Rusak')->count();
                @endphp
                <span class="stat-badge bg-success text-white">{{ $tersedia }} Tersedia</span>
                <span class="stat-badge bg-warning text-dark">{{ $dipinjam }} Dipinjam</span>
                <span class="stat-badge bg-danger text-white">{{ $rusak }} Rusak</span>
            </div>
        </div>

        <div class="p-0">
            @if ($barang->detailBarangs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Kode Unit</th>
                                <th width="12%">Kondisi</th>
                                <th width="12%">Status</th>
                                <th width="25%">Keterangan</th>
                                <th width="20%">Info Peminjaman</th>
                                <th width="11%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barang->detailBarangs as $index => $unit)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-dark">{{ $unit->sub_kode }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = 'bg-success';
                                            if ($unit->kondisi == 'Rusak ringan') $badgeClass = 'bg-warning text-dark';
                                            if ($unit->kondisi == 'Rusak berat') $badgeClass = 'bg-danger';
                                        @endphp
                                        <span class="badge badge-clean {{ $badgeClass }}">{{ $unit->kondisi }}</span>
                                    </td>
                                    <td>
                                        @if ($unit->status === 'Tersedia')
                                            <span class="badge badge-clean bg-success">
                                                <i class="bi bi-check-circle"></i> Tersedia
                                            </span>
                                        @elseif ($unit->status === 'Dipinjam')
                                            <span class="badge badge-clean bg-warning text-dark">
                                                <i class="bi bi-clock"></i> Dipinjam
                                            </span>
                                        @else
                                            <span class="badge badge-clean bg-danger">
                                                <i class="bi bi-x-circle"></i> Rusak
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $unit->keterangan ?? '-' }}</small>
                                    </td>
                                    <td>
                                        @if ($unit->peminjamanAktif)
                                            <div class="d-flex flex-column gap-1">
                                                <small>
                                                    <i class="bi bi-person-fill text-primary"></i> 
                                                    <strong>{{ $unit->peminjamanAktif->nama_peminjam }}</strong>
                                                </small>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar"></i> 
                                                    {{ $unit->peminjamanAktif->tanggal_pinjam->format('d/m/Y') }}
                                                </small>
                                            </div>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('barang.units.show', [$barang, $unit]) }}" 
                                                class="btn btn-action btn-info" title="Detail">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            
                                            <button type="button" class="btn btn-action btn-warning" 
                                                data-bs-toggle="modal" data-bs-target="#editModal{{ $unit->id }}" title="Edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>
                                            
                                            @can('delete barang')
                                                @if ($unit->status !== 'Dipinjam')
                                                    <button type="button" class="btn btn-action btn-danger" 
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                        data-url="{{ route('barang.units.destroy', [$barang, $unit]) }}"
                                                        title="Hapus">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>

                                {{-- Modal Edit --}}
                                <div class="modal fade" id="editModal{{ $unit->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('barang.units.update', [$barang, $unit]) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header modal-header-modern">
                                                    <h5 class="modal-title">Edit Unit: {{ $unit->sub_kode }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Kode Unit <span class="text-danger">*</span></label>
                                                        <input type="text" name="sub_kode" class="form-control" 
                                                            value="{{ $unit->sub_kode }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Kondisi <span class="text-danger">*</span></label>
                                                        <select name="kondisi" class="form-select" required>
                                                            <option value="Baik" {{ $unit->kondisi == 'Baik' ? 'selected' : '' }}>Baik</option>
                                                            <option value="Rusak ringan" {{ $unit->kondisi == 'Rusak ringan' ? 'selected' : '' }}>Rusak ringan</option>
                                                            <option value="Rusak berat" {{ $unit->kondisi == 'Rusak berat' ? 'selected' : '' }}>Rusak berat</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Status <span class="text-danger">*</span></label>
                                                        <select name="status" class="form-select" required {{ $unit->status == 'Dipinjam' ? 'disabled' : '' }}>
                                                            <option value="Tersedia" {{ $unit->status == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                                                            @if($unit->status == 'Dipinjam')
                                                                <option value="Dipinjam" selected>Dipinjam (Tidak dapat diubah)</option>
                                                            @endif
                                                            <option value="Rusak" {{ $unit->status == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                                                        </select>
                                                        @if($unit->status == 'Dipinjam')
                                                            <input type="hidden" name="status" value="Dipinjam">
                                                            <small class="text-muted">Status tidak dapat diubah saat unit sedang dipinjam</small>
                                                        @endif
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Keterangan</label>
                                                        <textarea name="keterangan" class="form-control" rows="3">{{ $unit->keterangan }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-gradient">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h5 class="text-muted mb-2">Belum Ada Unit</h5>
                    <p class="text-muted small mb-0">Tambahkan unit baru menggunakan form di atas</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Modal Delete --}}
    <x-modal-delete />
</x-main-layout>