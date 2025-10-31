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





    {{-- <style>
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
    </style> --}}


</head>

<body class="dark">


    <main>
        @yield('content')

    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> --}}
    @include('partials.toaster_config')
</body>

</html>
