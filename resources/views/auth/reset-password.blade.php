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
                                <img src="https://www.moe.gov.my/storage/files/shares/1704770967_jata-negara-svg.svg" width="40%" class="mx-1">
                                <label style="font-size:10px;">KEMENTERIAN PENDIDIKAN MALAYSIA</label>
                                <br><br>
                                <h4>SISTEM PENGURUSAN BPSM</h4>
                                <br>
                                <h4>KEMASKINI PROFIL</h4>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    @if(session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                    @elseif(session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                    @endif

                                    <!-- Password reset form -->
                                    <form method="POST" action="{{ route('password.store') }}">
                                        @csrf

                                        <!-- Hidden token input -->
                                        <input type="hidden" name="token" value="{{ $token }}">

                                        <!-- IC input -->
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label for="Employee_ID">No. Kad Pengenalan</label>
                                                <input type="number" class="form-control" value="{{ $user->Employee_ID }}" disabled>
                                                <input id="Employee_ID" type="number" class="form-control @error('Employee_ID') is-invalid @enderror" name="Employee_ID" value="{{ $user->Employee_ID }}" hidden>
                                            </div>
                                        </div>
                                        <!-- Email input -->
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label for="Email">Emel</label>
                                                <input id="Email" type="text" class="form-control @error('Email') is-invalid @enderror" name="Email" value="{{ $user->Email }}" disabled>
                                            </div>
                                        </div>

                                        <!-- Jawatan input -->
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label for="Position">Jawatan, Skim & Gred</label>
                                                <input id="Position" type="text" class="form-control" name="Position" value="{{ old('Position',$user->Position) }}" required autofocus>
                                                <span class="text-danger">
                                                    @error('Position')
                                                    {{$message}}
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Phone -->
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label for="Telephone">No. Telefon</label>
                                                <input id="Telephone" type="text" class="form-control" name="Telephone" value="{{ old('Telephone',$user->Telephone) }}" required autofocus>
                                                <span class="text-danger">
                                                    @error('Telephone')
                                                    {{$message}}
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Password input -->
                                        <div class="form-group">
                                            <label for="password">Kata Laluan</label>
                                            <input type="password" name="password" class="form-control" placeholder="Masukkan kata laluan" id="passhide" required>

                                            <!-- An element to toggle between password visibility -->
                                            <input type="checkbox" onclick="passhideFunction()"> <small>Lihat Kata Laluan</small>
                                            <span class="text-danger">
                                                @error('password')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>

                                        <!-- Confirm Password input -->
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label for="password-confirm">Sahkan Kata Laluan Baru</label>
                                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Sila Sahkan Kata Laluan Baru" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-block btn-primary">
                                                Set Semula Kata Laluan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="text-center">
                                    <a class="small" href="{{ route('login.form')}}">Log Masuk</a> | <a class="small" href="#">Terlupa Kata Laluan?</a>
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