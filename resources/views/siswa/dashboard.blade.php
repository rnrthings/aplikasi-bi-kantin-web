
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bi-Kantin - Siswa</title>
    <style>
        body { font-family:Arial; margin:0; background:#f1f5f9; color:#1e293b; }
        header { background:#166534; color:white; padding:20px 5%; display:flex; justify-content:space-between; align-items:center; }
        main { max-width:1000px; margin:25px auto; padding:0 16px; }
        .grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:16px; }
        .card, section { background:white; padding:20px; border-radius:12px; margin-bottom:20px; }
        input,button { padding:10px; margin-top:8px; }
        button { background:#15803d; color:white; border:0; border-radius:5px; cursor:pointer; }
        table { border-collapse:collapse; width:100%; }
        th,td { padding:10px; border-bottom:1px solid #e2e8f0; text-align:left; }
        .pesan { background:#dcfce7; padding:12px; }
        .error { background:#fee2e2; padding:12px; }
        .scroll { overflow-x:auto; }
    </style>
</head>
<body>
<header>
    <div><strong>BI-KANTIN</strong><br>Katalog Kantin Sekolah</div>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
</header>

<main>
    <h2>Halo, {{ session('username') }}!</h2>
    <p>Pilih menu favoritmu dan buat pesanan.</p>

    @if (session('sukses'))
        <p class="pesan">{{ session('sukses') }}</p>
    @endif
    @if ($errors->any())
        <p class="error">{{ $errors->first() }}</p>
    @endif

    <h3>Katalog Menu</h3>
    <div class="grid">
        @forelse ($menus as $menu)
            <div class="card">
                <h3>{{ $menu->nama_menu }}</h3>
                <p>Kategori: {{ $menu->kategori }}</p>
                <h3>Rp{{ number_format($menu->harga, 0, ',', '.') }}</h3>

                <form action="{{ route('siswa.pesan') }}" method="POST">
                    @csrf
                    <input type="hidden" name="menu_id"
                           value="{{ $menu->id }}">
                    <label>Jumlah:</label>
                    <input type="number" name="jumlah"
                           min="1" max="100" value="1" required>
                    <br>
                    <button type="submit">Pesan Sekarang</button>
                </form>
            </div>
        @empty
            <p>Belum ada menu tersedia.</p>
        @endforelse
    </div>

    <section>
        <h3>Riwayat Pesanan Saya</h3>
        <div class="scroll">
            <table>
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Jumlah</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($pesanan as $p)
                    <tr>
                        <td>{{ $p->nama_menu }}</td>
                        <td>{{ $p->jumlah }}</td>
                        <td>Rp{{ number_format($p->total_harga, 0, ',', '.') }}</td>
                        <td>{{ $p->status }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Kamu belum mempunyai pesanan.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <p>Status akan terlihat setelah halaman diperbarui.</p>
    </section>
</main>
</body>
</html>
