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

@if (session('error_tostar'))
    <script>
        toastr["error"]("{{ session('error_tostar') }}");
    </script>
@endif

@if (session('success_tostar'))
    <script>
        toastr["success"]("{{ session('success_tostar') }}");
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
