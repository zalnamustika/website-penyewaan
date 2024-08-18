@extends('admin.main')
@section('content')
    <style>
        .pagination {
            margin: 10px 0;
            text-align: center;
        }

        .pagination a,
        .pagination button {
            margin: 0 5px;
            padding: 5px 10px;
            text-decoration: none;
            border: 1px solid #ddd;
            color: #007bff;
            background: #fff;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }

        .pagination button {
            border: 1px solid transparent;
            background-color: transparent;
            color: #007bff;
        }

        .pagination a:hover,
        .pagination button:hover {
            background-color: #007bff;
            color: #fff;
        }

        #searchContainer {
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #searchInput {
            padding: 8px;
            width: 60%;
            box-sizing: border-box;
            max-width: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        #searchContainer select,
        #searchContainer button {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #fff;
        }

        #searchContainer button {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            margin-left: 10px;
        }

        #searchContainer button:hover {
            background-color: #0056b3;
        }

        .card {
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
            /* Jarak bawah untuk semua kartu */
        }

        .card-body {
            padding: 20px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 4px;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tbody tr:hover {
            background-color: #e9ecef;
        }

        .row {
            display: flex;
            flex-wrap: nowrap;
            /* Memastikan semua card berada dalam satu baris */
            gap: 20px;
            /* Jarak antar kolom */
            justify-content: space-between;
            /* Mengatur jarak antara card */
        }

        .col-lg-6,
        .col-sm-12 {
            flex: 1;
            min-width: 0;
        }

        .col-xl-3,
        .col-md-6 {
            flex: 1;
            max-width: 23%;
            /* Mengatur ukuran maksimum card */
        }
    </style>
    <main>
        <div class="container-fluid px-4">
            <div class="row mt-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <h1>{{ $total_penyewaan }}</h1> Reservasi
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small link-dark stretched-link" href="{{ route('penyewaan.index') }}">Kelola Reservasi</a>
                            <div class="small"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <h1>{{ $total_user }}</h1> Penyewa
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small link-dark stretched-link" href="{{ route('admin.user') }}">Kelola Penyewa</a>
                            <div class="small"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <h1>{{ $total_product }}</h1> Produk
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small link-dark stretched-link" href="{{ route('product.index') }}">Kelola Produk</a>
                            <div class="small"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <h1>{{ $total_kategori }}</h1> Kategori
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small link-dark stretched-link" href="{{ route('kategori.index') }}">Kelola Kategori</a>
                            <div class="small"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-lg-6 col-sm-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="card-title">Sewa Terlambat</h5>
                            <div id="searchContainer">
                                <input type="text" id="searchInput1" placeholder="Cari...">
                                <select id="rowsPerPage1">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select>
                                <button id="entirePageButton1">Tampilkan Semua</button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable1" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nama Produk</th>
                                            <th>Nama Pelanggan</th>
                                            <th>Tanggal Sewa</th>
                                            <th>Tanggal Kembali</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            @if ($order->status != 5)
                                                <tr>
                                                    <td>{{ $order->product->nama_produk }}</td>
                                                    <td>{{ $order->user->name }} ({{ $order->user->telepon }})</td>
                                                    <td>{{ date('D, d M Y H:i', strtotime($order->starts)) }}</td>
                                                    <td>{{ date('D, d M Y H:i', strtotime($order->ends)) }}</td>
                                                    <td>
                                                        @php
                                                            $now = \Carbon\Carbon::now();
                                                            $ends = \Carbon\Carbon::parse($order->ends);
                                                            $daysLate = $now->diffInDays($ends);
                                                        @endphp
                                                        <a href="https://api.whatsapp.com/send?phone=+62{{ $order->user->telepon }}&text=Hai {{ $order->user->name }},%0A%0AKami ingin mengingatkan bahwa produk *{{ $order->product->nama_produk }}* yang Anda sewa telah melewati batas waktu pengembalian. Anda telah terlambat selama {{ $daysLate }} hari. Mohon segera melakukan pengembalian. Tanggal pengembalian: {{ $order->ends }}, sekarang sudah tanggal: {{ $now }}."
                                                            target="_blank" class="btn btn-sm btn-success">
                                                            <i class="fab fa-whatsapp"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="card-title">Reservasi Belum Diacc</h5>
                            <div id="searchContainer">
                                <input type="text" id="searchInput2" placeholder="Cari...">
                                <select id="rowsPerPage2">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select>
                                <button id="entirePageButton2">Tampilkan Semua</button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Invoice</th>
                                            <th>Produk</th>
                                            <th>Pelanggan</th>
                                            <th>Sewa</th>
                                            <th>Dikembalikan</th>
                                            <th>Sekarang</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($belumAcc as $order)
                                            @if ($order->status == 1 && $order->status != 5 && $order->payment)
                                                <tr class="{{ $order->colorClass }}">
                                                    <td>{{ $order->payment->no_invoice }}</td>
                                                    <td>{{ $order->product->nama_produk }}</td>
                                                    <td>{{ $order->user->name }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($order->starts)->locale('id')->translatedFormat('l, d F Y H:i') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($order->ends)->locale('id')->translatedFormat('l, d F Y H:i') }}</td>
                                                    <td>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y H:i') }}</td>
                                                    <td>
                                                        <a href="{{ route('penyewaan.detail', $order->payment_id) }}" class="btn btn-sm btn-success">
                                                            <i class="fas fa-check-circle"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="7">Order ID: {{ $order->id }} tidak memiliki informasi payment.</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-sm-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="card-title text-danger"><i class="fas fa-exclamation-circle"></i> Peringatan Penting</h5>
                            <p>Perhatian untuk admin: Pastikan selalu memeriksa dan mengacc pembayaran reservasi yang masuk tepat waktu. Batas waktu untuk mengacc pembayaran hanya 24 jam setelah pembayaran dilakukan.</p>
                        </div>
                    </div>
                    <div class="card shadow">
                        <div class="card-body">
                            <b>
                                <h5>5 Produk Terfavorit</h5>
                            </b>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Produk</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($top_products as $product)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="d-flex justify-content-between">{{ $product->nama_produk }} <span
                                                    class="badge bg-secondary">{{ $product->order_count }} Reservasi</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- <div class="card shadow" style="margin-top: 20px;"> <!-- Jarak atas kartu tambahan -->
                        <div class="card-body">
                            <h5 class="card-title">Produk Belum ACC Pembayaran</h5>
                            <div id="searchContainer">
                                <input type="text" id="searchInput3" placeholder="Cari...">
                                <select id="rowsPerPage3">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select>
                                <button id="entirePageButton3">Tampilkan Semua</button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable3" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Invoice</th>
                                            <th>Produk</th>
                                            <th>Pelanggan</th>
                                            <th>Sewa</th>
                                            <th>Kembali</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($belumBayar as $order)
                                            @if ($order->payment->status == 3)
                                                <tr>
                                                    <td>{{ $order->payment->no_invoice }}</td>
                                                    <td>{{ $order->product->nama_produk }}</td>
                                                    <td>{{ $order->user->name }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($order->starts)->locale('id')->translatedFormat('l, d F Y H:i') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($order->ends)->locale('id')->translatedFormat('l, d F Y H:i') }}</td>
                                                    <td>
                                                        <a href="{{ route('penyewaan.detail', $order->id) }}" class="btn btn-sm btn-success">
                                                            <i class="fas fa-check-circle"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </main>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                function paginateTable(tableId, rowsPerPageSelectId, showAll) {
                    const table = document.getElementById(tableId);
                    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
                    const rowsPerPageSelect = document.getElementById(rowsPerPageSelectId);
                    const rowsPerPage = rowsPerPageSelect.value === 'all' ? rows.length : parseInt(rowsPerPageSelect
                        .value);
                    const totalRows = rows.length;
                    const pageCount = Math.ceil(totalRows / rowsPerPage);

                    // Hapus pagination lama jika ada
                    const existingPagination = table.parentElement.querySelector('.pagination');
                    if (existingPagination) {
                        existingPagination.remove();
                    }

                    if (showAll) {
                        for (let i = 0; i < totalRows; i++) {
                            rows[i].style.display = '';
                        }
                    } else {
                        // Buat pagination
                        const pagination = document.createElement('div');
                        pagination.className = 'pagination';
                        for (let i = 1; i <= pageCount; i++) {
                            const pageLink = document.createElement('a');
                            pageLink.href = '#';
                            pageLink.textContent = i;
                            pageLink.addEventListener('click', function(e) {
                                e.preventDefault();
                                showPage(i);
                            });
                            pagination.appendChild(pageLink);
                        }
                        table.parentElement.appendChild(pagination);

                        // Tampilkan halaman pertama
                        showPage(1);

                        function showPage(page) {
                            const startRow = (page - 1) * rowsPerPage;
                            const endRow = page * rowsPerPage;

                            for (let i = 0; i < totalRows; i++) {
                                rows[i].style.display = (i >= startRow && i < endRow) ? '' : 'none';
                            }
                        }
                    }
                }

                function searchTable(tableId, searchInputId) {
                    const input = document.getElementById(searchInputId);
                    const table = document.getElementById(tableId);
                    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

                    input.addEventListener('input', function() {
                        const filter = input.value.toLowerCase();

                        for (let i = 0; i < rows.length; i++) {
                            const cells = rows[i].getElementsByTagName('td');
                            let matched = false;

                            for (let j = 0; j < cells.length; j++) {
                                if (cells[j].textContent.toLowerCase().includes(filter)) {
                                    matched = true;
                                    break;
                                }
                            }

                            rows[i].style.display = matched ? '' : 'none';
                        }

                        // Update pagination setelah pencarian
                        paginateTable(tableId, tableId + 'RowsPerPage');
                    });
                }

                // Inisialisasi pagination dan pencarian untuk setiap tabel
                document.getElementById('entirePageButton1').addEventListener('click', function() {
                    paginateTable('dataTable1', 'rowsPerPage1', true);
                });
                document.getElementById('entirePageButton2').addEventListener('click', function() {
                    paginateTable('dataTable2', 'rowsPerPage2', true);
                });
                document.getElementById('entirePageButton3').addEventListener('click', function() {
                    paginateTable('dataTable3', 'rowsPerPage3', true);
                });

                document.getElementById('rowsPerPage1').addEventListener('change', function() {
                    paginateTable('dataTable1', 'rowsPerPage1');
                });
                document.getElementById('rowsPerPage2').addEventListener('change', function() {
                    paginateTable('dataTable2', 'rowsPerPage2');
                });
                document.getElementById('rowsPerPage3').addEventListener('change', function() {
                    paginateTable('dataTable3', 'rowsPerPage3');
                });

                document.getElementById('searchInput1').addEventListener('input', function() {
                    searchTable('dataTable1', 'searchInput1');
                });
                document.getElementById('searchInput2').addEventListener('input', function() {
                    searchTable('dataTable2', 'searchInput2');
                });
                document.getElementById('searchInput3').addEventListener('input', function() {
                    searchTable('dataTable3', 'searchInput3');
                });

                paginateTable('dataTable1', 'rowsPerPage1');
                paginateTable('dataTable2', 'rowsPerPage2');
                paginateTable('dataTable3', 'rowsPerPage3');
            });
        </script>
    @endpush
@endsection
