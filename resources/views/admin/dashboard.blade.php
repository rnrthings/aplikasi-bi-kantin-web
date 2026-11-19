<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin - Bi-Kantin</title>
    <style>
        body { font-family: Arial; background:#f1f5f9; margin:0; color:#1e293b; }
        header { background:#166534; color:white; padding:20px 5%; display:flex; justify-content:space-between; align-items:center; }
        main { max-width:1100px; margin:25px auto; padding:0 16px; }
        section { background:white; padding:20px; border-radius:12px; margin-bottom:22px; overflow-x:auto; }
        input, select, button { padding:9px; margin:4px 0; }
        input, select { max-width:100%; box-sizing:border-box; }
        button { cursor:pointer; border:0; border-radius:5px; background:#15803d; color:white; }
        .hapus { background:#dc2626; }
        table { border-collapse:collapse; width:100%; margin-top:15px; }
        th,td { padding:10px; border-bottom:1px solid #e2e8f0; text-align:left; }
        .pesan { background:#dcfce7; padding:12px; }
        .gagal { background:#fee2e2; padding:12px; }
        form { margin:3px 0; }
        .menu-image { width:80px; height:60px; object-fit:cover; border-radius:6px; }
    </style>
</head>
<body>
<header>
    <div><strong>BI-KANTIN</strong><br>Dashboard Pengelola</div>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
</header>

<main>
    <h2>Halo, {{ session('username') }}!</h2>

    @if (session('sukses'))
        <p class="pesan">{{ session('sukses') }}</p>
    @endif
    @if (session('gagal'))
        <p class="gagal">{{ session('gagal') }}</p>
    @endif
    @if ($errors->any())
        <p class="gagal">{{ $errors->first() }}</p>
    @endif

    <section>
        <h3>Tambah Menu</h3>
        <form action="{{ route('admin.menu.tambah') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <input name="nama_menu" placeholder="Nama menu" required>
            <input name="harga" type="number" min="1" placeholder="Harga" required>
            <input name="stok" type="number" min="0" max="2147483647"
                placeholder="Stok awal" required>
            <input name="gambar" type="file" accept=".jpg,.jpeg,.png,.webp">
            <select name="id_kantin" required>
                @foreach ($kantins as $kantin)
                    <option value="{{ $kantin->id_kantin }}">
                        {{ $kantin->nama_kantin }}
                    </option>
                @endforeach
            </select>
            <select name="kategori" required>
                <option value="Makanan">Makanan</option>
                <option value="Minuman">Minuman</option>
            </select>
            <button type="submit">Tambah Menu</button>
        </form>
    </section>

    <section>
        <h3>Daftar Menu</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama Menu</th>
                    <th>Gambar</th>
                    <th>Harga</th>
                    <th>Kategori</th>
                    <th>Kantin</th>
                    <th>Stok</th>
                    <th>Tambah Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($menus as $menu)
                <tr>
                    <td>
                        <form id="edit-menu-{{ $menu->id_menu }}"
                            action="{{ route('admin.menu.edit', $menu->id_menu) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input name="nama_menu"
                                value="{{ $menu->nama_menu }}" required>
                        </form>
                    </td>
                    <td>
                            @if ($menu->gambar)
                                <img class="menu-image"
                                    src="{{ asset('storage/' . $menu->gambar) }}"
                                    alt="Gambar {{ $menu->nama_menu }}">
                            @else
                                <span>Belum ada gambar</span>
                            @endif
                            <input form="edit-menu-{{ $menu->id_menu }}"
                                name="gambar" type="file"
                                accept=".jpg,.jpeg,.png,.webp">
                    </td>
                    <td>
                            <input form="edit-menu-{{ $menu->id_menu }}"
                                name="harga" type="number" min="1"
                                value="{{ $menu->harga }}" required>
                    </td>
                    <td>
                            <select form="edit-menu-{{ $menu->id_menu }}"
                                name="kategori">
                                <option value="Makanan"
                                    {{ $menu->kategori === 'Makanan' ? 'selected' : '' }}>
                                    Makanan
                                </option>
                                <option value="Minuman"
                                    {{ $menu->kategori === 'Minuman' ? 'selected' : '' }}>
                                    Minuman
                                </option>
                            </select>
                    </td>
                    <td>
                            <select form="edit-menu-{{ $menu->id_menu }}"
                                name="id_kantin" required>
                                @foreach ($kantins as $kantin)
                                    <option value="{{ $kantin->id_kantin }}"
                                        {{ $menu->id_kantin == $kantin->id_kantin ? 'selected' : '' }}>
                                        {{ $kantin->nama_kantin }}
                                    </option>
                                @endforeach
                            </select>
                    </td>
                    <td>
                            <p>
                                {{ $menu->stok > 0 ? 'Tersedia' : 'Stok Habis' }}
                                ({{ $menu->stok }})
                            </p>
                            <form action="{{ route('admin.menu.stok', $menu->id_menu) }}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <input name="stok" type="number" min="0"
                                    max="2147483647" value="{{ $menu->stok }}" required>
                                <button type="submit">Atur Stok</button>
                            </form>
                    </td>
                    <td>
                            <form action="{{ route('admin.menu.stok.tambah', $menu->id_menu) }}"
                                method="POST">
                                @csrf
                                <input name="jumlah_stok" type="number" min="1"
                                    max="2147483647" value="1" required>
                                <button type="submit">Tambah</button>
                            </form>
                    </td>
                    <td>
                            <button form="edit-menu-{{ $menu->id_menu }}"
                                type="submit">Simpan Edit</button>
                        <form action="{{ route('admin.menu.hapus', $menu->id_menu) }}"
                            method="POST"
                            onsubmit="return confirm('Hapus menu ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="hapus" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8">Belum ada menu.</td></tr>
            @endforelse
            </tbody>
        </table>
    </section>

    <section>
        <h3>Pesanan Masuk</h3>
        <table>
            <thead>
                <tr>
                    <th>Siswa</th>
                    <th>Menu</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($pesanan as $p)
                <tr>
                    <td>{{ $p->username ?? 'Siswa ' . $p->id_transaksi }}</td>
                    <td>{{ $p->nama_menu }}</td>
                    <td>{{ $p->jumlah }}</td>
                    <td>Rp{{ number_format($p->total_harga, 0, ',', '.') }}</td>
                    <td>{{ $p->status }}</td>
                    <td>{{ $p->metode_bayar }} ({{ $p->status_bayar }})</td>
                    <td>
                        <form action="{{ route('admin.pesanan.status', $p->id_transaksi) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <select name="status">
                                @foreach ([
                                    'Menunggu Konfirmasi',
                                    'Sedang Dimasak',
                                    'Siap Diambil',
                                    'Selesai'
                                ] as $status)
                                    <option value="{{ $status }}"
                                        {{ $p->status === $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit">Update</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">Belum ada pesanan masuk.</td></tr>
            @endforelse
            </tbody>
        </table>
    </section>
</main>
</body>
</html>
