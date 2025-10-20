<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailBarang;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DetailBarangController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission:manage barang', except: ['destroy']),
            new Middleware('permission:delete barang', only: ['destroy']),
        ];
    }

    /**
     * Halaman kelola unit untuk barang tertentu
     */
    public function index(Barang $barang)
    {
        // Load relasi dengan fresh data
        $barang->load(['detailBarangs' => function($query) {
            $query->orderBy('sub_kode', 'asc');
        }, 'detailBarangs.peminjamanAktif', 'kategori', 'lokasi']);
        
        $lokasis = \App\Models\Lokasi::all();
        
        return view('barang.kelola-unit', compact('barang', 'lokasis'));
    }

    /**
     * Tambah unit baru
     */
    public function store(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'sub_kode' => 'required|string|max:50',
            'kondisi' => 'required|in:Baik,Rusak ringan,Rusak berat',
            'status' => 'required|in:Tersedia,Dipinjam,Rusak',
            'keterangan' => 'nullable|string',
        ]);

        $validated['lokasi_id'] = $barang->lokasi_id;
        
        // Format kode unit: CP103-002
        $kodeUnit = preg_replace('/[^0-9]/', '', $validated['sub_kode']);
        $validated['sub_kode'] = $barang->kode_barang . '-' . str_pad($kodeUnit, 3, '0', STR_PAD_LEFT);
        
        // Pastikan unik
        if (DetailBarang::where('sub_kode', $validated['sub_kode'])->exists()) {
            return redirect()->back()
                ->withErrors(['sub_kode' => 'Kode unit sudah ada.'])
                ->withInput();
        }
        
        $barang->detailBarangs()->create($validated);

        return redirect()->route('barang.units.index', $barang)
            ->with('success', 'Unit barang berhasil ditambahkan.');
    }

    /**
     * Update unit
     */
    public function update(Request $request, Barang $barang, DetailBarang $detailBarang)
    {
        // Pastikan detail barang ini milik barang yang dipilih
        if ($detailBarang->barang_id !== $barang->id) {
            return back()->with('error', 'Unit tidak ditemukan.');
        }

        $validated = $request->validate([
            'sub_kode' => 'required|string|max:50|unique:detail_barangs,sub_kode,' . $detailBarang->id,
            'kondisi' => 'required|in:Baik,Rusak ringan,Rusak berat',
            'status' => 'required|in:Tersedia,Rusak',
            'keterangan' => 'nullable|string',
        ]);

        // Update data
        $detailBarang->update($validated);

        // Refresh relasi barang agar data terbaru
        $barang->refresh();
        $barang->load(['detailBarangs' => function($query) {
            $query->orderBy('sub_kode', 'asc');
        }]);

        return redirect()->route('barang.units.index', $barang)
            ->with('success', 'Unit barang berhasil diperbarui.');
    }

    /**
     * Hapus unit
     */
    public function destroy(Barang $barang, DetailBarang $detailBarang)
    {
        // Pastikan detail barang ini milik barang yang dipilih
        if ($detailBarang->barang_id !== $barang->id) {
            return back()->with('error', 'Unit tidak ditemukan.');
        }

        // Cek apakah unit sedang dipinjam
        if ($detailBarang->status === 'Dipinjam') {
            return back()->with('error', 'Unit sedang dipinjam, tidak dapat dihapus.');
        }

        $detailBarang->delete();

        // Refresh data barang
        $barang->refresh();

        return back()->with('success', 'Unit barang berhasil dihapus.');
    }

    /**
     * Store bulk units (tambah banyak unit sekaligus).
     */
    public function storeBulk(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'jumlah_unit' => 'required|integer|min:1|max:500',
            'kode_mulai' => 'required|string|max:10',
            'kondisi' => 'required|in:Baik,Rusak ringan,Rusak berat',
            'status' => 'required|in:Tersedia,Rusak',
        ]);

        $jumlahUnit = $validated['jumlah_unit'];
        $kodeMulai = intval($validated['kode_mulai']);
        $kodeBarang = $barang->kode_barang;
        
        $unitsToCreate = [];
        
        for ($i = 0; $i < $jumlahUnit; $i++) {
            $nomorUrut = str_pad($kodeMulai + $i, 3, '0', STR_PAD_LEFT);
            $subKode = $kodeBarang . '-' . $nomorUrut;
            
            // Cek apakah kode sudah ada
            if (DetailBarang::where('sub_kode', $subKode)->exists()) {
                return redirect()->back()
                    ->withErrors(['kode_mulai' => "Kode unit $subKode sudah ada. Gunakan nomor mulai yang berbeda."])
                    ->withInput();
            }
            
            $unitsToCreate[] = [
                'barang_id' => $barang->id,
                'lokasi_id' => $barang->lokasi_id,
                'sub_kode' => $subKode,
                'kondisi' => $validated['kondisi'],
                'status' => $validated['status'],
                'keterangan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // Insert semua unit sekaligus
        DetailBarang::insert($unitsToCreate);
        
        // Refresh data barang
        $barang->refresh();
        
        return redirect()->route('barang.units.index', $barang)
            ->with('success', "Berhasil menambahkan $jumlahUnit unit baru.");
    }

    /**
     * Display the specified unit detail.
     */
    public function show(Barang $barang, DetailBarang $detailBarang)
    {
        // Pastikan unit ini milik barang yang dipilih
        if ($detailBarang->barang_id !== $barang->id) {
            return redirect()->route('barang.units.index', $barang)
                ->with('error', 'Unit tidak ditemukan.');
        }
        
        // Load relasi yang dibutuhkan
        $detailBarang->load(['barang.kategori', 'barang.lokasi', 'peminjamanAktif']);
        
        return view('barang.show-unit', compact('barang', 'detailBarang'));
    }
}