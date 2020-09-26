@extends('layout.master')

@section('title')
Orders
@endsection

@section('content')


<!-- <form action="" method="GET">
    <button type="submit" class="btn mb-1 btn-primary">Add User</button>
</form> -->
<h2>Orders</h2>
<div class="table-responsive">
    <table class="table table-xs mb-0" id="example">
        <thead>
            <tr>
                <th>SNo.</th>
                <th>Order No.</th>
                <th>User Name</th>
                <th>Status</th>
                <th>Order Item</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $key => $order)
            <tr>
                <td>{{$key+1}}</td>
                <td>ORDER #{{$order->id}}</td>
                <td>{{ isset($order->user->name) ? ucfirst($order->user->name) : ''}}</td>
                <td>{{$order->status}}</td>
                <td>
                    <!-- <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                        </tr>
                    </thead> -->
                    <!-- <tbody>
                        @foreach($order->items as $key => $item)
                        <tr>
                            <td>{{ isset($item->product->name) ? $item->product->name : '' }}  </td>
                            <td>{{ isset($item->quantity) ? $item->quantity : '' }}</td>
                            <td>{{ isset($item->price) ? $item->price : '' }}</td>
                        </tr>
                        @endforeach
                    </tbody> -->
                    @foreach($order->items as $key => $item)
                        <strong>Product:</strong> {{ isset($item->product->name) ? $item->product->name : '' }} |   
                        <strong>Quantity:</strong> {{ isset($item->quantity) ? $item->quantity : '' }} | 
                        <strong>Price:</strong> {{ isset($item->price) ? $item->price : '' }} 
                        <hr>
                    @endforeach
                </td>
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
