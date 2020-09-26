@extends('layout.master')

@section('title')
Categories
@endsection

@section('content')


<h2>Categories</h2>
<!-- <form action="" method="GET">
    <button type="submit" class="btn mb-1 btn-primary">Add</button>
</form> -->
<a href="#_" class="btn mb-1 btn-primary event_modal" label="Category" event_type="C" event_id="0"
    style="float: right;">Add Category</a>

<div class="table-responsive">
    <table class="table table-xs mb-0" id="example">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $key => $cat)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{ucfirst($cat->name)}}</td>
                <td>
                    <a href="{{ route('subcategories',['category_id'=> $cat->id]) }}"
                        class="btn btn-success btn-sm">Subcategory</a>
                    <a href="javascript:;" class="btn btn-primary btn-sm edit_event_modal" label="Category"
                        event_type="C" event_id="{{ $cat->id }}" name="{{ $cat->name }}">Edit</a>
                    <a href="{{ route('event.delete',['event_id'=> $cat->id,'event_type'=>'C']) }}"
                        class="btn btn-sm btn-danger">Delete</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Open modal for @mdo</button>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@fat">Open modal for @fat</button>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@getbootstrap">Open modal for @getbootstrap</button> -->



@endsection

@push('scripts')
@endpush
