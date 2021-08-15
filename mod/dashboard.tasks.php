<?php
    $sql = "select * from users_tasks where user_id='" . $userInfo["id"] . "' and status='DUE' order by due_on asc limit 0,5";
    $result = mysql_query($sql, $conn) or die(mysql_error());
    $numrows = mysql_num_rows($result);
?>
<div class="col-lg-4">
    <div class="card">
        <div class="card-body">
            <h4>My Tasks</h4>
            <div class="row mb-2">
                <div class="col-6">Task</div>
                <div class="col-6 text-right">Due Date</div>
            </div>
            <div class="activity" id="my_tasks">
                <?php
                    if ($numrows > 0) {
                        while ($rs = mysql_fetch_array($result)) {
                            $id          = $rs["id"];
                            $title       = stripslashes($rs["title"]);
                            $details     = stripslashes(nl2br($rs["details"]));
                            $status      = $rs["status"];
                            $due_on      = date("F d, Y", strtotime($rs["due_on"]));
                            $assigned_by = getUserFullName($rs["assigned_by"]);

                            $style = "";

                            if ($status == "DUE") {
                                $t_due = strtotime($rs["due_on"]);
                                $t     = time();

                                if ($t_due <= $t) {
                                    $style = "background-color: #ffeeee; padding: 5px;";
                                }
                            }
                            ?>

                            <div class="media border-bottom pt-3 pb-3">
                                <div class="media-body">
                                    <h6><?php echo "<a href='index.php?ss=$ss&mod=users.tasks#task_$id' title='$details'>$title</a>" ?></h6>
                                    <p class="mb-0">Assigned By: <?php echo $assigned_by; ?></p>
                                </div>
                                <span class="text-muted" style="<?php echo $style; ?>"><?php echo $due_on; ?></span>
                            </div>

                            <?php
                        }
                    } else {
                        ?>
                        <div class="media border-bottom pt-3 pb-3">
                            <div class="media-body">
                                <p class="mb-0">No new task, <?php echo "<a href='index.php?ss=$ss&mod=users.tasks'>Click here</a>"; ?> to see all tasks.</p>
                            </div>
                        </div>
                <?php
                    }
                ?>
            </div>
        </div>
    </div>
</div>
