<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <title>Registrasi</title>
    <style>
        body {
            position: relative;
            margin: 0;
            height: 100vh;
            background-image: url('/images/bg.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: #f8f9fa;
        }

        .container {
            position: relative;
            z-index: 1;
        }

        .bg-dark {
            background: rgba(0, 0, 0, 0.8) !important;
        }

        .form-control {
            background: white;
            border: none;
            color: #212529;
        }

        .form-control:focus {
            background: white;
            color: #212529;
            border: 2px solid #28a745;
        }

        .form-floating label {
            color: #212529;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .alert-danger {
            background-color: rgba(220, 53, 69, 0.8);
            color: #fff;
            border: none;
        }

        .link-dark {
            color: #f8f9fa;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="mainNav">
        <div class="container px-4">
            <a class="navbar-brand" href="{{ route('member.index') }}">Rumah Penyewaan Pakaian Adat</a>
        </div>
    </nav>
    <div class="container mt-5 px-3">
        <div class="row justify-content-center">
            <div class="col-md-5 bg-dark p-4 rounded">
                <form action="{{ route('register.store') }}" method="POST">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="text" name="name" class="form-control" id="floatingName" placeholder="Nama"
                            value="{{ old('name') }}" required>
                        <label for="floatingName">Nama Lengkap</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control" id="floatingInput"
                            placeholder="name@example.com" required>
                        <label for="floatingInput">Email</label>
                    </div>
                    @error('email')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div class="form-floating mb-3">
                        <input type="password" name="password" class="form-control" id="floatingPassword"
                            placeholder="Password" required>
                        <label for="floatingPassword">Password</label>
                    </div>
                    @error('password')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">No Telepon disarankan menggunakan nomor yang terhubung dengan whatsapp.</small>
                    <div class="form-floating mb-3">
                        <input type="text" name="telepon" class="form-control" id="floatingTelp"
                            placeholder="Nomor Telepon" value="{{ old('telepon') }}" required>
                        <label for="floatingTelp">No Telepon</label>
                    </div>
                    @error('telepon')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="btn btn-success w-100 mt-4">Daftar</button>
                </form>
                <div class="d-flex w-100 text-white mt-3">
                    Sudah punya akun? Silahkan&nbsp;<a class="link-dark" href="{{ route('login') }}">Login</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
