<?php
    $sql = "SELECT p.id, p.title, COUNT( pt.status ) AS
            cnt , pt.status
            FROM projects p, plots pt
            WHERE p.id = pt.project_id
            GROUP BY p.id, pt.status
            ORDER BY p.title";
    $result = mysql_query($sql, $conn) or die(mysql_error());
    $numrows = mysql_num_rows($result);
?>
<div class="col-lg-4">
    <div class="card card-widget">
        <div class="card-body">
            <h4>Projects</h4>
            <div class="row mb-2">
                <div class="col-12">* Plots Statistics</div>
            </div>
            <div class="monthly_report" id="monthly_report">
                <?php
                    if ($numrows > 0) {
                        $projects = array();

                        while ($rs = mysql_fetch_array($result)) {
                            $projects[$rs["id"] . "~" . $rs["title"]][$rs["status"]] = $rs["cnt"];
                        }

                        foreach ($projects as $project => $status) {
                            $prj = explode("~", $project);

                            asort($status);

                            $data  = '';
                            $total = array_sum($status);

                            foreach ($status as $key => $val) {

                                switch ($key) {
                                    case "VACANT":
                                        $style = "bg-success";
                                        break;

                                    case "SOLD":
                                        $style = "bg-danger";
                                        break;

                                    case "RESERVED":
                                        $style = "bg-warning";
                                        break;

                                    default:
                                        $style = "bg-primary";
                                        break;
                                }

                                $percentage = number_format((($val / $total) * 100), 2);

                                $data .= '<h6 class="m-t-10 text-muted">' . $key . ' <span class="pull-right mr-2">' . $val . '</span></h6>
                                            <div class="progress mb-3" style="height: 7px">
                                                <div class="progress-bar ' . $style . '" style="width: ' . $percentage . '%;" role="progressbar"><span class="sr-only">' . $percentage . '% Complete</span>
                                                </div>
                                          </div>';
                            }

                            echo "<div class='mt-4'><h5><a href='index.php?ss=$ss&mod=plots.manage&project_id=" . $prj[0] . "'>" . $prj[1] . "</a> <span class='pull-right mr-2'>" . $total . "</span></h5>";

                            echo $data;

                            echo "</div>";
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</div>
