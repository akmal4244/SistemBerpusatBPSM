<div class="container">
    <div class="row align-items-center flex-row-reverse">
        <!-- <div class="col-auto ml-lg-auto">
              <div class="row align-items-center">
                <div class="col-auto">
                  <ul class="list-inline list-inline-dots mb-0">
                    <li class="list-inline-item"><a href="./docs/index.html">Documentation</a></li>
                    <li class="list-inline-item"><a href="./faq.html">FAQ</a></li>
                  </ul>
                </div>
              </div>
            </div> -->
        <div class="col-12 col-lg-12 mt-3 mt-lg-0 text-center">
            Copyright © 2024 <a href=".">BPSM - Kementerian Pendidikan Malaysia</a>
        </div>
    </div>
</div>

<!-- Bootstrap JS with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="{{asset('asset/template/js/require.min.js')}}"></script>
<script>
    requirejs.config({
        baseUrl: '.'
    });
</script>
<!-- Dashboard Core -->
<script src="{{asset('asset/template/js/dashboard.js')}}"></script>
<!-- c3.js Charts Plugin -->
<script src="{{asset('asset/template/plugins/charts-c3/plugin.js')}}"></script>
<!-- Google Maps Plugin -->
<script src="{{asset('asset/template/plugins/maps-google/plugin.js')}}"></script>
<!-- Input Mask Plugin -->
<script src="{{asset('asset/template/plugins/input-mask/plugin.js')}}"></script>