<!doctype html>
<html lang="en" dir="ltr">

<head>
    @include('layouts.head')
</head>

<body class="">

    <div class="page">
        <div class="page-main">
            @include('layouts.topbar')
            <div class="my-3 my-md-5">
                <div class="container">
                    <div class="page-header">
                        <h1 class="page-title">
                            Selamat Datang, {{Auth::user()->Fullname}}
                        </h1>
                    </div>
                    <div class="row row-cards">

                        <!--profile-->
                        <div class="col-lg-3">
                            <div class="card card-profile">
                                <div class="card-header" style="background-image: url('{{ asset('asset/template/images/profile_bg.jpg')}}');"></div>
                                <div class="card-body text-center">
                                    @if($user->Profile_Picture == null)
                                    <img class="card-profile-img" src="{{ asset('asset/image/undraw_profile.svg') }}">
                                    @else
                                    <img class="card-profile-img" src="{{ asset('public/storage/profile/'.$user->Profile_Picture) }}">
                                    @endif
                                    
                                    <h6 class="mb-3">{{Auth::user()->Fullname}}</h6>
                                    <h6>{{ Auth::user()->Email}}</h6>
                                    <p class="mb-3" style="font-size: 11px;">
                                        {{ Auth::user()->Department}}
                                        <br>
                                        ( {{ Auth::user()->Unit }} )
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!--announcement-->
                        <div class="col-lg-9">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-status bg-blue"></div>
                                        <div class="card-header">
                                            <h3 class="card-title">Makluman</h3>
                                        </div>
                                        <div class="card-body">
                                            Selamat datang ke Sistem Pengurusan BPSM !
                                        </div>
                                    </div>
                                </div>
                                @yield('content')
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <footer class="footer">
            @include('layouts.footer')
        </footer>
    </div>
</body>

</html>