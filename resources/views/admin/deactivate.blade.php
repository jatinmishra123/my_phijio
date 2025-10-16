<div class="modal fade zoomIn" id="deletepopup" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-soft-info p-3">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="btn-close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="deletedata">
                    @csrf()
                    <input type="hidden" name="id" id="delete_id">
                    <input type="hidden" name="table" id="delete_table">
                    <input type="hidden" name="type" id="delete_type">

                    <div class="mt-2 text-center">
                        <span class="delete_icon"></span>
                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                            <h4>Are you Sure ?</h4>
                            <p class="text-muted mx-4 mb-0">Are you Sure You want to <span class="delete_text">Remove</span> this Record ?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn w-sm btn-danger " id="delete-record">Yes, <span class="delete_text">Remove</span> It!</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<script>
    function deletepopup(id, table, type) {
        $("#delete_id").val(id);
        $("#delete_table").val(table);
        $("#delete_type").val(type);
        $(".delete_text").html(type);

        if (type == 'delete') {
            $(".delete_icon").html('<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>');
        } else if (type == 'activate') {
            $(".delete_icon").html('<lord-icon src = "https://cdn.lordicon.com/hjeefwhm.json" trigger = "loop" colors = "primary:#109121" style = "width:100px;height:100px"></lord-icon>');
        } else if (type == 'deactivate') {
            $(".delete_icon").html('<lord-icon src = "https://cdn.lordicon.com/rslnizbt.json" trigger = "loop" colors = "primary:#c71f16" style = "width:100px;height:100px"></lord-icon>');
        }
    }



    $(document).on('submit', '#deletedata', function(ev) {
        ev.preventDefault(); // Prevent browers default submit.
        var formData = new FormData(this);
        $.ajax({
            url: "{{ url('admin/staff/delete')}}",
            type: 'post',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(result) {
                if (result.code == 200) {
                    swal(result.message, ' ', 'success');
                    reloadpage();
                } else {
                    swal(result.message, ' ', 'error');
                }
            },
        })
    })
</script>