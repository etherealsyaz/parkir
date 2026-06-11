<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIJA PARKING</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root{
            --primary:#d63384;
        }

        body{
            background:#f8f9fa;
            font-family:'Segoe UI',sans-serif;
        }

        .sidebar{
            width:220px;
            min-height:100vh;
            background:#fff;
            position:fixed;
            border-right:1px solid #eee;
        }

        .sidebar .brand{
            color:#344767;
            font-weight:700;
            padding:20px 25px;
            font-size:18px;
            display: flex;
            align-items: center;
        }

        .sidebar .brand img{
            object-fit: contain;
        }

        .sidebar .nav-link{
            color:#67748e;
            margin:8px 15px;
            border-radius:12px;
            padding:12px 15px;
        }

        .sidebar .nav-link.active{
            background:linear-gradient(90deg,#ff00aa,#a100ff);
            color:#fff !important;
        }

        .sidebar .nav-link:hover{
            background:#f2f2f2;
        }

        .sidebar .section-title{
            font-size:12px;
            color:#8392ab;
            padding:15px 20px;
            text-transform:uppercase;
        }

        .main-content{
            margin-left:220px;
            padding:25px;
        }

        .topbar{
            background:#fff;
            border-radius:20px;
            padding:20px 30px;
            box-shadow:0 0 20px rgba(0,0,0,.05);
            display:flex;
            justify-content:space-between;
            align-items:center;
            color:#344767;
        }

        .topbar small{
            color:#8392ab;
        }

        .card{
            border:none;
            border-radius:20px;
            box-shadow:0 0 20px rgba(0,0,0,.05);
        }

        .btn-primary-custom{
            background:linear-gradient(90deg,#ff00aa,#a100ff);
            border:none;
            color:#fff !important;
            border-radius:10px;
        }

        .card-title-custom{
            color:#d63384;
            font-weight:bold;
        }

        .logout-btn{
            background:#fff;
            color:#344767;
            border:1px solid #ddd;
            border-radius:10px;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <div class="brand">
            <img src="{{ asset('images/parkir.png') }}" alt="Logo" class="me-2" style="height: 30px; width: auto;">
            <span>SIJA PARKING</span>
        </div>

        <nav>
            <a href="{{ route('location.index') }}"
               class="nav-link {{ request()->routeIs('location.*') ? 'active' : '' }}">
                <i class="fas fa-map-marker-alt me-2"></i>Location
            </a>

            <a href="{{ route('transaction.index') }}"
               class="nav-link {{ request()->routeIs('transaction.*') ? 'active' : '' }}">
                <i class="fas fa-exchange-alt me-2"></i>Transaction
            </a>

            <a href="{{ route('vehicle-type.index') }}"
               class="nav-link {{ request()->routeIs('vehicle-type.*') ? 'active' : '' }}">
                <i class="fas fa-car me-2"></i>Vehicle Type
            </a>

            <a href="{{ route('report.location') }}"
               class="nav-link {{ request()->routeIs('report.location') ? 'active' : '' }}">
                <i class="fas fa-chart-bar me-2"></i>Location Report
            </a>

            <a href="{{ route('report.transaction') }}"
               class="nav-link {{ request()->routeIs('report.transaction') ? 'active' : '' }}">
                <i class="fas fa-file-invoice me-2"></i>Transaction Report
            </a>
        </nav>
    </div>

    <div class="main-content">

        <div class="topbar">
            <div>
                <small>Pages / @yield('breadcrumb')</small><br>
                <strong>@yield('page-title')</strong>
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                @yield('topbar-actions')

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Sign Out
                    </button>
                </form>
            </div>
        </div>

        <div class="pt-3">
            @yield('content')
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts')
</body>
</html>