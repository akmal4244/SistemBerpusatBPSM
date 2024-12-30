@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
@if (session('error'))
<!-- <div class="alert alert-danger">
                {{ session('error') }}
            </div> -->
<script>
    Swal.fire({
        icon: 'error',
        title: 'Maaf, Tidak Berjaya',
        text: '{{ session('error') }}', // Correctly access the session 'error' message
    });
</script>
@endif

@if (session('success'))
<!-- <div class="alert alert-success">
                {{ session('success') }}
            </div> -->
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berjaya Disimpan!',
        text: '{{ session('success') }}', // Correctly access the session 'success' message
    });
</script>
@endif