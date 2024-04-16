@extends('admin.layout')


@include('admin.reports.style')

@section('content')
    <x-admin.report action="products_stock_in_orders_post" title="يتم عرض توزيع استوك المنتج في كل الطلبات علي هذا المنتج">
        <select name="product_id" class="js-example-basic-single main" id="product">

            <option value> كل المنتجات</option>

            @forelse ($products as $product)
                <option name="{{ $product->price }}" value="{{ $product->id }}">
                    {{ $product->name }}</option>
            @empty
                <option disabled selected>لا يوجد منتجات </option>
            @endforelse

        </select>
    </x-admin.report>
@endsection


@section('js')
    <script>
        $('aside .reports').addClass('active');

        $('.js-example-basic-single').select2();
    </script>
@endsection
