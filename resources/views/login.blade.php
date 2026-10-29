<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Bi-Kantin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .card {
            background: white;
            padding: 30px;
            border-radius: 14px;
            width: 320px;
            box-shadow: 0 5px 20px #0001;
        }
        h1 { color: #15803d; text-align: center; }
        input, button {
            box-sizing: border-box;
            width: 100%;
            padding: 12px;
            margin-top: 12px;
            border-radius: 7px;
        }
        input { border: 1px solid #cbd5e1; }
        button {
            background: #15803d;
            color: white;
            border: 0;
            cursor: pointer;
        }
        .error { color: #dc2626; }
        .info { color: #64748b; font-size: 13px; }
    </style>
</head>
<body>
<div class="card">
    <h1>BI-KANTIN</h1>
    <p style="text-align:center">Login Kantin Online Sekolah</p>

    @if ($errors->any())
        <p class="error">{{ $errors->first() }}</p>
    @endif

    <form action="{{ route('login.proses') }}" method="POST">
        @csrf

        <label>Username</label>
        <input type="text" name="username"
            value="{{ old('username') }}" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <p class="info">Admin: admin_kantin / admin123</p>
    <p class="info">Siswa: budi_siswa / 123</p>
</div>
</body>
</html>
