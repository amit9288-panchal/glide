<?php

include "./app/View/header.php" ?>
<div class="container-xl">
    <div class="row search-bar">
        <form action="<?php
        echo BASE_URL . 'calorific/?action=statistic'; ?>" method="POST" class="container-xl">
            <div class="col-lg-9 mx-auto">
                <label style="color: red;padding: 5px;">It will display top <b>10 average value</b> based on selected
                    date
                    range and in bottom it has <b>graph</b></label>
                <div class="input-group">
                    <input type="date" class="form-control" name="calorific_date_from"
                           placeholder="Calorific Date From">
                    <input type="date" class="form-control" name="calorific_date_to" placeholder="Calorific Date To">
                    <button class="btn btn-primary" type="submit">Filter</button>
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
                    <div class="col-sm-8"><h2>Calorific <b>Average Value Details</b></h2></div>
                    <div class="col-sm-4">
                        <div class="search-box">

                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Calorific Date</th>
                    <th>Average Calorific Value</th>
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
                        <td><?php
                            echo $calorific['avg_value']; ?></td>
                    </tr>
                    <?php
                    $i++;
                }
                ?>
                </tbody>
            </table>
            <!--
            <div class="clearfix">
                <div class="hint-text">Showing <b>5</b> out of <b>25</b> entries</div>
                <ul class="pagination">
                    <li class="page-item disabled"><a href="#"><i class="fa fa-angle-double-left"></i></a></li>
                    <li class="page-item"><a href="#" class="page-link">1</a></li>
                    <li class="page-item"><a href="#" class="page-link">2</a></li>
                    <li class="page-item active"><a href="#" class="page-link">3</a></li>
                    <li class="page-item"><a href="#" class="page-link">4</a></li>
                    <li class="page-item"><a href="#" class="page-link">5</a></li>
                    <li class="page-item"><a href="#" class="page-link"><i class="fa fa-angle-double-right"></i></a></li>
                </ul>
            </div>
            -->
        </div>
    </div>
</div>
<?php
if (count($dates) > 0 && count($averageValues) > 0) { ?>
    <div class="container-xl">
        <div class="table-responsive">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-sm-8"><h2>Calorific <b>Average Value Graph</b></h2></div>
                        <div class="col-sm-4">
                            <div class="search-box">

                            </div>
                        </div>
                    </div>
                </div>
                <canvas id="calorific_graph"></canvas>
            </div>
        </div>
    </div>
<?php
} ?>
<script type="text/javascript">
    let content = document.getElementById("calorific_graph").getContext('2d');
    let calorificGraph = new Chart(content, {
        type: 'bar',
        data: {
            labels:<?php echo json_encode($dates);?>,
            datasets: [{
                backgroundColor: [
                    "#5969ff",
                    "#ff407b",
                    "#25d5f2",
                    "#ffc750",
                    "#2ec551",
                    "#5969ff",
                    "#ff407b",
                    "#25d5f2",
                    "#ffc750",
                    "#2ec551"
                ],
                data:<?php echo json_encode($averageValues);?>,
            }]
        }
    });
</script>
<?php
include "./app/View/footer.php" ?>
