@extends('admin.main')
@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <button type="button" class="btn btn-success mt-4" data-bs-toggle="modal" data-bs-target="#addNewUser">Tambah User</button>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card shadow mt-4">
                <div class="card-header"><b>Penyewa</b></div>
                <div class="card-body">
                    <table id="dataTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($penyewa as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->name }} <span class="badge bg-secondary">{{ $item->payment->count() }} Transaksi</span></td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->telepon }}</td>
                                <td><a class="btn btn-success" href="{{ route('admin.buatreservasi',['userId' => $item->id]) }}">Buat Reservasi</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addNewUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">User Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('user.new') }}" method="POST">
                    @csrf
                    <div class="form-floating mb-2">
                        <input type="text" name="name" class="form-control" id="floatingName" placeholder="Nama" required>
                        <label for="floatingName">Nama Lengkap</label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com" required>
                        <label for="floatingInput">Email</label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required>
                        <label for="floatingPassword">Password</label>
                    </div>
                    <div class="form-floating">
                        <input type="text" name="telepon" class="form-control" id="floatingtelp" placeholder="Nomor Telepon" required>
                        <label for="floatingtelp">No Telepon</label>
                    </div>
                    <button type="submit" class="btn btn-success w-100 mt-4">Daftar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
