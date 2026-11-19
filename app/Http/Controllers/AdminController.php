<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class AdminController extends Controller
{
    public function dashboard()
    {
        $menus = Menu::query()
            ->join('kantin', 'menu.id_kantin', '=', 'kantin.id_kantin')
            ->select('menu.*', 'kantin.nama_kantin')
            ->orderByDesc('menu.id_menu')
            ->get();

        $kantins = DB::table('kantin')
            ->orderBy('nama_kantin')
            ->get(['id_kantin', 'nama_kantin']);

        $pesanan = DB::table('transaksi as t')
            ->join('detail_transaksi as d', 't.id_transaksi', '=', 'd.id_transaksi')
            ->join('menu as m', 'd.id_menu', '=', 'm.id_menu')
            ->leftJoin('users as u', 't.nis', '=', 'u.nis')
            ->select(
                't.id_transaksi',
                't.status',
                't.status_bayar',
                't.metode_bayar',
                't.tanggal',
                'u.username',
                'm.nama_menu',
                'd.jumlah',
                'd.subtotal as total_harga'
            )
            ->orderByDesc('t.id_transaksi')
            ->get()
            ->groupBy('id_transaksi')
            ->map(function ($items) {
                $pesanan = $items->first();
                $pesanan->nama_menu = $items->pluck('nama_menu')->join(', ');
                $pesanan->jumlah = $items->sum('jumlah');
                $pesanan->total_harga = $items->sum('total_harga');

                return $pesanan;
            })
            ->values();

        return view('admin.dashboard', compact('menus', 'kantins', 'pesanan'));
    }

    public function tambahMenu(Request $request)
    {
        $data = $request->validate([
            'id_kantin' => 'required|integer|exists:kantin,id_kantin',
            'nama_menu' => 'required|string|max:100',
            'harga' => 'required|integer|min:1',
            'kategori' => 'required|string|max:30',
            'gambar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'extensions:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $gambar = $request->file('gambar')?->store('menu', 'public');

        if ($request->hasFile('gambar') && $gambar === false) {
            return back()
                ->withErrors(['gambar' => 'Gambar gagal disimpan.'])
                ->withInput();
        }

        unset($data['gambar']);

        Menu::create([...$data, 'gambar' => $gambar]);

        return redirect()->route('admin.dashboard')
            ->with('sukses', 'Menu berhasil ditambahkan.');
    }

    public function editMenu(Request $request, $id)
    {
        $data = $request->validate([
            'id_kantin' => 'required|integer|exists:kantin,id_kantin',
            'nama_menu' => 'required|string|max:100',
            'harga' => 'required|integer|min:1',
            'kategori' => 'required|string|max:30',
            'gambar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'extensions:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $menu = Menu::findOrFail($id);
        $gambarLama = $menu->gambar;
        $gambarBaru = $request->file('gambar')?->store('menu', 'public');

        if ($request->hasFile('gambar') && $gambarBaru === false) {
            return back()
                ->withErrors(['gambar' => 'Gambar gagal disimpan.'])
                ->withInput();
        }

        unset($data['gambar']);

        if ($gambarBaru !== null) {
            $data['gambar'] = $gambarBaru;
        }

        $menu->update($data);

        if ($gambarBaru !== null && $gambarLama !== $gambarBaru) {
            $this->hapusGambarMenu($gambarLama);
        }

        return redirect()->route('admin.dashboard')
            ->with('sukses', 'Menu berhasil diperbarui.');
    }

    public function hapusMenu($id)
    {
        $menu = Menu::findOrFail($id);

        if (DB::table('detail_transaksi')->where('id_menu', $id)->exists()) {
            return back()->with(
                'gagal',
                'Menu memiliki riwayat pesanan sehingga tidak dapat dihapus.'
            );
        }

        $menu->delete();
        $this->hapusGambarMenu($menu->gambar);

        return back()->with('sukses', 'Menu berhasil dihapus.');
    }

    private function hapusGambarMenu(?string $path): void
    {
        if (! $path) {
            return;
        }

        $storage = Storage::disk('public');

        if ($storage->exists($path) && ! $storage->delete($path)) {
            throw new RuntimeException('Failed to remove a menu image from public storage.');
        }
    }

    public function ubahStatus(Request $request, $id)
    {
        $data = $request->validate([
            'status' => 'required|in:Menunggu Konfirmasi,Sedang Dimasak,Siap Diambil,Selesai',
        ]);

        Transaksi::findOrFail($id)->update($data);

        return back()->with('sukses', 'Status pesanan berhasil diubah.');
    }
}
