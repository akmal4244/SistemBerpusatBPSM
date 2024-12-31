<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.head')

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-3">
                    <!-- Nested Row within Card Body -->
                    <div class="row" style="margin:4%;">
                        <div class="col-lg-12">
                            <div class="d-flex flex-column justify-content-center align-items-center">
                            <div class="d-flex justify-content-center">
                                <img src="{{asset('asset/image/Kementerian_Pendidikan_Malaysia_logo.png')}}" width="40%" class="mx-1">
                            </div>

                            <!-- <img src="asset/image/KITA@BPSM.png" width="40%" class="mx-2"> -->
                            <br>
                            <div class="text-center" style="color:black;">
                                <h4>SISTEM PENGURUSAN BPSM</h4>
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <div class="col-lg-12">
                                    <div class="align-items-center">
                                        @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endif

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
                                    <form method="POST" action="{{ route('password.forgot.store') }}">
                                        @csrf

                                        <div class="row">
                                        <!-- IC input -->
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label for="Employee_ID">No. Kad Pengenalan</label>
                                                <input type="number" class="form-control" name="Employee_ID" value="{{ old('Employee_ID') }}" required>
                                            </div>
                                        </div>
                                        
                                        <!-- Email input -->
                                        <!-- <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label for="Email">Emel</label>
                                                <input id="Email" type="text" class="form-control @error('Email') is-invalid @enderror" name="Email" >
                                            </div>
                                        </div> -->


                       
                                        <!-- <div class="form-group">
                                            <label for="password">Kata Laluan</label>
                                            <input type="password" name="password" class="form-control" placeholder="Masukkan kata laluan" id="passhide" required>

                         
                                            <input type="checkbox" onclick="passhideFunction()"> <small>Lihat Kata Laluan</small>
                                            <span class="text-danger">
                                                @error('password')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>

                                    
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label for="password-confirm">Sahkan Kata Laluan Baru</label>
                                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Sila Sahkan Kata Laluan Baru" required>
                                            </div>
                                        </div> -->

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-block btn-primary">
                                                Set Semula Kata Laluan
                                            </button>
                                        </div>
                                    </div>
                                    </form>
                                    <br>
                                        <div class="text-center">

                                        </div>
                                        <div class="text-center">
                                            <a class="small" href="{{ route('login.form') }}">Log Masuk</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>



    @include('layouts.footer')
</body>

</html>