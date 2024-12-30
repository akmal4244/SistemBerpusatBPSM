 <!-- Sidebar -->
 <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

     <!-- Sidebar - Brand -->
     <!-- <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
    <div class="sidebar-brand-icon rotate-n-15">
    </div>
    <div class="sidebar-brand-text mx-3">
    
    </div>
</a> -->


     <!-- Divider -->
     <!-- <hr class="sidebar-divider my-0"> -->

     <!-- Nav Item - Dashboard -->
     <!-- <li class="nav-item active">
         <a class="nav-link" href="index.html">
             <span></span></a>
     </li> -->
     <!-- Divider -->

     <div class="user">
         <div class="photo">
             <img class="img-profile rounded-circle" src="asset/image/undraw_profile.svg">
         </div>
         <div class="info">
             <a class="">
                 <span>
                     <span class="user-level">{{Auth::user()->name}}</span>
                 </span>
             </a>
             <div class="clearfix"></div>
         </div>
     </div>
     <hr class="sidebar-divider">
     <!-- Heading -->
     <div class="sidebar-heading">
         Menu Pilihan
     </div>
     <!-- Nav Item - Charts -->
     <li class="nav-item active">
         <a class="nav-link" href="{{ route('dashboard') }}">
             <i class="fas fa-fw fa-home"></i>
             <span>Halaman Utama</span></a>
     </li>




     <!-- Nav Item - Pages Collapse Menu -->
     <li class="nav-item">
         <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
             aria-expanded="true" aria-controls="collapsePages">
             <i class="fas fa-fw fa-user"></i>
             <span>Profil</span>
         </a>
         <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
             <div class="bg-white py-2 collapse-inner rounded">
                 <a class="collapse-item" href="#">Profil</a>
                 <a class="collapse-item" href="#">Kemaskini Profil</a>
                 <a class="collapse-item" href="#">Tukar Kata Laluan</a>
                 <a class="collapse-item" href="#">Terlupa Kata Laluan</a>
             </div>
         </div>
     </li>

     <!-- Nav Item - Tables -->
     <li class="nav-item">
         <a class="nav-link" href="#">
             <i class="fas fa-fw fa-list"></i>
             <span>Log Aktiviti</span></a>
     </li>



     <!-- Divider -->
     <hr class="sidebar-divider d-none d-md-block">

     <!-- Sidebar Toggler (Sidebar) -->
     <div class="text-center d-none d-md-inline">
         <button class="rounded-circle border-0" id="sidebarToggle"></button>
     </div>



 </ul>
 <!-- End of Sidebar -->