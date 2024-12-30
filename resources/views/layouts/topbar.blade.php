<div class="header py-4">
    <div class="container">
        <div class="d-flex">
            <a class="header-brand" href="#">
                <!-- First Image -->
                <img src="{{ asset('asset/image/Kementerian_Pendidikan_Malaysia_logo.png')}}" style="width: 100px; height: 50px; margin-right: 10px;" alt="Logo">
                <!-- Second Image -->
                <img src="{{ asset('asset/image/KITA@BPSM.png')}}" style="width: 100px; height: 50px; margin-right: 10px;" alt="Logo">
            </a>
            <h5 class="py-4 align-items-center">SISTEM PENGURUSAN BPSM</h5>
            <div class="py-4 d-flex order-lg-2 ml-auto">
                <div class="dropdown">
                    <a href="#" class="nav-link pr-0 leading-none dropdown-toggle" data-bs-toggle="dropdown">
                        <span class="avatar" style="background-image: url('{{ asset('asset/image/undraw_profile.svg') }}')"></span>
                        <span class="ml-2 d-none d-lg-block">
                            <span class="text-default">{{ Auth::user()->Fullname }}</span>
                        </span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-arrow" style="position: absolute; top: -10px; right: 15px; width: 0; height: 0; border-left: 5px solid transparent; border-right: 5px solid transparent; border-bottom: 5px solid white;"></div>
                        <a class="dropdown-item" href="{{ route('dashboard') }}">Halaman Utama</a>
                        <a class="dropdown-item" href="{{ route('profile.edit', Auth::user()->id) }}">Profil</a>
                        <a class="dropdown-item" href="{{ route('profile.password_reset', Auth::user()->id) }}">Tukar Kata Laluan</a>
                        <a class="dropdown-item" href="{{ route('logout') }}">
                            Log Keluar
                        </a>
                    </div>
                </div>

            </div>
            <a href="#" class="header-toggler d-lg-none ml-3 ml-lg-0" data-toggle="collapse" data-target="#headerMenuCollapse">
                <span class="header-toggler-icon"></span>
            </a>
        </div>
    </div>
</div>