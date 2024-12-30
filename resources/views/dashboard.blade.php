@extends('layouts.app')

@section('content')

<h5>Halaman Utama</h5>



<div class="col-lg-6">
    <div class="card p-5 d-flex flex-column align-items-center" style="height: 100px;">

        <div class="d-flex w-100">

            <span class="stamp stamp-md bg-white" style="width: 80px; margin-right: 20px;">
                <img src="{{asset('asset/image/logo-bpsm.png')}}">
            </span>

            <a href="http://10.22.28.183/portal/" style="text-decoration:none; flex-grow: 1;">
                <h4 class="m-0">PORTAL-BPSM</h4>
                <small class="text-muted">PORTAL BPSM</small>
            </a>
        </div>
    </div>
</div>

<div class="col-lg-6">
    <div class="card p-5 d-flex flex-column align-items-center" style="height: 100px;">
        <!-- <div class="card-status bg-blue"></div> -->
        <div class="d-flex w-100">
            <!-- Logo with fixed width -->
            <span class="stamp stamp-md bg-white" style="width: 80px; margin-right: 20px;">
                <img src="{{asset('asset/image/logo-pinas.png')}}">
            </span>
            <!-- Title and description -->
            <!-- <a href="http://10.22.28.236/pinas" style="text-decoration:none; flex-grow: 1;"> -->
            <a href="http://10.22.28.236/pinas/log-masuk.php?token={{ App\Http\Helper::gettokenpinas() }}" style="text-decoration:none; flex-grow: 1;">

                <h4 class="m-0">PINAS</h4>
                <small class="text-muted">SISTEM PINJAMAN ASET</small>
            </a>
        </div>
    </div>
</div>

<div class="col-lg-6">
    <div class="card p-5 d-flex flex-column align-items-center" style="height: 100px;">
        <!-- <div class="card-status bg-blue"></div> -->
        <div class="d-flex w-100">
            <!-- Logo with fixed width -->
            <span class="stamp stamp-md bg-white" style="width: 80px; margin-right: 20px;">
                <img src="{{asset('asset/image/logo-stk.png')}}" style="max-width: 100%; height: auto;">
            </span>
            <!-- Title and description -->
            <a href="{{ route('redirect.token', 'STK') }}" style="text-decoration:none; flex-grow: 1;">
                <h4 class="m-0">STK</h4>
                <small class="text-muted">SISTEM TEMPAHAN KENDERAAN</small>
            </a>
        </div>
    </div>
</div>

<div class="col-lg-6">
    <div class="card p-5 d-flex flex-column align-items-center" style="height: 100px;">
        <!-- <div class="card-status bg-blue"></div> -->
        <div class="d-flex w-100">
            <!-- Logo with fixed width -->
            <span class="stamp stamp-md bg-white" style="width: 80px; margin-right: 20px;">
                <img src="{{asset('asset/image/logo-etempah.png')}}">
            </span>
            <!-- Title and description -->
            <a href="http://10.22.28.183/eTempah" style="text-decoration:none; flex-grow: 1;">
                <h4 class="m-0">eTempah</h4>
                <small class="text-muted">SISTEM TEMPAHAN BILIK MESYUARAT</small>
            </a>
        </div>
    </div>
</div>

<!-- <div class="col-lg-6">
    <div class="card p-5 d-flex flex-column align-items-center" style="height: 100px;">

        <div class="d-flex w-100">
            
            <span class="stamp stamp-md bg-white" style="width: 80px; margin-right: 20px;">
                <img src="{{asset('asset/image/logo-ictbpsm.png')}}">
            </span>
            
            <a href="http://10.22.28.183/ictbpsm" style="text-decoration:none; flex-grow: 1;">
                <h4 class="m-0">ICTBPSM</h4>
                <small class="text-muted">SISTEM UNIT TEKNIKAL ICT</small>
            </a>
        </div>
    </div>
</div> -->

<br>

@if(Auth::user()->Role == 'Superadmin')
<h5>Bahagian Superadmin</h5>
<div class="col-lg-6">
    <div class="card p-5 d-flex flex-column align-items-center" style="height: 100px;">
        <!-- <div class="card-status bg-blue"></div> -->
        <div class="d-flex w-100">
            <!-- Logo with fixed width -->
            <span class="stamp stamp-md bg-white" style="width: 80px; margin-right: 20px;">
                <img src="{{asset('asset/image/logo-spp.png')}}">
            </span>
            <!-- Title and description -->
            <a href="{{ route('user.mgt.list') }}" style="text-decoration:none; flex-grow: 1;">
                <h4 class="m-0">SPP</h4>
                <small class="text-muted">SISTEM PENGURUSAN PENGGUNA</small>
            </a>
        </div>
    </div>
</div>
@endif

@endsection