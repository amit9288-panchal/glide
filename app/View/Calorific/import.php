<?php include "./app/View/header.php" ?>
<div class="container-xl">
    <div class="row search-bar">
        <form action="<?php echo BASE_URL . 'calorific/?action=upload'; ?>" method="POST" class="container-xl" enctype="multipart/form-data">
            <div class="col-lg-6 mx-auto">
                <div class="input-group">
                    <input type="file" class="form-control upload-file" name="calorific_file" />
                    <input type="submit" class="btn btn-primary" value="Upload">
                </div>
                <div class="input-group">

                </div>
            </div>
        </form>
    </div>
</div>
<?php include "./app/View/footer.php" ?>
