@extends('admin.main')
@section('content')
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Manajemen Kategori</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <div class="row">
            @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Tambah Harga dan Paket Hari
                    </div>
                    <div class="card-body">
                        <form action="{{ route('harga.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <select class="form-select" name="product" required>
                                    @foreach ($hargas as $h)
                                        <option value="{{ $h->product->id }}">{{ $h->product->nama_produk }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <select class="form-select" id="hari" name="hari" required>
                                    <option value="" disabled selected>Pilih Paket Hari:</option>
                                    <option value="1">1 hari</option>
                                    <option value="3">3 hari</option>
                                    <option value="7">7 hari</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <span class="form-text mb-2">Harga ditulis angka saja, tidak perlu tanda titik</span>
                                <input type="text" name="harga" class="form-control" placeholder="Harga" required>
                            </div>
                            
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Tambah</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Harga dan Durasi
                    </div>
                    <div class="card-body">
                        <table id="dataTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Paket Hari</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hargas as $h)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $h->product->nama_produk }}</td>
                                    <td>{{ $h->harga }}</td>
                                    <td> {{ $h->hari }}</td>
                                    <td>
                                        <a href="{{ route('harga.edit',['id'=>$h->id]) }}" type="button" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('harga.destroy',['id'=>$h->id]) }}" method="POST" style="display: inline-block">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="javascript: return confirm('Anda yakin akan menghapus produk ini?');"><i class="fas fa-trash"></i></a>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection
