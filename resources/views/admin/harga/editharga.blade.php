@extends('admin.main')
@section('content')
    <main>
        <div class="container-fluid px-4">
            <div class="row mt-4">
                <div class="col">
                    <div class="card mb-4">
                        <div class="card-header">
                            <a class="link-dark" href="{{ route('harga.index') }}" style="text-decoration: none"><i
                                    class="fas fa-arrow-left"></i> Kembali</a>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('harga.update', ['id' => $harga->id]) }}" method="POST"
                                enctype="multipart/form-data">
                                @method('PATCH')
                                @csrf
                                <select name="product" class="form-select mb-4">
                                    @foreach ($product as $p)
                                        <option value="{{ $p->id }}"
                                            @if ($p->id == $harga->product->id) selected="selected" @endif>
                                            {{ $p->nama_produk }}</option>
                                    @endforeach
                                </select>
                                <div class="mb-3">
                                    <select class="form-select" id="hari" name="hari" required>
                                        <option value="" disabled>Pilih Paket Hari:</option>
                                        <option value="1" {{ $harga->hari == 1 ? 'selected' : '' }}>1 hari</option>
                                        <option value="3" {{ $harga->hari == 3 ? 'selected' : '' }}>3 hari</option>
                                        <option value="7" {{ $harga->hari == 7 ? 'selected' : '' }}>7 hari</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <input type="number" name="harga" class="form-control mb-4" width="30%"
                                        value="{{ $harga->harga }}" required>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-lg-8"></div>
                                    <div class="col-lg-4"><button class="btn btn-success" type="submit"
                                            style="float: right">Simpan Perubahan</button></div>
                                </div>
                        </div>
                        </form>

                    </div>
                    <div class="card-body">
                        <form action="{{ route('harga.destroy', ['id' => $harga->id]) }}" method="POST">
                            @method('DELETE')
                            @csrf
                            <div class="alert alert-danger">
                                <b>Danger Zone: menghapus produk akan mempengaruhi transaksi yang telah dibuat</b>
                                <button class="btn btn-danger"
                                    onclick="javascript: return confirm('Anda yakin akan menghapus produk ini?');"
                                    type="submit">Hapus</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </main>
@endsection
