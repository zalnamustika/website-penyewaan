<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <title>Member Area</title>
</head>
<style>
    .navbar .dropdown-menu {
        right: 0;
        left: auto;
    }

    @media screen and (max-width: 900px) {
        .navbar .dropdown-menu {
            right: 0;
            left: 0;
        }
    }

    .custom-btn {
        text-decoration: underline !important;
        text-underline-offset: 0.5em;
        color: #333333 !important;
    }

    .custom-btn-off {
        text-decoration: none !important;
        color: #888888 !important;
    }

    .custom-btn-off:hover {
        text-decoration: underline !important;
        text-underline-offset: 0.5em !important;
    }

    .footer {
        margin-top: 7rem;
        background-color: #222222;
        padding: 5rem;
    }

    .footer-list {
        gap: 5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap
    }

    .list-group-item a {
        text-decoration: none !important;
        color: #B2B2B2 !important;
    }

    .help-footer {
        height: 200px;
    }

    .git-footer {
        height: 200px;
    }

    .git-footer p {
        width: 250px;
        color: #B2B2B2;
    }


    .git-footer iframe {
        max-width: 100%;
        
    }
</style>

<body style="padding-bottom: 90px">
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Selamat Datang di Rumah Penyewaan Pakaian</span>
            <div class="d-flex text-light">
                <div class="nav-item dropdown">
                    @auth
                        <a class="link-light dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration: none;">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="{{ route('akun.pengaturan') }}">Pengaturan Akun</a></li>
                            <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
                        </ul>
                    @endauth
                    @guest
                        <a class="link-light dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration: none;">
                            Akun
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="{{ route('daftar') }}">Daftar</a></li>
                            <li><a class="dropdown-item" href="{{ route('login') }}">Login</a></li>
                        </ul>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <ul class="nav nav-tabs nav-fill d-none d-sm-flex">
        <li class="nav-item"><a class="nav-link {{ Route::is('member.index') ? 'active' : '' }}"
                href="{{ route('member.index') }}">Produk</a></li>
        @auth
            <li class="nav-item"><a class="nav-link {{ Route::is('member.keranjang') ? 'active' : '' }}"
                    href="{{ route('keranjang.show') }}">Keranjang Anda <span
                        class="badge bg-secondary">{{ Auth::user()->cart->count() }}</span></a></li>
            <li class="nav-item"><a class="nav-link {{ Route::is('order.show') ? 'active' : '' }}"
                    href="{{ route('order.show') }}">Reservasi Anda <span
                        class="badge bg-secondary">{{ Auth::user()->payment->count() }}</span></a></li>
        @else
            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Keranjang Anda <span
                        class="badge bg-secondary">0</span></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Reservasi Anda <span
                        class="badge bg-secondary">0</span></a></li>
        @endauth
    </ul>

    <div class="container-fluid px-4 mt-4">
        @yield('container')
        <!-- Bottom Navbar -->
        <nav class="navbar navbar-dark bg-dark navbar-expand d-md-none d-lg-none d-xl-none fixed-bottom">
            <ul class="navbar-nav nav-justified w-100">
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('member.index') ? 'active' : '' }}"
                        href="{{ route('member.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-search" viewBox="0 0 16 16">
                            <path
                                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                        </svg>
                        <span class="small d-block">Produk</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('member.keranjang') ? 'active' : '' }}"
                        href="{{ route('member.keranjang') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-cart" viewBox="0 0 16 16">
                            <path
                                d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                        </svg>
                        <span class="small d-block">Keranjang</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('order.show') || Route::is('order.detail') ? 'active' : '' }}"
                        href="{{ route('order.show') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-journal-bookmark" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M6 8V1h1v6.117L8.743 6.07a.5.5 0 0 1 .514 0L11 7.117V1h1v7a.5.5 0 0 1-.757.429L9 7.083 6.757 8.43A.5.5 0 0 1 6 8z" />
                            <path
                                d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2z" />
                            <path
                                d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1z" />
                        </svg>
                        <span class="small d-block">Reservasi</span>
                    </a>
                </li>
            </ul>
        </nav>

    </div>
    <div class="container-fluid footer px-4 ">
        <div class="footer-list d-flex justify-content-center align-items-center text-light">
            <div class="help-footer d-flex flex-column">
                <h3>Hub Kami</h3>
                <div class="d-flex gap-3">
                    <a target="_blank" href="https://www.facebook.com/penyewaan.adat/">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#888888"
                            class="bi bi-facebook" viewBox="0 0 16 16">
                            <path
                                d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951" />
                        </svg>
                    </a>
                    <a target="_blank" href="https://www.instagram.com/rumahpenyewaanpakaian/">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#888888"
                            class="bi bi-instagram" viewBox="0 0 16 16">
                            <path
                                d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334" />
                        </svg>
                    </a>
                    <a target="_blank" href="https://web.whatsapp.com/send?phone=085384968497">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#888888"
                            class="bi bi-whatsapp" viewBox="0 0 16 16">
                            <path
                                d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232" />
                        </svg>
                    </a>
                </div>
            </div>
            <div class="git-footer d-flex flex-column">
                <h3>Lokasi</h3>
                <p>Jl. Raya Ampang No.34, Ampang, Kec. Kuranji, Kota Padang, Sumatera Barat 25154</p>
            </div>
            <div class="git-footer d-flex flex-column">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15957.205115690762!2d100.3817728!3d-0.9224194!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2fd4b8e494cf4b0d%3A0x591b42d5a468f1c!2sRumah%20Penyewaan%20Pakaian!5e0!3m2!1sid!2sid!4v1724038898359!5m2!1sid!2sid"
                    width="700" height="650" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
    <script src="/js/datatables.js"></script>
    <script src="/js/adminscripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
    @stack('custom-script')


</body>

</html>
