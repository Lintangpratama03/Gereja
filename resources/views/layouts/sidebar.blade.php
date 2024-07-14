<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-ticket-alt"></i>
        </div>
        <div class="sidebar-brand-text mx-3">GBT KRISTUS JURUSELAMAT</div>
    </a>
    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    @if (Auth::user()->level == 'Admin')
        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('home') }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span></a>
        </li>
        <!-- Nav Item - Data Master Menu -->
        <li class="nav-item">
            <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true"
                aria-controls="collapsePages">
                <i class="fas fa-folder"></i>
                <span>Master Ibadah Rutin</span>
            </a>
            <div id="collapsePages" class="collapse show" aria-labelledby="headingPages"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="{{ route('rute.index') }}">Jadwal</a>
                    <a class="collapse-item" href="{{ route('transportasi.index') }}">Tempat</a>
                    <a class="collapse-item" href="{{ route('category.index') }}">Nama Ibadah</a>
                </div>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePages2" aria-expanded="true"
                aria-controls="collapsePages">
                <i class="fas fa-folder"></i>
                <span>Master Acara</span>
            </a>
            <div id="collapsePages2" class="collapse show" aria-labelledby="headingPages"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="{{ route('list.index') }}">Daftar Pengajuan Acara</a>
                    <a class="collapse-item" href="{{ route('acara.index') }}">Kategori Acara</a>
                </div>
            </div>
        </li>
        <!-- Nav Item - Verifikasi -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('petugas') }}">
                <i class="fas fa-clipboard-check"></i>
                <span>Verifikasi</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.index') }}">
                <i class="fas fa-user"></i>
                <span>User</span></a>
        </li>
        <!-- Nav Item - Transaksi -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('transaksi') }}">
                <i class="fas fa-shopping-cart"></i>
                <span>Transaksi</span></a>
        </li>
    @endif

    @if (Auth::user()->level == 'Pendeta')
        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('homependeta') }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span></a>
        </li>
        <!-- Nav Item - Data Master Menu -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('validasi.index') }}">
                <i class="fas fa-home"></i>
                <span>Validasi Jadwal</span></a>
        </li>
    @endif
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
