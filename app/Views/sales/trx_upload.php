<?php 

$request = \Config\Services::request();
$mylibzdb = model('App\Models\MyLibzDBModel');
$mylibzsys = model('App\Models\MyLibzSysModel');

$image_url= '';
?>
<style>
table.memetable, th.memetable, td.memetable {
  border: 1px solid #F6F5F4;
  border-collapse: collapse;
}
thead.memetable, th.memetable, td.memetable {
  padding: 6px;
}
</style>

<main id="main">
    
    <div class="row mb-3 me-form-font">
        <span id="__me_numerate_wshe__" ></span>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                <form id="upload-form" enctype="multipart/form-data">
                    <input type="file" name="image">
                    <button type="submit">Upload</button>
                </form>
                <div id="image-preview"></div>
                <img src="<?= $image_url ?>" alt="Uploaded image">
                </div>
            </div>
        </div>
    </div>
</main>






	
<script type="text/javascript"> 

$(function() {
    $('#upload-form').submit(function(event) {
        event.preventDefault();
        $.ajax({
            url: 'upload',
            type: 'post',
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function(response) {
                $('#image-preview').html(response);
            }
        });
    });
});
	
	
</script>
