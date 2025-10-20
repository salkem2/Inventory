<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DetailBarang;

class BarangController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission:manage barang', except: ['index', 'show']),
            new Middleware('permission:delete barang', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $barangs = Barang::with(['kategori', 'lokasi', 'detailBarangs.peminjamanAktif'])
            ->when($search, function ($query, $search) {
                $query->where('nama_barang', 'like', '%' . $search . '%')
                    ->orWhere('kode_barang', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate()
            ->withQueryString();

        return view('barang.index', compact('barangs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = Kategori::all();
        $lokasi = Lokasi::all();
        $barang = new Barang();

        return view('barang.create', compact('barang', 'kategori', 'lokasi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|max:50|unique:barangs,kode_barang',
            'nama_barang' => 'required|string|max:150',
            'kategori_id' => 'required|exists:kategoris,id',
            'lokasi_id' => 'required|exists:lokasis,id',
            'is_per_unit' => 'required|boolean',
            'jumlah' => 'nullable|integer|min:1',
            'satuan' => 'required|string|max:20',
            'kondisi' => 'required_if:is_per_unit,false|nullable|in:Baik,Rusak ringan,Rusak berat',
            'tanggal_pengadaan' => 'required|date',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sumber_dana' => 'required|in:Pemerintah,Swadaya,Donatur',
            'status_pinjam' => 'required|in:Dapat Dipinjam,Tidak Dapat Dipinjam',
        ]);
        // Jumlah hanya wajib jika tidak per unit
        if ((int)($validated['is_per_unit']) === 0 && (empty($validated['jumlah']) || $validated['jumlah'] < 1)) {
            return back()->withErrors(['jumlah' => 'Jumlah harus diisi minimal 1 jika tipe barang Tidak Per Unit.'])->withInput();
        }

        // Jika per unit, set jumlah ke 0 dan kondisi ke 'Baik'
        if ($validated['is_per_unit'] == true || $validated['is_per_unit'] == 1) {
            $validated['jumlah'] = 0;
            $validated['kondisi'] = 'Baik';
        }

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store(null, 'gambar-barang');
        }

        Barang::create($validated);

        $message = ($validated['is_per_unit'] == true || $validated['is_per_unit'] == 1)
            ? 'Data barang berhasil ditambahkan. Gunakan tombol "Kelola Unit" untuk menambahkan detail unit.'
            : 'Data barang berhasil ditambahkan.';

        return redirect()->route('barang.index')->with('success', $message);
    }

    /**
     * Display the specified resource.
     * Untuk barang tidak per unit, tampilkan detail barang
     */
    public function show(Barang $barang)
    {
        $barang->load(['kategori', 'lokasi']);

        return view('barang.show', compact('barang'));
    }

    /**
     * Show the form for editing the existing resource.
     */
    public function edit(Barang $barang)
    {
        $kategori = Kategori::all();
        $lokasi = Lokasi::all();

        return view('barang.edit', compact('barang', 'kategori', 'lokasi'));
    }

        /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|max:50|unique:barangs,kode_barang,' . $barang->id,
            'nama_barang' => 'required|string|max:150',
            'kategori_id' => 'required|exists:kategoris,id',
            'lokasi_id' => 'required|exists:lokasis,id',
            'is_per_unit' => 'required|boolean',
            'jumlah' => 'nullable|integer|min:1',
            'satuan' => 'required|string|max:20',
            'kondisi' => 'required_if:is_per_unit,0|nullable|in:Baik,Rusak ringan,Rusak berat',
            'tanggal_pengadaan' => 'required|date',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sumber_dana' => 'required|in:Pemerintah,Swadaya,Donatur',
            'status_pinjam' => 'required|in:Dapat Dipinjam,Tidak Dapat Dipinjam'
        ]);

        // Jika per unit, set jumlah ke 0 dan kondisi ke Baik
        if ((bool)$validated['is_per_unit']) {
            $validated['jumlah'] = 0;
            $validated['kondisi'] = 'Baik';
        } else {
            // Jika tidak per unit, validasi jumlah dan kondisi
            if (empty($validated['jumlah']) || $validated['jumlah'] < 1) {
                return back()->withErrors(['jumlah' => 'Jumlah harus diisi minimal 1 jika tipe barang Tidak Per Unit.'])->withInput();
            }
            if (empty($validated['kondisi'])) {
                return back()->withErrors(['kondisi' => 'Kondisi harus dipilih jika tipe barang Tidak Per Unit.'])->withInput();
            }
        }

        if ($request->hasFile('gambar')) {
            if ($barang->gambar) {
                Storage::disk('gambar-barang')->delete($barang->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store(null, 'gambar-barang');
        }

        $barang->update($validated);

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang)
    {
        if ($barang->gambar) {
            Storage::disk('gambar-barang')->delete($barang->gambar);
        }

        // Hapus semua detail barang jika ada
        $barang->detailBarangs()->delete();

        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil dihapus.');
    }

    /**
     * Generate PDF laporan barang
     */
    public function cetakLaporan()
    {
        $barangs = Barang::with(['kategori', 'lokasi', 'detailBarangs'])->get();
        $data = [
            'title' => 'Laporan Data Barang Inventaris',
            'date' => date('d F Y'),
            'barangs' => $barangs
        ];

        $pdf = Pdf::loadView('barang.laporan', $data);
        return $pdf->stream('laporan-inventaris-barang.pdf');
    }
}