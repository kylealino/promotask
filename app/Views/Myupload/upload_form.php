

<main id="main">
<div class="container">
  <div class="row">
    <div class="col-md-6 offset-md-3">
      <div class="card">
        <div class="card-header">
          Upload File
        </div>
        <div class="card-body">
       <form id="upload-form" action="<?php echo base_url('upload/do_upload'); ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="userfile" class="form-label">Choose File</label>
              <input type="file" name="userfile" class="form-control" id="userfile">
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Description</label>
              <input type="text" name="description" class="form-control" id="description">
            </div>
              <div class="mb-3">
              <label for="age" class="form-label">Age</label>
              <input type="text" name="age" class="form-control" id="age">
            </div>
            <button type="button" id="upload-file-btn" class="btn btn-primary">Upload File</button>
        </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h1>Uploaded Files</h1>
      <table class="table table-striped" id="file-table">
        <thead>
          <tr>
            <th>File Name</th>
            <th>Description</th>
            <th>Age</th>
            <th>Uploaded By</th>
            <th>Uploaded At</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($files as $file) { ?>
            <tr>
              <td><?php echo $file->filename; ?></td>
              <td><?php echo $file->description; ?></td>
              <td><?php echo $file->age; ?></td>
              <td><?php echo $file->uploaded_by; ?></td>
              <td><?php echo date('M j, Y h:i A', strtotime($file->created_at)); ?></td>
              <td>
                <button type="button" class="btn btn-info edit-file-btn" data-id="<?php echo $file->id; ?>">Edit</button>
                <button type="button" class="btn btn-primary view-file-btn" data-bs-toggle="modal" data-bs-target="#view-file-modal" data-id="<?= $file->id; ?>">View</button>
                <button type="button" class="btn btn-danger delete-file-btn" data-id="<?= $file->id; ?>">Delete</button>

         
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- View file modal -->
<div class="modal fade" id="view-file-modal" tabindex="-1" aria-labelledby="view-file-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="view-file-modal-label">File Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tr>
                        <td>ID</td>
                        <td id="file-id"></td>
                    </tr>
                    <tr>
                        <td>File Name</td>
                        <td id="file-name"></td>
                    </tr>
                    <tr>
                        <td>Description</td>
                        <td id="file-description"></td>
                    </tr>
                    <tr>
                        <td>Image</td>
                        <td> <img class="img-fluid" src="" id="img_file_src" alt="view_file"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editFileModal" tabindex="-1" role="dialog" aria-labelledby="editFileModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editFileModalLabel">Edit File</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editFileForm">
          <div class="form-group">
            <label for="edit-file-name">File Name</label>
            <input type="text" class="form-control" id="edit-file-name" name="edit-file-name">
          </div>
          <div class="form-group">
            <label for="edit-file-desc">Description</label>
            <textarea class="form-control" id="edit-file-desc" name="edit-file-desc"></textarea>
          </div>
          <input type="hidden" id="edit-file-id" name="edit-file-id">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="update-file-btn">Save changes</button>
      </div>
    </div>
  </div>
</div>


</main>
<script>
    $(document).ready(function() {
    $('#upload-file-btn').click(function(event) {
        event.preventDefault();
        var form = $('#upload-form')[0];
        var url = "<?php echo site_url('/upload/do_upload'); ?>";
        var formData = new FormData();

        formData.append('userfile', $('#userfile')[0].files[0]);
        formData.append('description', $('#description').val());
        formData.append('upload_by', 'ako ako');
        formData.append('age',$('#age').val());
        
        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert('File uploaded successfully!');
                     // Update the table with the latest data
                $('#file-table tbody').html('');
                $.each(response.files, function(i, file) {
                    $('#file-table tbody').append('<tr><td>' + file.filename + '</td><td>' + file.description + '</td><td>' + file.age + '</td><td>' + file.uploaded_by + '</td><td>' + file.created_at + '</td><td><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#view-file-modal" data-id="' + file.id + '">View</button></td></tr>');
                });
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                alert('Error uploading file.');
            }
        });
    });
});


$(document).on('click', '.delete-file-btn', function() {
    var id = $(this).data('id');
    if (confirm('Are you sure you want to delete this file?')) {
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("Meupload/delete_file"); ?>',
            data: { id: id },
            success: function(response) {
                if (response.success) {
                    // Reload the table to remove the deleted row
                    location.reload();
                }
            }
        });
    }
});


$(document).on('click', '.view-file-btn', function() {
    var id = $(this).data('id');
    $.ajax({
        type: 'POST',
        url: '<?php echo site_url("Meupload/view_file"); ?>',
        data: { id: id },
        success: function(response) {
            $('#file-id').text(response.id);
            $('#file-name').text(response.filename);
            $('#file-description').text(response.description);
            $('#img_file_src').attr('src','<?php echo base_url("uploads/meuploadhehe/");?>'+'/'+response.filename);
            $('#view-file-modal').modal('show');
        }
    });
});



$(document).on('click', '.edit-file-btn', function() {
  var fileId = $(this).data('id');
  var fileName = $(this).closest('tr').find('td:eq(1)').text();
  var fileDesc = $(this).closest('tr').find('td:eq(2)').text();

  $('#edit-file-id').val(fileId);
  $('#edit-file-name').val(fileName);
  $('#edit-file-desc').val(fileDesc);

  $('#editFileModal').modal('show');
});



</script>