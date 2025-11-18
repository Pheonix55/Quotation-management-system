<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <!-- Styles -->
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" /> --}}
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    @livewireStyles




    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100..900&display=swap');

        :root {
            --base-clr: #ffffff;
            --line-clr: #ddd;
            --hover-clr: #f5f5f5;
            --text-clr: #11121a;
            --accent-clr: #5e63ff;
            --secondary-clr: #555;
        }

        /* body.dark {
            --base-clr: #11121a;
            --line-clr: #42434a;
            --hover-clr: #222533;
            --text-clr: #e6e6ef;
            --accent-clr: #5e63ff;
            --secondary-clr: #b0b3c1;
        } */

        * {
            margin: 0;
            padding: 0;
        }

        html {
            font-family: Popins, 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.5rem;
        }

        body {
            min-height: 100vh;
            min-height: 100dvh;
            background-color: var(--base-clr);
            color: var(--text-clr);
            display: grid;
            grid-template-columns: auto 1fr;
        }


        #sidebar[data-style="icon-only"] {
            padding: 5px;
            width: 85px;

        }

        #sidebar[data-style="icon-only"] #toggle-sidebar-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0.85em;
        }

        #sidebar[data-style="icon-only"]>ul>li:first-child {
            span {
                display: none;
            }
        }


        #sidebar {
            box-sizing: border-box;
            height: 100vh;
            width: 250px;
            padding: 5px 1em;
            background-color: var(--base-clr);
            border-right: 1px solid var(--line-clr);

            transition: 300ms ease-in-out;

            overflow: hidden;
            position: sticky;
            top: 0;
            align-self: start;
            text-wrap: nowrap;
        }

        #sidebar ul {
            list-style: none;
        }

        #sidebar>ul>li:first-child {
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;

            .logo {
                font-weight: 600;
            }

            >:last-child {
                background: none;
                border: none;
                transition: 300ms ease-in-out;
            }
        }

        #sidebar ul li.active a {
            color: var(--accent-clr);

            svg {
                fill: var(--accent-clr);
            }
        }

        .dropdown-btn.active span {
            color: var(--accent-clr);

        }

        .dropdown-btn.active svg:first-child,
        .dropdown-btn.active svg:last-child {
            fill: var(--accent-clr) !important;
        }


        #sidebar a,
        #sidebar .dropdown-btn,
        #sidebar .logo {
            border-radius: .5em;
            padding: .85em;
            text-decoration: none;
            color: var(--text-clr);
            display: flex;
            align-items: center;
            gap: 1em;
        }

        .dropdown-btn {
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            font: inherit;
            cursor: pointer;
        }

        #sidebar svg {
            flex-shrink: 0;
            fill: var(--text-clr);
            transition: transform 0.3s ease;

        }

        #sidebar a span,
        #sidebar .dropdown-btn span {
            flex-grow: 1;
        }

        #sidebar a:hover,
        #sidebar .dropdown-btn:hover {
            background-color: var(--hover-clr);
        }

        #sidebar .sub-menu {
            display: grid;
            grid-template-rows: 0fr;
            transition: 300ms ease-in-out;
            place-content: center;

            >div {
                overflow: hidden;
            }
        }

        #sidebar .sub-menu.show {
            grid-template-rows: 1fr;
        }

        button.rotate svg:last-child {
            transform: rotate(180deg);
        }

        @media(max-width: 800px) {
            body {
                grid-template-columns: 1fr;
            }

            main {
                padding: 2em 1em 6opx 1em;
            }

            .container {
                border: none;
                padding: 0;

            }

            #sidebar {
                height: 60px;
                width: 100%;
                border-right: none;
                border-top: 1px solid var(--line-clr);
                padding: 0;
                position: fixed;
                top: unset;
                bottom: 0;

                >ul {
                    padding: 0;
                    display: grid;
                    grid-auto-columns: 60px;
                    grid-auto-flow: column;
                    align-items: center;
                    overflow-x: scroll;

                }

                ul li {
                    height: 100%;
                }

                ul a,
                ul .dropdown-btn {
                    width: 60px;
                    height: 60px;
                    padding: 0;
                    margin: 0;
                    border-radius: 0;
                    justify-content: center;

                }

                ul li span,
                ul li:first-child,
                .dropdown-btn svg:last-child {
                    display: none;
                }

                ul li .sub-menu.show {
                    position: fixed;
                    bottom: 60px;
                    left: 0;
                    box-sizing: border-box;
                    height: 60px;
                    width: 100%;
                    background-color: var(--hover-clr);
                    border-top: 1px solid var(--line-clr);
                    display: flex;
                    justify-content: center;

                    >div {
                        overflow-x: auto;
                    }

                    li {
                        display: inline-flex;

                        width: auto;
                        height: 60px;
                    }

                    a {
                        box-sizing: border-box;
                        padding: 1em;
                        width: auto;
                        justify-content: center;
                    }
                }

            }
        }

        @media (min-width: 1440px) and (max-width:1800px) {
            .container {
                max-width: 1160px;
                margin: 0 auto;
            }
        }

        @media (min-width: 1801px) and (max-width:4000px) {
            .container {
                max-width: 1360px;
                margin: 0 auto;
            }
        }




        main {
            padding: min(30px, 7%);
        }

        main h2 {
            padding-bottom: 10px;
        }

        main p {
            color: var(--secondary-clr);
            border-radius: 1em;
            margin-bottom: 20px;
        }

        .container {
            border: 1px solid var(--line-clr);
            border-radius: 1em;
            margin-bottom: 20px;
            padding: 40px 20px;
        }
    </style>

    <style>
        .navbar img {
            object-fit: cover;
        }

        .dropdown-menu {
            min-width: 250px;
        }
    </style>

