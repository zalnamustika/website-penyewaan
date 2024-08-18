@extends('admin.main')
@section('content')
    <main>
        <div class="container-fluid px-4">
            <div class="row mt-4">
                <div class="col">
                    <div class="card mb-4">
                        <div class="card-header">
                            <a class="link-dark" href="{{ route('product.index') }}" style="text-decoration: none"><i
                                    class="fas fa-arrow-left"></i> Kembali</a> | Detail untuk Produk
                            "{{ $product->nama_produk }}"
                        </div>
                        <div class="card-body">
                            <form action="{{ route('product.update', ['id' => $product->id]) }}" method="POST"
                                enctype="multipart/form-data">
                                @method('PATCH')
                                @csrf
                                <input class="form-control form-control mb-4" type="text" name="nama"
                                    value="{{ $product->nama_produk }}" required>
                                <select name="kategori" class="form-select mb-4">
                                    @foreach ($kategori as $cat)
                                        <option value="{{ $cat->id }}"
                                            @if ($cat->id == $product->category->id) selected="selected" @endif>
                                            {{ $cat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                                <div class="mb-3">
                                    <textarea class="form-control" name="deskripsi" placeholder="Deskripsi singkat" rows="3">{{ $product->deskripsi }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <input type="number" name="stok" class="form-control mb-4" width="30%"
                                        value="{{ $product->stok }}" required>
                                </div>
                                <div class="mb-3">
                                    <div class="mt-3">
                                        <span class="form-text">Upload Gambar Produk</span>
                                        <input type="file" name="gambar" class="form-control">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <span class="form-text">Harga Paket</span>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Paket Hari</th>
                                            <th>Harga</th>
                                        </tr>
                                        @forelse ($product->harga as $harga)
                                            <tr>
                                                <td>{{ $harga->hari }} Hari</td>
                                                <td><input type="text" name="harga_{{ $harga->hari }}_hari"
                                                        class="form-control" value="{{ $harga->harga }}" placeholder="Isi harga tanpa menggunakan titik"></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2">Tidak ada data harga paket.</td>
                                            </tr>
                                        @endforelse
                                    </table>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-lg-8"></div>
                                    <div class="col-lg-4"><button class="btn btn-success" type="submit"
                                            style="float: right">Simpan Perubahan</button></div>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('product.destroy', ['id' => $product->id]) }}" method="POST">
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
        </div>
    </main>
@endsection
