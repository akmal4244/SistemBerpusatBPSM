<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.head')

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="card o-hidden border-0 shadow-lg my-4">
                <div class="card-body p-3">
                    <!-- Nested Row within Card Body -->
                    <div class="row" style="margin:3%;">

                        <div class=" d-flex flex-column justify-content-center align-items-center">
                            <div class="text-center" style="color:black;">
                                <img src="{{ asset('asset/image/Kementerian_Pendidikan_Malaysia_logo_1.png')}}" width="20%" class="mx-1">
                                <br><br>
                                <h4>SISTEM PENGURUSAN BPSM</h4>
                                <br>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                    <form action="{{route('login.user')}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        @if (Session::has('success'))
                                        <div class="alert alert-success">
                                            {{Session::get('success')}}
                                        </div>
                                        @endif
                                        @if (Session::has('error'))
                                        <div class="alert alert-danger">
                                            {{Session::get('error')}}
                                        </div>
                                        @endif

                                        <div class="form-group">
                                            <label for="Employee_ID">Nombor Kad Pengenalan</label>
                                            <input type="number" name="Employee_ID" class="form-control" placeholder="Tidak perlu masukkan '-'">
                                            <span class="text-danger">
                                                @error('Employee_ID')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Kata Laluan</label>
                                            <input type="password" name="password" class="form-control" placeholder="Masukkan kata laluan" id="passhide">

                                            <!-- An element to toggle between password visibility -->
                                            <input type="checkbox" onclick="passhideFunction()"> <small>Lihat Kata Laluan</small>
                                            <span class="text-danger">
                                                @error('password')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-block btn-primary">Log Masuk</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="text-center">
                                    <a class="small" href="{{ route('register.form')}}">Daftar Akaun Baru!</a> | <a class="small" href="#">Terlupa Kata Laluan?</a>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>

    </div>


    <script>
        function passhideFunction() {
            var x = document.getElementById("passhide");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>

</body>

</html>