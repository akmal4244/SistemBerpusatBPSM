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
                        <div class="col-lg-12>
                            <div class=" d-flex flex-column justify-content-center align-items-center">
                            <div class="d-flex justify-content-center">
                                <img src="asset/image/Kementerian_Pendidikan_Malaysia_logo.png" width="20%" class="mx-1">
                            </div>

                            <!-- <img src="asset/image/KITA@BPSM.png" width="40%" class="mx-2"> -->
                            <br>
                            <div class="text-center" style="color:black;">
                                <h4>Sistem Pengurusan BPSM</h4>
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <div class="col-lg-8 col-md-6">
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

                                        <form action="{{route('register.submit')}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            @if (Session::has('success'))
                                            <div class="alert alert-success">
                                                {{Session::get('success')}}
                                            </div>
                                            @endif
                                            @if (Session::has('fail'))
                                            <div class="alert alert-danger">
                                                {{Session::get('fail')}}
                                            </div>
                                            @endif
                                            <div class="form-group">
                                                <label for="name">Nama Penuh</label>
                                                <input type="text" name="name" class="form-control" placeholder="MOHAMAD NURAKMAL BIN AB RASHID">
                                                <span class="text-danger">
                                                    @error('name')
                                                    {{$message}}
                                                    @enderror
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Kad Pengenalan (Tanpa "-")</label>
                                                <input type="text" name="ic" class="form-control" placeholder="Tanpa '-'">
                                                <span class="text-danger">
                                                    @error('name')
                                                    {{$message}}
                                                    @enderror
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Emel</label>
                                                <input type="email" name="email" class="form-control" placeholder="nurakmal@moe.gov.my">
                                                <span class="text-danger">
                                                    @error('email')
                                                    {{$message}}
                                                    @enderror
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Jawatan, Skim & Gred</label>
                                                <input type="text" name="position" class="form-control" placeholder="PEGAWAI TEKNOLOGI MAKLUMAT F41">
                                                <span class="text-danger">
                                                    @error('name')
                                                    {{$message}}
                                                    @enderror
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label for="department">Cawangan</label>
                                                <select class="form-control" id="department" name="department">
                                                    @foreach($department as $depart)
                                                    <option value="{{ $depart->title }}">{{ $depart->title2  . ' - ' . $depart->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="unit">Unit</label>
                                                <select class="form-control" id="unit" name="unit">
                                                    @foreach($unit as $units)
                                                    <option value="{{ $units->title }}">{{ $units->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>                                            

                                            <div class="form-group">
                                                <label for="name">No. Telefon</label>
                                                <input type="text" name="phone" class="form-control" placeholder="0123456789">
                                                <span class="text-danger">
                                                    @error('name')
                                                    {{$message}}
                                                    @enderror
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label for="password">Kata Laluan</label>
                                                <input type="password" name="password" class="form-control">
                                                <span class="text-danger">
                                                    @error('password')
                                                    {{$message}}
                                                    @enderror
                                                </span>
                                            </div>

                                            <br>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-block btn-success">Daftar</button>
                                            </div>
                                        </form>
                                        <hr>
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