<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Siswa - Bi-Kantin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            margin: 0;
            color: #1e293b;
        }

        header {
            background: #166534;
            color: white;
            padding: 20px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        main {
            max-width: 1000px;
            margin: 25px auto;
            padding: 0 16px;
        }

        section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 22px;
            overflow-x: auto;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 15px;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }

        button, select, input {
            padding: 9px;
            margin: 4px 0;
        }

        button {
            cursor: pointer;
            border: 0;
            border-radius: 5px;
            background: #15803d;
            color: white;
        }

        .logout {
            background: #dc2626;
        }

        .pesan {
            background: #dcfce7;
            padding: 12px;
        }

        .gagal {
            background: #fee2e2;
            padding: 12px;
        }
    </style>
</head>
<body>

<header>
    <div>
        <strong>BI-KANTIN</strong><br>
        Dashboard Siswa
    </div>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="logout" type="submit">Logout</button>
    </form>
</header>

<main>
    <h2>Halo, {{ session('username') }}!</h2>
    <p>Selamat datang di Bi-Kantin. Pilih menu favoritmu!</p>

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
        <h3>Daftar Menu Kantin</h3>

        <table>
            <thead>
                <tr>
                    <th>Nama Menu</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($menus as $menu)
                    <tr>
                        <td>{{ $menu->nama_menu }}</td>
                        <td>{{ $menu->kategori }}</td>
                        <td>Rp{{ number_format($menu->harga, 0, ',', '.') }}</td>
                        <td>
                        
            <form action="{{ route('siswa.pesan', $menu->id) }}"
                    method="POST">
                @csrf

                <input type="hidden" name="menu_id"
                    value="{{ $menu->id }}">

                <input type="number" name="jumlah"
                    min="1" value="1" required>
                        </td>
                        <td>
                                <button type="submit">Pesan</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Belum ada menu tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <section>
        <h3>Pesanan Saya</h3>

        <table>
            <thead>
                <tr>
                    <th>Menu</th>
                    <th>Jumlah</th>
                    <th>Total</th>
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
                        <td colspan="4">Kamu belum memiliki pesanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
</main>

</body>
</html>
