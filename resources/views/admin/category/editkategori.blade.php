@extends('admin.main')
@section('content')
<main>
    <div class="container-fluid px-4">
        <!-- Back Button -->
        <a href="{{ route('kategori.index') }}" class="btn btn-sm btn-outline-primary mb-4 mt-4">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        <!-- Title -->
        <h3 class="mt-4 mb-4 text-center">Edit Kategori "{{ $kategori->nama_kategori }}"</h3>

        <!-- Form -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('kategori.update', ['id' => $kategori->id]) }}" method="POST">
                            @method('PATCH')
                            @csrf
                            <!-- Input Field -->
                            <div class="mb-4">
                                <label for="nama" class="form-label">Nama Kategori</label>
                                <input type="text" id="nama" name="nama" class="form-control" value="{{ $kategori->nama_kategori }}" required>
                            </div>
                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Ganti Nama</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>


@endsection
