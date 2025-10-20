<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\DetailBarang;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PeminjamanController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission:manage barang', except: ['destroy']),
            new Middleware('permission:delete barang', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $status = $request->status;

        $peminjamans = Peminjaman::with(['detailBarang.barang'])
            ->when($search, function ($query, $search) {
                $query->where('nama_peminjam', 'like', '%' . $search . '%')
                    ->orWhereHas('detailBarang', function ($q) use ($search) {
                        $q->where('sub_kode', 'like', '%' . $search . '%')
                            ->orWhereHas('barang', function ($q2) use ($search) {
                                $q2->where('nama_barang', 'like', '%' . $search . '%');
                            });
                    });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate()
            ->withQueryString();

        return view('peminjaman.index', compact('peminjamans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil barang yang memiliki detail barang tersedia
        $barangs = Barang::where('status_pinjam', 'Dapat Dipinjam')
            ->whereHas('detailBarangs', function ($query) {
                $query->where('status', 'Tersedia');
            })
            ->with(['detailBarangs' => function ($query) {
                $query->where('status', 'Tersedia');
            }])
            ->get();

        $peminjaman = new Peminjaman();

        return view('peminjaman.create', compact('peminjaman', 'barangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'detail_barang_id' => 'required|exists:detail_barangs,id',
            'nama_peminjam' => 'required|string|max:150',
            'kontak' => 'nullable|string|max:50',
            'keperluan' => 'nullable|string',
            'tanggal_pinjam' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        $validated['status'] = 'Dipinjam';

        // Cek apakah detail barang masih tersedia
        $detailBarang = DetailBarang::findOrFail($validated['detail_barang_id']);
        
        if ($detailBarang->status !== 'Tersedia') {
            return back()->with('error', 'Barang tidak tersedia untuk dipinjam.');
        }

        // Buat peminjaman
        Peminjaman::create($validated);

        // Update status detail barang
        $detailBarang->update(['status' => 'Dipinjam']);

        return redirect()->route('peminjaman.index')
            ->with('success', 'Data peminjaman berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['detailBarang.barang']);
        return view('peminjaman.show', compact('peminjaman'));
    }

    /**
     * Kembalikan barang yang dipinjam
     */
    public function kembalikan(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status === 'Dikembalikan') {
            return back()->with('error', 'Barang sudah dikembalikan.');
        }

        $validated = $request->validate([
            'tanggal_kembali' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        $validated['status'] = 'Dikembalikan';

        // Update peminjaman
        $peminjaman->update($validated);

        // Update status detail barang menjadi tersedia
        $peminjaman->detailBarang->update(['status' => 'Tersedia']);

        return redirect()->route('peminjaman.index')
            ->with('success', 'Barang berhasil dikembalikan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Peminjaman $peminjaman)
    {
        // Jika masih dipinjam, kembalikan status barang
        if ($peminjaman->status === 'Dipinjam') {
            $peminjaman->detailBarang->update(['status' => 'Tersedia']);
        }

        $peminjaman->delete();

        return redirect()->route('peminjaman.index')
            ->with('success', 'Data peminjaman berhasil dihapus.');
    }
}