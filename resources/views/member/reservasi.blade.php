@extends('member.main')
@section('container')
    <div class="container">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="card-title">Reservasi</h5>
            </div>
            <div class="card-body" style="overflow: auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal Pengambilan</th>
                            <th>Total</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reservasi as $item)
                            <tr>
                                @if ($item->order->isNotEmpty())
                                    <td>{{ date('D, d M Y H:i', strtotime($item->order->first()->starts)) }}</td>
                                @else
                                    <td>Data tidak tersedia</td>
                                @endif
                                <td>{{ formatRupiah($item->total) }} &nbsp; <span
                                        class="badge bg-secondary">{{ $item->order->count() }} Produk</span>
                                    @if ($item->status == 1)
                                        <span class="badge bg-warning">Sedang Ditinjau</span>
                                    @elseif ($item->status == 2)
                                        <span class="badge bg-info">Belum Bayar</span>
                                    @elseif ($item->status == 3)
                                        <span class="badge bg-success">Sudah Bayar</span>
                                    @elseif ($item->status == 5)
                                        <span class="badge bg-danger">Pembayaran Gagal</span>
                                    @endif
                                </td>
                                <td><a class="btn btn-primary"
                                        href="{{ route('order.detail', ['id' => $item->id]) }}">Detail</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="3">
                                    <p>Anda belum melakukan reservasi apapun.</p>
                                    <a href="{{ route('member.index') }}" class="btn btn-success">Mulai Reservasi
                                        Sekarang</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card shadow">
            <div class="card-header">
                <h5 class="card-title">Riwayat</h5>
            </div>
            <div class="card-body">
                <table id="dataTable">
                    <thead>
                        <tr>
                            <th>Tanggal Pengambilan</th>
                            <th>Total</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($riwayat as $r)
                            <tr>
                                <td>{{ date('D, d M Y H:i', strtotime($r->order->first()->starts)) }}</td>
                                <td>{{ formatRupiah($r->total) }} &nbsp; <span class="badge bg-secondary">{{ $r->order->count() }}
                                        Produk</span>
                                    <span class="badge bg-secondary">Selesai</span>
                                </td>
                                <td><a class="btn btn-primary"
                                        href="{{ route('order.detail', ['id' => $r->id]) }}">Detail</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
