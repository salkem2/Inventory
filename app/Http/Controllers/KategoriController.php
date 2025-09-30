<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class KategoriController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new middleware('permission:view kategori', only: ['index', 'show']),
            new middleware('permission:manage kategori', except: ['index', 'show']),

        ];
    }
    public function index(Request $request)
    {
        $search = $request->search ? $request->search : null;

        $kategoris = kategori::when($search, function ($query, $search) {
            $query->where('nama_kategori', 'like', '%' . $search . '%');
        })->oldest()->paginate()->withQueryString();

        return view('kategori.index', compact('kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = new kategori();

        return view('kategori.create', compact('kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
       {
           $validated = $request->validate([
               'nama_kategori' => 'required|string|max:100|unique:kategoris,nama_kategori',
           ]);

           Kategori::create($validated);

           return redirect()->route('kategori.index')
                  ->with('success', 'Kategori baru berhasil ditambahkan.');
       }

    /**
     * Display the specified resource.
     */
    public function show(kategori $kategori)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
   public function edit(Kategori $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategoris,nama_kategori,' . $kategori->id,
        ]);

        $kategori->update($validated);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(kategori $kategori)
    {
        if ($kategori->barang()->exists()) {
            return redirect()->route('kategori.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki barang terkait.');
        }
        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
