@extends('admin.layout')




@section('content')
    <div class="tableSpace ">
        <div class="contnet-title">التجار</div>
        <table class="not">
            <thead>

                <tr>
                    <th> التاجر </th>
                    <th> الإجراءات </th>

                </tr>

            </thead>
            <tbody>


                @forelse ($traders as $trader)
                    <tr>




                        <td> {{ $trader->name }} <br> {{ $trader->mobile }}
                        </td>

                        <td data-text="الإجراءات">
                            <div>


                                <div onclick='window.location.href = "/admin/invoices/{{ $trader->id }}"'
                                    id="myButton" data-tippy-content="اضافة" class="square-btn ltr has-tip"><i
                                        class="fas fa-plus mr-2  icon" aria-hidden="true"></i></div>




                            </div>

                        </td>


                    </tr>

                @empty
                    <tr>

                        <td colspan="2">لا يوجد تجار متاحة</td>
                    </tr>
                @endforelse

            </tbody>


        </table>

    </div>
@endsection


@section('js')
    <script>
        $('aside .invoices').addClass('active');
        tippy('[data-tippy-content]');

    </script>
@endsection
