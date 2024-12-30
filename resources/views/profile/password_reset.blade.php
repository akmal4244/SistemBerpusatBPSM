@extends('layouts.app')

@section('content')
<!--update profile-->

<div class="col-lg-12">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-status bg-blue"></div>
                <div class="card-header">
                    <h3 class="card-title">Tukar Kata Laluan</h3>
                </div>
                <div class="card-body">
                    <form action="{{route('profile.password_reset.submit', $user->id)}}" method="post" enctype="multipart/form-data">

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
                            <label for="old_password">Kata Laluan Lama</label>
                            <input type="password" name="old_password" class="form-control" placeholder="Masukkan kata laluan lama" id="passhide1" required>

                            <!-- An element to toggle between password visibility -->
                            <input type="checkbox" onclick="passhideFunction1()"> <small>Lihat Kata Laluan</small>
                            <span class="text-danger">
                                @error('old_password')
                                {{$message}}
                                @enderror
                            </span>
                        </div>

                        <!-- Password input -->
                        <div class="form-group">
                            <label for="new_password">Kata Laluan Baru</label>
                            <input type="password" name="new_password" class="form-control" placeholder="Masukkan kata laluan" id="passhide" required>

                            <!-- An element to toggle between password visibility -->
                            <input type="checkbox" onclick="passhideFunction()"> <small>Lihat Kata Laluan</small>
                            <span class="text-danger">
                                @error('new_password')
                                {{$message}}
                                @enderror
                            </span>
                        </div>

                        <!-- Confirm Password input -->
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="new_password_confirmation">Sahkan Kata Laluan Baru</label>
                                <input id="new_password_confirmation" type="password" class="form-control" name="new_password_confirmation" placeholder="Sila Sahkan Kata Laluan Baru" required>
                                <span class="text-danger">
                                @error('new_password_confirmation')
                                {{$message}}
                                @enderror
                            </span>
                            </div>
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



<script>
    function passhideFunction1() {
        var x = document.getElementById("passhide1");
        if (x.type === "password1") {
            x.type = "text";
        } else {
            x.type = "password1";
        }
    }
</script>
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

@endsection