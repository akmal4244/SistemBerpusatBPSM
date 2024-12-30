@extends('layouts.app_user')

@section('content')


<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Kemaskini Butiran Pengguna</h6>
    </div>
    <div class="card-body">
        <form method="post" action="{{route('user.mgt.edit.submit', $data->id)}}" enctype="multipart/form-data">
            @csrf
            <div class="form-group row d-flex justify-content-center">
                @if($data->Profile_Picture == null)
                <img src="{{ asset('asset/image/undraw_profile.svg') }}" alt="Logo" width="20%">
                @else
                <img src="{{ asset('public/storage/profile/'.$data->Profile_Picture) }}" alt="Logo" width="20%">
                @endif
            </div>
            <div class="form-group row d-flex justify-content-center">
                @if($data->Profile_Picture)
                <label class="form-check-label">
                    <input class="form-check-input" id="remove_image" name="remove_image" type="checkbox">
                    <span class="form-check-sign">Padam Gambar Sedia Ada</span>
                </label>
                @else
                <input type="file" class="form-control form-control-user" id="Profile_Picture" name="Profile_Picture" style="width:300px;">

                @endif
            </div>

            <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <span>Nama Penuh</span>
                    <input type="text" class="form-control form-control-user" id="Fullname" name="Fullname" value="{{ $data->Fullname}}" required>
                </div>
                <div class="col-sm-6">
                    <span>No. Kad Pengenalan</span>
                    <input type="number" class="form-control form-control-user" id="Employee_ID" name="Employee_ID" value="{{ $data->Employee_ID}}" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <span>Emel</span>
                    <input type="text" class="form-control form-control-user" id="Email" name="Email" value="{{ $data->Email}}" required>
                </div>
                <div class="col-sm-6">
                    <span>Peranan</span>
                    <select class="form-control form-control-user" id="Role" name="Role" required>
                        <option value="{{ $data->Role}}" selected>{{ $data->Role}}</option>
                        <option value="Pengguna" >Pengguna</option>
                        <option value="Pentadbir" >Pentadbir</option>
                        <option value="Superadmin" >Superadmin</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <span>No. Telefon</span>
                    <input type="number" class="form-control form-control-user" id="Telephone" name="Telephone" value="{{ $data->Telephone}}" required>
                </div>
                <div class="col-sm-6">
                    <span>Position</span>
                    <input type="text" class="form-control form-control-user" id="Position" name="Position" value="{{ $data->Position}}" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <span>Cawangan</span>
                    <select class="form-control form-control-user" id="Department" name="Department" required>
                        <option value="{{ $data->Department}}" selected>{{ $data->Department}}</option>
                        @foreach($department as $depart)
                        <option value="{{ strtoupper($depart->title) }}">{{ strtoupper($depart->title) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6">
                    <span>Unit</span>
                    <select class="form-control form-control-user" id="Unit" name="Unit" required>
                        <option value="{{ $data->Unit}}" selected>{{ $data->Unit}}</option>
                        @foreach($unit as $units)
                        <option value="{{ strtoupper($units->title) }}">{{ strtoupper($units->title) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <span>Status Aktif</span>
                    <select class="form-control form-control-user" id="Status_Aktif" name="Status_Aktif" required>
                        <option value="{{ $data->Status_Aktif}}" selected>@if($data->Status_Aktif == '1') Aktif  @else Tidak Aktif @endif</option>
                        <option value="1" >Aktif</option>
                        <option value="0" >Tidak Aktif</option>                     
                    </select>
                </div>
                <div class="col-sm-6">
                    <span>Status Set Semula Kata Laluan</span>
                    <select class="form-control form-control-user" id="needs_password_reset" name="needs_password_reset" required>
                        <option value="{{ $data->needs_password_reset}}" selected>@if($data->needs_password_reset == '1') Ya  @else Tidak @endif</option>
                        <option value="1" >Ya</option>
                        <option value="0" >Tidak</option>                     
                    </select>
                </div>
            </div>

            <div class="form-group row d-flex justify-content-end">
                <div class="col-sm-6 mb-3 mb-sm-0"></div>
                <div class="col-sm-6 mb-3 mb-sm-0 d-flex justify-content-end">
                    <button type="button"  onclick="confirm(event)" class="btn btn-primary mr-2">Simpan</button>
                    <a href="{{ route('user.mgt.list') }}">
                        <button type="button" class="btn btn-warning">Kembali</button>
                    </a>
                </div>
            </div>

        </form>

    </div>
</div>

<script>
        function confirm(event) {
            event.preventDefault(); // Prevent the form from submitting immediately
            Swal.fire({
                title: "Adakah anda pasti?",
                text: "Anda tidak boleh membatalkan perubahan ini!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya",
                cancelButtonText: "Tidak"
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.closest('form').submit(); // Submit the form after confirmation
                    Swal.fire({
                        title: "Dikemaskini!",
                        text: '{{ session('success') }}',
                        icon: "success"
                    });
                }
            });
        }
    </script>
@endsection