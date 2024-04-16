@extends('admin/layout')


@section('path')
    <ul class="paths">
        <li> <a href="{{ url('admin/users') }}"> المستخدمين / </a> </li>
        <li class="active"> {{ $user->name }} / </li>
        <li class="active"> سجل التغيرات </li>
    </ul>
@endsection




@section('content')
    <div class=" mt-lg-4 mt-3 tableSpace">

        <div class="contnet-title">سجل التعديلات</div>

        <table class="not">
            <thead>

                <tr>
                    <th>التعديلات</th>

                    <th>المغير</th>

                    <th>الوقت</th>

                </tr>

            </thead>

            <tbody>

                @forelse ($user->logs as $log)
                    <tr>

                        <td>
                            @php
                                $updatedColumns = json_decode($log->updated_columns, true);
                            @endphp

                            @foreach ($updatedColumns as $column => $values)
                                <span>{{ translateUsers($column) }}</span> : <span
                                    class="old_update">{{ $values['old'] }}</span> ---->
                                <span>{{ $values['new'] }}</span><br>
                            @endforeach
                        </td>

                        <td>{{ $log->editer->name }}</td>

                        <td>{{ $log->created_at }} <br> <small>{{ timeFormat($log->created_at)  }}</small> </td>

                    </tr>
                @empty
                    <tr>
                        <td>لا يوجد اي تعديل علي هذا المستخدم</td>
                    </tr>
                @endforelse

            </tbody>

        </table>





    </div>
@endsection


@section('js')
    <script>
        $('#aside .users').addClass('active');
        tippy('[data-tippy-content]');
    </script>
@endsection
