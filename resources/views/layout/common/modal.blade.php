<!-- Event Add like Category,SubCategory and Verticals -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New message</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="category_form">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Name:</label>
                        <input type="text" class="form-control" name="name" id="recipient-name" required>
                    </div>
                    <!-- <div class="form-group">
                        <label for="message-text" class="col-form-label">Message:</label>
                        <textarea class="form-control" id="message-text"></textarea>
                    </div> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Event like Category,SubCategory and Verticals -->
<div class="modal fade" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">New message</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="edit_event_form">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Name:</label>
                        <input type="text" class="form-control" name="name" id="event_name" required>
                    </div>
                    <!-- <div class="form-group">
                        <label for="message-text" class="col-form-label">Message:</label>
                        <textarea class="form-control" id="message-text"></textarea>
                    </div> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


@push('scripts')

<script type="text/javascript">
    $(document).ready(function(){

        //Add Event 
        $(document).on('click','.event_modal', function(){
            var label       = $(this).attr('label');
            var event_id    = $(this).attr('event_id');
            var event_type  = $(this).attr('event_type');
            $('#exampleModal').modal('show');
            $('#exampleModalLabel').text(label);
            $('#category_form').attr('action', "{{ url('admin/event') }}/"+event_id+'/'+event_type);
            //route('event.add',['event_id' => "+event_id+",'event_type'=> "+event_type+"])
        });

        //Edit Event 
        $(document).on('click','.edit_event_modal', function(){
            var label       = $(this).attr('label');
            var event_id    = $(this).attr('event_id');
            var event_type  = $(this).attr('event_type');
            var name        = $(this).attr('name');
            $('#editEventModal').modal('show');
            $('#eventModalLabel').text(label);
            $('#event_name').val(name);
            $('#edit_event_form').attr('action', "{{ url('admin/event/edit') }}/"+event_id+'/'+event_type);
            //route('event.add',['event_id' => "+event_id+",'event_type'=> "+event_type+"])
        });
    });   
</script>
@endpush
