<script src="{{ asset('assets/admin/js/jquery.js') }}" charset="utf-8"></script>
<script src="{{ asset('assets/admin/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/popper.js') }}"></script>


@if (!request()->is('admin/home'))
    <script src="{{ asset('assets/admin/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tippy.js') }}"></script>
    <script src="{{ asset('assets/admin/js/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/select2.js') }}"></script>
    <script src="{{ asset('assets/admin/js/time.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
@endif




@if (!request()->is('admin/orders') && !request()->is('admin/orders/search')  && !request()->is('admin/home')  )
    <script src="{{ asset('assets/admin/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/charts.js') }}"></script>


    @if (auth()->user()->role == 'admin')
        <script src="{{ asset('assets/admin/js/quill.js') }}"></script>
        <script src="{{ asset('assets/admin/js/qimg.js') }}"></script>
        <script src="{{ asset('assets/admin/js/ssi-uploader.min.js') }}"></script>
        <script src="{{ asset('assets/admin/js/jquery.smartWizard.min.js') }}"></script>
        <script src="{{ asset('assets/admin/js/printThis.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js"
            integrity="sha512-IOebNkvA/HZjMM7MxL0NYeLYEalloZ8ckak+NDtOViP7oiYzG5vn6WVXyrJDiJPhl4yRdmNAG49iuLmhkUdVsQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="{{ asset('assets/admin/js/tinymce/tinymce.min.js') }}"></script>
        <script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
        <script src="{{ asset('assets/admin/js/bootstrap2-toggle.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/print-js@1.0.51/dist/print.min.js"></script>
    @endif

@endif




<script src="{{ asset('assets/admin/js/main.js?ver=1.1') }}"></script>
