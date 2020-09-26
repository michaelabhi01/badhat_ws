@extends('layout.master')

@section('title')
Products
@endsection

@section('content')


<!-- <form action="" method="GET">
    <button type="submit" class="btn mb-1 btn-primary">Add User</button>
</form> -->
<h2>Products</h2>
<div class="table-responsive">
    <table class="table table-xs mb-0" id="example">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Price</th>
                <th>Image</th>
                <th>Description</th>
                <th>MOQ</th>
                <th>Vendor</th>
                <th>Category</th>
                <th>Sub category</th>
                <th>Vertical</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $key => $product)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{ucfirst($product->name)}}</td>
                <td>{{$product->price}}</td>
                <td>
                    <img src="{{ $product->image }}" width="70px" height="70px">
                </td>
                <td>{{ $product->description }}</td>
                <td>{{ $product->moq }}</td>
                <td>{{ isset($product->vendor->name) ? $product->vendor->name : '' }}</td>
                <td>{{ isset($product->category->name) ? $product->category->name : '' }}</td>
                <td>{{ isset($product->subcategory->name) ? $product->subcategory->name : '' }}</td>
                <td>{{ isset($product->vertical->name) ? $product->vertical->name : '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


@endsection

@push('scripts')



<script type="text/javascript">
   
</script>
@endpush
