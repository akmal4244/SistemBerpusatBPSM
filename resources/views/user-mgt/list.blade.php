@extends('layouts.app_user')

@section('content')


<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Senarai Pengguna</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Gambar</th>
                        <th>Name Penuh</th>
                        <th>Cawangan</th>
                        <th>Unit</th>
                        <th>No.Telefon</th>
                        <th>Status</th>
                        <th>Kemaskini</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 1)
                    @foreach($data as $datas)
                    <tr>
                        <td>{{$i}}</td>
                        <td>
                            <center>
                                @if($datas->Profile_Picture == null)
                                <img src="{{asset('asset/image/undraw_profile.svg')}}" alt="Logo" style="margin-right: 10px;" width="50%">
                                @else
                                <img src="{{asset('public/storage/profile/'.$datas->Profile_Picture )}}" alt="Logo" style="margin-right: 10px;" width="50%">
                                @endif
                            </center>
                        </td>
                        <td>{{$datas->Fullname}}</td>
                        <td>{{$datas->Department}}</td>
                        <td>{{$datas->Unit}}</td>
                        <td>{{$datas->Telephone}}</td>
                        <td>
                            @if($datas->Status_Aktif == '1')
                            <span style="color:blue">AKTIF</span>
                            @else
                            <span style="color:red">TIDAK AKTIF</span>
                            @endif

                        </td>
                        <td>
                            <center>
                                <a href="{{ route('user.mgt.edit', $datas->id)}}">
                                    <button class="btn btn-warning"><i class="fas fa-edit"></i></button>
                                </a>
                            </center>
                        </td>
                    </tr>
                    @php($i++)
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection