<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $jumlahBarang   = Barang::count();
        $jumlahKategori = Kategori::count();
        $jumlahLokasi   = Lokasi::count();
        $jumlahUser     = User::count();

        $kondisiBaik      = Barang::where('kondisi', 'Baik')->count();
        $kondisiRusakRingan = Barang::where('kondisi', 'Rusak Ringan')->count();
        $kondisiRusakBerat  = Barang::where('kondisi', 'Rusak Berat')->count();

        $barangTerbaru = Barang::with(['kategori', 'lokasi'])
            ->latest()
            ->take(5)
            ->get();

        // Data chart history peminjaman 30 hari terakhir
        $startDate = now()->subDays(29)->startOfDay();
        $labels = [];
        $data = [];
        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->copy()->addDays($i);
            $labels[] = $date->format('d/m');
            $data[] = \App\Models\Peminjaman::whereDate('tanggal_pinjam', $date->format('Y-m-d'))->count();
        }

        return view('dashboard', compact(
            'jumlahBarang',
            'jumlahKategori',
            'jumlahLokasi',
            'jumlahUser',
            'kondisiBaik',
            'kondisiRusakRingan',
            'kondisiRusakBerat',
            'barangTerbaru',
            'labels',
            'data'
        ));
    }
}

