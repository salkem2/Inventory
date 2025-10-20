@csrf
<div class="row mb-3">
    <div class="col-md-6">
        <x-form-input label="Kode Barang" name="kode_barang" :value="$barang->kode_barang" />
    </div>

    <div class="col-md-6">
        <x-form-input label="Nama Barang" name="nama_barang" :value="$barang->nama_barang" />
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <x-form-select label="Kategori" name="kategori_id" :value="$barang->kategori_id"
            :option-data="$kategori" option-label="nama_kategori" option-value="id" />
    </div>

    <div class="col-md-6">
        <x-form-select label="Lokasi" name="lokasi_id" :value="$barang->lokasi_id"
            :option-data="$lokasi" option-label="nama_lokasi" option-value="id" />
    </div>
</div>


{{-- Sumber Dana & Status Pinjam --}}
<div class="row mb-3">
    <div class="col-md-6">
        @php
            $sumberDana = [
                ['sumber' => 'Pemerintah'],
                ['sumber' => 'Swadaya'],
                ['sumber' => 'Donatur']
            ];
        @endphp
        <x-form-select label="Sumber Dana" name="sumber_dana" :value="$barang->sumber_dana" :option-data="$sumberDana"
            option-label="sumber" option-value="sumber" />
    </div>
    <div class="col-md-6">
        @php
            $statusPinjam = [
                ['status' => 'Dapat Dipinjam'],
                ['status' => 'Tidak Dapat Dipinjam']
            ];
        @endphp
        <x-form-select label="Status Pinjam" name="status_pinjam" :value="$barang->status_pinjam" :option-data="$statusPinjam"
            option-label="status" option-value="status" />
    </div>
</div>

{{-- Toggle Per Unit / Tidak Per Unit --}}
<div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label fw-bold">Tipe Barang</label>
        <div class="btn-group w-100" role="group">
            <input type="radio" class="btn-check" name="is_per_unit" id="perUnit" value="1" 
                {{ (old('is_per_unit', $barang->is_per_unit ?? 0) == 1) ? 'checked' : '' }}>
            <label class="btn btn-outline-primary" for="perUnit">
                <i class="bi bi-box-seam"></i> Per Unit
            </label>

            <input type="radio" class="btn-check" name="is_per_unit" id="tidakPerUnit" value="0" 
                {{ (old('is_per_unit', $barang->is_per_unit ?? 0) == 0) ? 'checked' : '' }}>
            <label class="btn btn-outline-primary" for="tidakPerUnit">
                <i class="bi bi-stack"></i> Tidak Per Unit
            </label>
        </div>
        @error('is_per_unit')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Jumlah & Kondisi (hanya untuk Tidak Per Unit) --}}
<div class="row mb-3" id="notPerUnitSection">
    <div class="col-md-6">
        <x-form-input label="Jumlah" name="jumlah" :value="old('jumlah', (old('is_per_unit', $barang->is_per_unit ?? 0) == 0 ? $barang->jumlah : null))" type="number" 
            placeholder="Masukkan jumlah barang" />
        @if ($errors->has('jumlah'))
            <div class="invalid-feedback d-block" id="jumlahError">
                {{ $errors->first('jumlah') }}
            </div>
        @endif
    </div>

    <div class="col-md-6" id="kondisiWrapper">
        @php
            $kondisi = [['kondisi' => 'Baik'], ['kondisi' => 'Rusak ringan'], ['kondisi' => 'Rusak berat']];
        @endphp
        <x-form-select label="Kondisi" name="kondisi" 
            :value="old('kondisi', (old('is_per_unit', $barang->is_per_unit ?? 0) == 0 ? $barang->kondisi : null))" 
            :option-data="$kondisi"
            option-label="kondisi" 
            option-value="kondisi" />
        @error('kondisi')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>



{{-- Satuan & Tanggal (untuk semua tipe, tapi label berubah) --}}
<div class="row mb-3">
    <div class="col-md-6">
        <x-form-input label="Satuan" name="satuan" :value="$barang->satuan" 
            placeholder="misal: pcs, buah, unit, box, etc" />
    </div>

    <div class="col-md-6">
        @php
            $tanggal = $barang->tanggal_pengadaan
                ? date('Y-m-d', strtotime($barang->tanggal_pengadaan))
                : null;
        @endphp
        <x-form-input label="Tanggal Pengadaan" name="tanggal_pengadaan" type="date" :value="$tanggal" />
    </div>