</head>

<body class="dark">

    <nav id="sidebar">
        <ul>
            <li class="header_title">
                <span class="logo">
                    QMS
                </span>
                <button id="toggle-sidebar-btn" onclick="toggleSidebar()"><svg xmlns="http://www.w3.org/2000/svg"
                        height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f">
                        <path
                            d="M440-240 200-480l240-240 56 56-183 184 183 184-56 56Zm264 0L464-480l240-240 56 56-183 184 183 184-56 56Z" />
                    </svg></button>
            </li>

            <li class="">
                <a href="{{ route('dashboard') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                        fill="#1f1f1f">
                        <path
                            d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h440l200 200v440q0 33-23.5 56.5T760-120H200Zm0-80h560v-400H600v-160H200v560Zm80-80h400v-80H280v80Zm0-320h200v-80H280v80Zm0 160h400v-80H280v80Zm-80-320v160-160 560-560Z" />
                    </svg><span>Dashboard</span></a>
            </li>
            <li>
                <button class="dropdown-btn" onclick="toggleSubmenu(this)">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                        fill="#1f1f1f">
                        <path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z" />
                    </svg>
                    <span>create</span>
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                        fill="#1f1f1f">
                        <path d="M480-344 240-584l56-56 184 184 184-184 56 56-240 240Z" />
                    </svg>
                </button>
                <ul class="sub-menu">
                    <div>
                        <li>
                            <a href="{{ route('customer.index') }}">Customer</a>
                        </li>
                        <li>
                            <a href="{{ route('product.index') }}">products</a>
                        </li>
                        <li>
                            <a href="{{ route('terms.index') }}">terms & conditions</a>
                        </li>
                        <li>
                            <a href="{{ route('category.index') }}">Categories</a>
                        </li>
                    </div>
                </ul>
            </li>
            <li class="">
                <a href="{{ route('logout') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                        fill="#1f1f1f">
                        <path
                            d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h440l200 200v440q0 33-23.5 56.5T760-120H200Zm0-80h560v-400H600v-160H200v560Zm80-80h400v-80H280v80Zm0-320h200v-80H280v80Zm0 160h400v-80H280v80Zm-80-320v160-160 560-560Z" />
                    </svg><span>logout</span></a>
            </li>
        </ul>
    </nav>
    <main>
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4 py-3">
            <div class="container-fluid">
                {{-- Left side: Dynamic title --}}
                <h4 class="mb-0 fw-semibold text-primary">
                    @yield('page_title', 'Dashboard')
                </h4>

                {{-- Right side --}}
                <div class="d-flex align-items-center ms-auto">
                    {{-- Notification bell --}}
                    <div class="dropdown me-4">
                        <a href="#" class="text-decoration-none text-dark position-relative" id="notifDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell fs-4"></i>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                3
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notifDropdown">
                            <li class="dropdown-header fw-semibold">Notifications</li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">New quotation request received</a></li>
                            <li><a class="dropdown-item" href="#">Quotation #102 updated</a></li>
                            <li><a class="dropdown-item" href="#">Your profile was viewed</a></li>
                        </ul>
                    </div>

                    {{-- User Profile --}}
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none" id="profileDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ Auth::user()->profile_picture ?? asset('images/profile.jpg') }}" alt="Profile"
                                class="rounded-circle me-2" width="40" height="40">
                            <span class="fw-semibold text-dark">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileDropdown">
                            <li><a class="dropdown-item" href="#">View Profile</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                @csrf
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}">Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        @yield('content')

    </main>
    <script>
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 4000);
    </script>
    <script>
        const sidebar = document.getElementById('sidebar');

        function toggleSubmenu(element) {
            const submenu = element.nextElementSibling;
            const isOpen = submenu.classList.contains(
                'show'
            );
            closeAllSubmenu();

            if (!isOpen) {
                if (sidebar.getAttribute('data-style') == 'icon-only') {
                    sidebar.setAttribute('data-style', 'full');
                }
                element.classList.add('active');
                element.classList.add('rotate');
                submenu.classList.add('show');
            }
        }

        function closeAllSubmenu() {
            document.querySelectorAll('.dropdown-btn').forEach(el => el.classList.remove('rotate'));
            document.querySelectorAll('#sidebar > ul > li').forEach(li => li.classList.remove('active'));
            document.querySelectorAll('#sidebar > ul > li > button').forEach(li => li.classList.remove('active'));

            sidebar
                .querySelectorAll('.sub-menu')
                .forEach(el => el.classList.remove('show'));
        }

        function toggleSidebar() {
            closeAllSubmenu()
            if (sidebar.getAttribute('data-style') == 'icon-only') {
                sidebar.setAttribute('data-style', 'full');
            } else {
                sidebar.setAttribute('data-style', 'icon-only');

            }
        }


        document.querySelectorAll('#sidebar > ul > li > a').forEach(link => {
            link.addEventListener('click', function(e) {
                document.querySelectorAll('#sidebar > ul > li').forEach(li => li.classList.remove(
                    'active'));
                this.parentElement.classList.add('active');
            });
        });
    </script>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    @livewireScripts
    @include('partials.toaster_config')
    @include('partials.select2')

</body>

</html>
