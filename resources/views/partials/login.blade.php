<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Login</title>
    <style>
        body {
            background-color: #f5f5f5;
        }

        .container {
            max-width: 400px;
            margin-top: 50px;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-control {
            border-radius: 0.25rem;
        }

        .btn-custom {
            background-color: #ff6600;
            border-color: #ff6600;
            color: white;
        }

        .btn-custom:hover {
            background-color: #e65c00;
            border-color: #e65c00;
        }
    </style>
</head>

<body>
    <div class="container">
        <h4 class="text-center mb-4">Rumah Penyewaan Pakaian</h4>
        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif
        <form action="{{ route('login.auth') }}" method="POST">
            @csrf
            <div class="mb-3">Email</label>
                <input type="email" name="email" class="form-control" id="email"
                    placeholder="Silakan masukkan Email" required value="{{ old('email') }}">
                @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Kata sandi</label>
                <input type="password" name="password" class="form-control" id="password"
                    placeholder="Silahkan masukkan kata sandi" required>
                @error('password')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="text-end">
                <small> <a href="{{ route('forgetpassword.index') }}" class="text-decoration-none">Lupa Kata Sandi?</a>
                </small>
            </div>
            <button type="submit" class="btn btn-success w-100">Login</button>
            <div class="mb-3">
                <br>belum punya akun? <a href="{{ route('daftar') }}" class="text-decoration-none">daftar disini</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXlIHjAnDXf5VO3U0OD8+0sW0J1K/hvI3x7r3ysF9ozQZ9rfaBGIKSUFn0YJ" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiY2yMn6GoI1QZzpqKpPo0iSmVnU9M8tb+7uwj1Q6nj8Yj8gFA2C/1zYS" crossorigin="anonymous">
    </script>
</body>

</html>
