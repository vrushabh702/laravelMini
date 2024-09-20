<!DOCTYPE html>

<html lang="en">
<head>
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Laravel 11 Ajax CRUD Image Upload Tutorial - Tutsmake.com</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<link  href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

</head>
<body>
<div class="container" style="margin-top: 1%;">
  <h1>Laravel Ajax CRUD Image Upload Tutorial - Tutsmake.com</h1>
  <a href="javascript:void(0)" class="btn btn-info ml-3" id="create-new-task">Add New</a>
  <br><br>

  <table class="table table-bordered table-striped" id="laravel_datatable">
    <thead>
      <tr>
        <th>ID</th>
        <th>S. No</th>
        <th>Image</th>
        <th>Title</th>
        <th>Task Code</th>
        <th>Description</th>
        <th>Created at</th>
        <th>Action</th>
      </tr>
    </thead>
  </table>
</div>
<div class="modal fade" id="ajax-task-modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="taskCrudModal"></h4>
      </div>
      <div class="modal-body">
        <form id="taskForm" name="taskForm" class="form-horizontal" enctype="multipart/form-data">
          <input type="hidden" name="task_id" id="task_id">
          <div class="form-group">
            <label for="name" class="col-sm-2 control-label">Title</label>
            <div class="col-sm-12">
              <input type="text" class="form-control" id="title" name="title" placeholder="Enter Tilte" value="" maxlength="50" required="">
            </div>
          </div>
          <div class="form-group">
            <label for="name" class="col-sm-2 control-label">Task Code</label>
            <div class="col-sm-12">
              <input type="text" class="form-control" id="task_code" name="task_code" placeholder="Enter Tilte" value="" maxlength="50" required="">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label">Description</label>
            <div class="col-sm-12">
              <input type="text" class="form-control" id="description" name="description" placeholder="Enter Description" value="" required="">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label">Image</label>
            <div class="col-sm-12">
              <input id="image" type="file" name="image" accept="image/*" onchange="readURL(this);">
              <input type="hidden" name="hidden_image" id="hidden_image">
            </div>
          </div>
          <img id="modal-preview" src="https://via.placeholder.com/150" alt="Preview" class="form-group hidden" width="100" height="100">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary" id="btn-save" value="create">Save changes
            </button>
          </div>
        </form>
      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>

<script>

 var SITEURL = '{{URL::to('')}}'+'/';
 console.log(SITEURL);
 $(document).ready( function () {
   $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  $('#laravel_datatable').DataTable({
         processing: true,
         serverSide: true,
         ajax: {
          url: SITEURL + "tasks",
          type: 'GET',
         },
         columns: [
                  {data: 'id', name: 'id', 'visible': false},
                  {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false,searchable: false},
                  {data: 'image', name: 'image', orderable: false},
                  { data: 'title', name: 'title' },
                  { data: 'task_code', name: 'task_code' },
                  { data: 'description', name: 'description' },
                  { data: 'created_at', name: 'created_at' },
                  {data: 'action', name: 'action', orderable: false},
               ],
        order: [[0, 'desc']]
      });
    /*  When user click add user button */
    $('#create-new-task').click(function () {
        $('#btn-save').val("create-task");
        $('#task_id').val('');
        $('#taskForm').trigger("reset");
        $('#taskCrudModal').html("Add New task");
        $('#ajax-task-modal').modal('show');
        $('#modal-preview').attr('src', 'https://via.placeholder.com/150');
    });

       /* When click edit user */
       $('body').on('click', '.edit-task', function () {
      var task_id = $(this).data('id');
      $.get('taskEdit/' + task_id, function (data) {
         $('#title-error').hide();
         $('#task_code-error').hide();
         $('#description-error').hide();
         $('#taskCrudModal').html("Edit Task");
          $('#btn-save').val("edit-task");
          $('#ajax-task-modal').modal('show');
          $('#task_id').val(data.id);
          $('#title').val(data.title);
          $('#task_code').val(data.task_code);
          $('#description').val(data.description);
          $('#modal-preview').attr('alt', 'No image available');
          if(data.image){
            $('#modal-preview').attr('src', SITEURL +'public/task/'+data.image);
            $('#hidden_image').attr('src', SITEURL +'public/task/'+data.image);
          }
$('body').on('click', '#delete-task', function () {

        var task_id = $(this).data("id");

        if(confirm("Are You sure want to delete !")){
          $.ajax({
              type: "get",
              url: SITEURL + "taskDelete/"+task_id,
              success: function (data) {
              var oTable = $('#laravel_datatable').dataTable();
              oTable.fnDraw(false);
              },
              error: function (data) {
                  console.log('Error:', data);
              }
          });
        }
    });
   });
   $('body').on('submit', '#taskForm', function (e) {
      e.preventDefault();
      var actionType = $('#btn-save').val();
      $('#btn-save').html('Sending..');
      var formData = new FormData(this);
      $.ajax({
          type:'POST',
          url: SITEURL + "taskStore",
          data: formData,
          cache:false,
          contentType: false,
          processData: false,
          success: (data) => {

              $('#taskForm').trigger("reset");
              $('#ajax-task-modal').modal('hide');
              $('#btn-save').html('Save Changes');
              var oTable = $('#laravel_datatable').dataTable();
              oTable.fnDraw(false);
          },
          error: function(data){
              console.log('Error:', data);
              $('#btn-save').html('Save Changes');
          }
      });
  });

  function readURL(input, id) {
  id = id || '#modal-preview';
  if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
          $(id).attr('src', e.target.result);
      };
      reader.readAsDataURL(input.files[0]);
      $('#modal-preview').removeClass('hidden');
      $('#start').hide();
  }
}

    })
});


</script>