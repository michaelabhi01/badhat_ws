@extends('layout.master')

@section('title')
Users
@endsection

@section('content')


<!-- <form action="" method="GET">
    <button type="submit" class="btn mb-1 btn-primary">Add User</button>
</form> -->
<h2>Users</h2>

<div class="table-responsive">
    <table class="table table-xs mb-0" id="example">
        <thead>
            <tr>
                <th>Id</th>
                <th>Image</th>
                <th>Business Name</th>
                <th>Name</th>
                <th>Email</th>
                <th>GSTIN</th>
                <th>Mobile</th>
                <th>Business Type</th>
                <th>Pin code</th>
                <th>District</th>
                <th>City</th>
                <th>State</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $key => $user)
            <tr>
                <td>{{$key+1}}</td>
                <td>
                    <img src="{{ $user->image }}" width="70px" height="70px" alt="Image">
                </td>
                <td>{{ $user->business_name }}</td>
                <td>{{ucfirst($user->name)}}</td>
                <td>{{$user->email}}</td>
                <td>{{ $user->gstin }}</td>
                <td>{{ $user->mobile }}</td>
                <td>{{ $user->business_type }}</td>
                <td>{{ $user->pin_code }}</td>
                <td>{{ $user->district }}</td>
                <td>{{ $user->city }}</td>
                <td>{{ $user->state }}</td>
                <td>{{ $user->address }}</td>
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
