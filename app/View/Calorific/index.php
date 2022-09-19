<?php

include "./app/View/header.php" ?>
<div class="container-xl">
    <div class="row search-bar">
        <form action="<?php
        echo BASE_URL . 'calorific/?action=search'; ?>" method="POST" class="container-xl">
            <div class="col-lg-9 mx-auto">
                <div class="input-group">
                    <input type="date" class="form-control" name="calorific_date" placeholder="Calorific Date">
                    <input type="text" class="form-control" name="calorific_value" placeholder="Calorific Value">
                </div>
                <div class="input-group">
                    <select class="form-control form-select" name="area" aria-label="Default select example">
                        <option value="">Please select area</option>
                        <?php
                        if (count($areas) > 0) {
                            foreach ($areas as $area) {
                                ?>
                                <option value="<?php
                                echo $area; ?>"><?php
                                    echo $area; ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                    <input type="hidden" class="form-control" name="search_type" value="specific_search">
                    <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit">Search</button>
                </span>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="container-xl">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8"><h2>Calorific <b>Details</b></h2></div>
                    <div class="col-sm-4">
                        <div class="search-box">
                            <i class="material-icons">&#xE8B6;</i>
                            <form action="<?php
                            echo BASE_URL . 'calorific/?action=search'; ?>" method="POST">
                                <input type="hidden" class="form-control" name="search_type" value="general_search">
                                <input type="text" name="search" class="form-control" placeholder="Search&hellip;">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Calorific Date</th>
                    <th>Calorific Value</th>
                    <th>Area</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 1;
                foreach ($calorificInfo as $calorific) {
                    ?>
                    <tr>
                        <td><?php
                            echo $i; ?></td>
                        <td><?php
                            echo date('d-m-Y', strtotime($calorific['applicable_date'])); ?></td>
                        <td id="<?php
                        echo $calorific['id']; ?>_cal_value">
                            <?php
                            echo $calorific['value']; ?></td>
                        <td><?php
                            echo $calorific['area_name']; ?></td>
                        <td>
                            <a href="#"><span class="fa fa-edit"
                                              data-original-title="Edit" data-toggle="modal"
                                              data-target="#load-data" onclick="getCalorificInfo(<?php
                                echo $calorific['id']; ?>,<?php
                                echo $calorific['value']; ?>)"></span></a>
                        </td>
                    </tr>
                    <?php
                    $i++;
                }
                ?>
                </tbody>
            </table>
            <!-- Modal -->
            <div class="modal fade" id="load-data" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Update Information</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <table>
                                <tr>
                                    <td>
                                        Calorific Value :
                                    </td>
                                    <td>
                                        <input type="hidden" class="form-control" id="cal_id">
                                        <input type="text" class="form-control" id="cal_val" name="cal_val"
                                               placeholder="Calorific Value">
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close-btn">Close
                            </button>
                            <button type="button" class="btn btn-primary" onclick="updateCalorificValue()">Save
                                changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function getCalorificInfo(id, cal_val) {
        $("#cal_id").val(id);
        $("#cal_val").val(cal_val);
    }

    function updateCalorificValue() {
        let id = $("#cal_id").val();
        let cal_value = $("#cal_val").val();
        $.ajax({
            url: "<?php  echo BASE_URL . 'calorific/?action=update';  ?>",
            type: "post",
            data: {'id': id, 'cal_value': cal_value}
        }).done(function (response) {
            console.log(response);
            console.log("updated ")
            $("#close-btn").click();
            $("#" + id + "_cal_value").html(cal_value);
        });
    }
</script>
<?php
include "./app/View/footer.php" ?>