</div>

{{-- Info untuk Per Unit --}}
<div class="alert alert-info" id="perUnitInfo" style="display: none;">
    <i class="bi bi-info-circle"></i> 
    <strong>Info Per Unit:</strong> Jumlah barang akan otomatis dihitung berdasarkan unit yang ditambahkan di menu <strong>"Kelola Unit"</strong>. Setiap unit bisa memiliki kondisi, kode, dan status tersendiri.
</div>

{{-- Info untuk Tidak Per Unit --}}
<div class="alert alert-info" id="notPerUnitInfo">
    <i class="bi bi-info-circle"></i> 
    <strong>Info Tidak Per Unit:</strong> Barang disimpan berdasarkan jumlah total tanpa perlu membuat unit individual.
</div>

<div class="row mb-3">
    <x-form-input label="Gambar Barang" name="gambar" type="file" />
</div>

<div class="mt-4">
    <x-primary-button>
        {{ isset($update) ? __('Update') : __('Simpan') }}
    </x-primary-button>
    
    <x-tombol-kembali :href="route('barang.index')" />
</div>

<script>
// Simpan nilai kondisi awal
let lastKondisi = document.querySelector('select[name="kondisi"]')?.value || '';

// Fungsi untuk mengelola kondisi
function toggleKondisi(isPerUnit) {
    const kondisiSelect = document.querySelector('select[name="kondisi"]');
    if (!kondisiSelect) return;
    
    if (isPerUnit) {
        lastKondisi = kondisiSelect.value; // Simpan nilai sebelum dikosongkan
        kondisiSelect.value = '';
        kondisiSelect.setAttribute('disabled', 'disabled');
    } else {
        kondisiSelect.removeAttribute('disabled');
        if (lastKondisi) {
            kondisiSelect.value = lastKondisi; // Kembalikan nilai terakhir
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const perUnitRadio = document.getElementById('perUnit');
    const tidakPerUnitRadio = document.getElementById('tidakPerUnit');
    const notPerUnitSection = document.getElementById('notPerUnitSection');
    const perUnitInfo = document.getElementById('perUnitInfo');
    const notPerUnitInfo = document.getElementById('notPerUnitInfo');
    const jumlahError = document.getElementById('jumlahError');

    function toggleFields() {
        const isPerUnit = perUnitRadio.checked;

        // Toggle visibility section
        if (notPerUnitSection) {
            notPerUnitSection.style.display = isPerUnit ? 'none' : 'flex';
        }
        if (perUnitInfo) {
            perUnitInfo.style.display = isPerUnit ? 'block' : 'none';
        }
        if (notPerUnitInfo) {
            notPerUnitInfo.style.display = isPerUnit ? 'none' : 'block';
        }

    // Set required attribute dan visibility untuk Jumlah & Kondisi
        const jumlahInput = document.querySelector('input[name="jumlah"]');
        const kondisiSelect = document.querySelector('select[name="kondisi"]');
    const kondisiWrapper = document.getElementById('kondisiWrapper');

        if (isPerUnit) {
            // Per Unit: tidak perlu jumlah & kondisi
            if (jumlahInput) {
                jumlahInput.removeAttribute('required');
                jumlahInput.value = ''; // Reset nilai jumlah
            }
            // Sembunyikan error jumlah jika ada
            if (jumlahError) {
                jumlahError.style.display = 'none';
            }
            // Kelola kondisi untuk Per Unit
            toggleKondisi(true);
            if (kondisiWrapper) {
                kondisiWrapper.style.display = 'none';
                kondisiWrapper.setAttribute('aria-hidden', 'true');
            }
        } else {
            // Tidak Per Unit: wajib isi jumlah & kondisi
            if (jumlahInput) {
                jumlahInput.setAttribute('required', 'required');
            }
            // Tampilkan error jumlah jika ada
            if (jumlahError) {
                jumlahError.style.display = 'block';
            }
            // Kelola kondisi untuk Tidak Per Unit
            toggleKondisi(false);
            if (kondisiWrapper) {
                kondisiWrapper.style.display = '';
                kondisiWrapper.removeAttribute('aria-hidden');
            }
        }
    }

    if (perUnitRadio) perUnitRadio.addEventListener('change', toggleFields);
    if (tidakPerUnitRadio) tidakPerUnitRadio.addEventListener('change', toggleFields);

    toggleFields();
});
</script>