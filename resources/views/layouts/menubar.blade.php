<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ route('/home') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ route('/home') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link menu-link @yield('nav_active_dashboard')" href="{{ route('/home') }}">
                        <i class="ri-dashboard-line"></i><span data-key="t-widgets">Dashboard</span>
                    </a>
                </li>
                {{-- start authentication --}}
                @if(Gate::check('menu_pengajuan') || Gate::check('menu_persetujuan') || Gate::check('menu_pengesahan') || Gate::check('menu_query_rb') || Gate::check('menu_otorisasi'))
                @can('menu_pengajuan')
                <li class="nav-item">
                    <a class="nav-link menu-link @yield('nav_active_rb_head')" href="#sidebarDashboards1" data-bs-toggle="collapse"
                        role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="ri-calculator-line"></i> <span data-key="t-dashboards">Pengajuan RB</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarDashboards1">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('pengajuan.baru')}}" class="nav-link  menu-link @yield('nav_active_pengajuan_baru')">
                                    <i class="ri-file-add-line"></i> Pengajuan Baru
                                </a>
                            </li>
                            {{-- <li class="nav-item">
                                <a href="{{ route('pengajuan.koreksi')}}" class="nav-link  menu-link @yield('nav_active_pengajuan_koreksi')">
                                    <i class="ri-refund-2-line"></i> Koreksi Pengajuan
                                </a>
                            </li> --}}
                            <li class="nav-item">
                                <a href="{{ route('pengajuan.list')}}" class="nav-link  menu-link @yield('nav_active_pengajuan_list')">
                                    <i class="ri-history-fill"></i> Riwayat Pengajuan 
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endcan
                @can('menu_persetujuan')
                <li class="nav-item">
                    <a class="nav-link menu-link @yield('nav_active_persetujuan')" href="#persetujuan" data-bs-toggle="collapse"
                        role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="ri-check-double-line"></i><span data-key="t-dashboards">Persetujuan</span>
                    </a>
                    <div class="collapse menu-dropdown" id="persetujuan">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('persetujuan')}}" class="nav-link @yield('nav_active_persetujuan_list')">
                                    <i class="ri-list-unordered"></i> List Persetujuan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('persetujuan.riwayat')}}" class="nav-link @yield('nav_active_persetujuan_riwayat')">
                                    <i class="ri-history-fill"></i> Riwayat Persetujuan 
                                </a>
                            </li>
                        </ul>
                    </div>
                </li> 
                @endcan
                @can('menu_pengesahan')
                <li class="nav-item">
                    <a class="nav-link menu-link @yield('nav_active_pengesahan')" href="#pengesahan" data-bs-toggle="collapse"
                        role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="ri-survey-line"></i><span data-key="t-dashboards">Pengesahan</span>
                    </a>
                    <div class="collapse menu-dropdown" id="pengesahan">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('pengesahan')}}" class="nav-link @yield('nav_active_pengesahan_list')">
                                    <i class="ri-list-unordered"></i> List Pengesahan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('pengesahan.riwayat')}}" class="nav-link @yield('nav_active_pengesahan_riwayat')">
                                    <i class="ri-history-fill"></i> Riwayat Pengesahan 
                                </a>
                            </li>
                        </ul>
                    </div>
                </li> 
                @endcan
                @can('menu_otorisasi')
                <li class="nav-item">
                    <a class="nav-link menu-link @yield('nav_active_otorisasi')" href="#otorisasi" data-bs-toggle="collapse"
                        role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="ri-wallet-line"></i><span data-key="t-dashboards">Otorisasi</span>
                    </a>
                    <div class="collapse menu-dropdown" id="otorisasi">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('otorisasi')}}" class="nav-link @yield('nav_active_otorisasi_list')">
                                    <i class="ri-bank-card-line"></i> List Otorisasi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('otorisasi.riwayat')}}" class="nav-link @yield('nav_active_otorisasi_riwayat')">
                                    <i class="ri-refund-2-fill"></i> Riwayat Otorisasi 
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endcan
                @endif

                @if(Gate::check('menu_mst_karyawan') || Gate::check('menu_mst_jabatan') || Gate::check('menu_mst_departement') || Gate::check('menu_mst_branch') || Gate::check('menu_mst_jabatan') || Gate::check('menu_mst_bank') || Gate::check('menu_mst_jenis_inventaris') || Gate::check('menu_mst_grup_inventaris') || Gate::check('menu_mst_kategori_masalah')) 
                <li class="nav-item">
                    <a class="nav-link menu-link @yield('nav_active_master_data')" href="#sidebarDashboards2" data-bs-toggle="collapse"
                        role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="ri-archive-drawer-line"></i> <span data-key="t-dashboards">Master Data</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarDashboards2">
                        <ul class="nav nav-sm flex-column">
                            @can('menu_mst_user')
                            <li class="nav-item">
                                <a href="{{ route('user')}}" class="nav-link @yield('nav_active_user')">
                                    <i class="ri-user-settings-line"></i> Autentikasi Pengguna
                                </a>
                            </li>
                            @endcan
                            @can('menu_mst_karyawan')
                            <li class="nav-item">
                                <a href="{{ route('karyawan')}}" class="nav-link @yield('nav_active_karyawan')" data-key="t-analytics">
                                    <i class="ri-user-2-line"></i> Karyawan
                                </a>
                            </li>
                            @endcan
                            @can('menu_mst_jabatan')
                            <li class="nav-item">
                                <a href="{{ route('jabatan')}}" class="nav-link @yield('nav_active_jabatan')" data-key="t-ecommerce">
                                    <i class="ri-hand-coin-line"></i> Jabatan 
                                </a>
                            </li>
                            @endcan
                            @can('menu_mst_departement')
                            <li class="nav-item">
                                <a href="{{ route('departemen')}}" class="nav-link @yield('nav_active_departemen')" data-key="t-crypto">
                                    <i class="ri-node-tree"></i> Departemen 
                                </a>
                            </li>
                            @endcan
                            @can('menu_mst_branch')
                            <li class="nav-item">
                                <a href="{{ route('branch')}}" class="nav-link @yield('nav_active_branch')" data-key="t-projects">
                                    <i class="ri-building-line"></i> Branch 
                                </a>
                            </li>
                            @endcan
                            @can('menu_mst_bank')
                            <li class="nav-item">
                                <a href="{{ route('bank')}}" class="nav-link @yield('nav_active_bank')" data-key="t-projects">
                                    <i class="ri-bank-card-line"></i> Bank 
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endif 
                {{-- /end authentication --}}
                
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
