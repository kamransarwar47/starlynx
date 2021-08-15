<?
    define("MODULE_ID", "DASHBOARD");

    include("common/check_access.php");

    system_log(ACCESS, "Module was accessed.", $userInfo["id"]);
?>
<?= showError() ?>
<div class="container-fluid">

    <?php
        if(checkPermission($userInfo["id"], VIEW)) {
            ?>

            <div class="row">
                <? include("dashboard.top_stats.php"); ?>
            </div>

            <?php
        }
    ?>

    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4>Project Wise Statistics</h4>
                            <p>* Total Earnings Project Wise From Start till Date. (Includes Pending Auth And Handovers / Clearances)</p>
                            <?php
                                echo get_project_wise_statistics();
                            ?>
                            <div id="project-wise-statistics"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h4>Project Monthly Statistics</h4>
                            <p>* Total Monthly Earnings of this Year From January, <?php echo date('Y', time()); ?> till <?php echo date('F, Y', time()); ?>. (Includes Pending Auth And Handovers / Clearances)</p>
                        </div>
                    </div>
                    <?php
                        echo get_monthly_project_statistics();
                    ?>
                    <div class="chart-wrapper">
                        <canvas id="monthly-project-statistics"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <? include("dashboard.due_installments.php"); ?>
    </div>

    <div class="row">
        <? include("dashboard.projects.php"); ?>
        <? include("dashboard.tasks.php"); ?>
        <? include("dashboard.assigned_tasks.php"); ?>
    </div>

    <div class="row">
        <? include("dashboard.cheques_clearance.php"); ?>
    </div>

    <div class="row">
        <? include("dashboard.invoices.php"); ?>
    </div>
</div>