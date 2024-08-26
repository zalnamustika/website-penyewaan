@extends('admin.main')
@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-md-12 mt-4">
            <div class="card shadow">
                <div class="card-body" style="overflow: auto">
                    <table id="dataTable">
                        <thead>
                            <tr>
                                <th>No. Invoice</th>
                                <th>Tanggal Reservasi</th>
                                <th>Tanggal Pengembalian</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detail as $item)
                                <tr>
                                    <td> {{ $item->no_invoice }}
                                        @if ($item->status == 1)
                                            <span class="badge bg-warning">Perlu Ditinjau</span>
                                        @elseif ($item->status == 2)
                                            <span class="badge bg-info">Belum Bayar</span>
                                        @elseif ($item->status == 3)
                                            <span class="badge bg-success">Sudah Bayar</span>
                                        @elseif ($item->status == 4)
                                            <span class="badge bg-secondary">Selesai</span>
                                        @elseif ($item->status == 5)
                                            <span class="badge bg-danger">Pembayaran Gagal</span>
                                        @endif
                                    </td>
                                    <td>{{ date('D, d M Y', strtotime($item->created_at)) }}</td>
                                    <td>{{ date('D, d M Y', $item->ends) }}</td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>
@endpush

