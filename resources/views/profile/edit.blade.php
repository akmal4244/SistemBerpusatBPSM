@extends('layouts.app')

@section('content')
<!--update profile-->

<div class="col-lg-12">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-status bg-blue"></div>
                <div class="card-header">
                    <h3 class="card-title">Makluman</h3>
                </div>
                <div class="card-body">
                    <form action="{{route('profile.update', $user->id)}}" method="post" enctype="multipart/form-data">

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


                        <div class="form-group row d-flex justify-content-center">
                            <div class="col-lg-12 mb-sm-0">
                                <center>
                                    @if($user->Profile_Picture == null)
                                    <img src="{{ asset('asset/image/undraw_profile.svg') }}" alt="Logo" width="20%">
                                    @else
                                    <img src="{{ asset('public/storage/profile/'.$user->Profile_Picture) }}" alt="Logo" width="20%">
                                    @endif
                                </center>
                            </div>
                        </div>


                        <div class="form-group row d-flex justify-content-center">
                            <div class="col-lg-12 mb-sm-0">
                                <center>
                                    @if($user->Profile_Picture)
                                    <label class="form-check-label">
                                        <input class="form-check-input" id="remove_image" name="remove_image" type="checkbox">
                                        <span class="form-check-sign">Padam Gambar Sedia Ada</span>
                                    </label>
                                    @else
                                    <input type="file" class="form-control form-control-user" id="Profile_Picture" name="Profile_Picture" style="width:300px;">
                                    @endif
                                </center>
                            </div>
                        </div>
                        <hr>

                        <div class="form-group">
                            <label for="Employee_ID">Kad Pengenalan (Tanpa "-")</label>
                            <input type="text" class="form-control" value="{{ old('Employee_ID', $user->Employee_ID) }}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="Fullname">Nama Penuh</label>
                            <input type="text" name="Fullname" class="form-control" value="{{ old('Fullname', $user->Fullname) }}" required>
                            <span class="text-danger">
                                @error('Fullname')
                                {{$message}}
                                @enderror
                            </span>
                        </div>

                        <div class="form-group">
                            <label for="Email">Emel</label>
                            <input type="email" name="Email" class="form-control" value="{{ old('Email', $user->Email) }}" required>
                            <span class="text-danger">
                                @error('Email')
                                {{$message}}
                                @enderror
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="Position">Jawatan, Skim & Gred</label>
                            <input type="text" name="Position" class="form-control" value="{{ old('Position', $user->Position) }}" required>
                            <span class="text-danger">
                                @error('Position')
                                {{$message}}
                                @enderror
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="department">Cawangan</label>
                            <select class="form-control" id="Department" name="Department" required>
                                <option value="{{ old('Department', $user->Department) }}" selected>{{ $user->Department}}</option>
                                @foreach($department as $depart)
                                <option value="{{ $depart->title }}">{{ $depart->title }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger">
                                @error('Department')
                                {{$message}}
                                @enderror
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="unit">Unit</label>
                            <select class="form-control" id="Unit" name="Unit">
                                <option value="{{ old('Unit', $user->Unit) }}" selected>{{ $user->Unit}}</option>
                                @foreach($unit as $units)
                                <option value="{{ $units->title }}">{{ $units->title }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger">
                                @error('Unit')
                                {{$message}}
                                @enderror
                            </span>
                        </div>

                        <div class="form-group">
                            <label for="name">No. Telefon</label>
                            <input type="text" name="Telephone" class="form-control" value="{{ old('Telephone', $user->Telephone) }}" required>
                            <span class="text-danger">
                                @error('Telephone')
                                {{$message}}
                                @enderror
                            </span>
                        </div>

                        <br>
                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn-primary">Kemaskini</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>




@endsection