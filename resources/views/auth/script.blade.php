
<script src="{{ asset('assets/admin/js/jquery.js') }}" charset="utf-8"></script>
<script src="{{ asset('assets/admin/js/sweetalert2.all.min.js') }}"></script>






@if (session('error'))
    <script>
        Swal.fire({
            title: 'خطا!',
            text: "{{ session('error') }}",
            icon: 'error',
            confirmButtonText: 'فهمت'
        })
    </script>
@endif

@if (session('success'))
    <script>
        Swal.fire({
            title: 'تم',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'فهمت'
        })
    </script>
@endif


@if ($errors->any)

    @foreach ($errors->all() as $error)
        <script>
            Swal.fire({
                title: 'خطأ',
                text: "{{ $error }}",
                icon: 'error',
                confirmButtonText: 'فهمت'
            })
        </script>
    @break
@endforeach

@endif

