@extends('layout.master')

@section('title')
Vertical
@endsection

@section('content')


<h2>Vertical</h2>
<h6>Subcategory: {{ $sub_category_name }}</h6>
<!-- <form action="" method="GET">
    <button type="submit" class="btn mb-1 btn-primary">Add</button>
</form> -->
<a href="#_" class="btn mb-1 btn-primary event_modal" label="Vertical" event_type="V" event_id="{{ $subcategory_id }}"
    style="float: right;">Add Vertical</a>

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
            @foreach($verticals as $key => $cat)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{ucfirst($cat->name)}}</td>
                <td>
                    <a href="javascript:;" class="btn btn-primary btn-sm edit_event_modal" label="Vertical"
                        event_type="V" event_id="{{ $cat->id }}" name="{{ $cat->name }}">Edit</a>
                    <a href="{{ route('event.delete',['event_id'=> $cat->id,'event_type'=>'V']) }}"
                        class="btn btn-sm btn-danger">Delete</a>
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
