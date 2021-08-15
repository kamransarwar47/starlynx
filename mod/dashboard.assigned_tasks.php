<?php
    $sql = "SELECT * FROM users_tasks WHERE assigned_by = '" . $userInfo["id"] . "' AND user_id <> '" . $userInfo["id"] . "' AND status='DUE' ORDER BY due_on ASC limit 0,5";
    $result = mysql_query($sql, $conn) or die(mysql_error());
    $numrows = mysql_num_rows($result);
?>
<div class="col-lg-4">
    <div class="card">
        <div class="card-body">
            <h4>Assigned Tasks</h4>
            <div class="row mb-2">
                <div class="col-6">Task</div>
                <div class="col-6 text-right">Due Date</div>
            </div>
            <div class="activity" id="assigned_tasks">
                <?php
                    if ($numrows > 0) {
                        while ($rs = mysql_fetch_array($result)) {
                            $id          = $rs["id"];
                            $title       = stripslashes($rs["title"]);
                            $details     = stripslashes(nl2br($rs["details"]));
                            $status      = $rs["status"];
                            $due_on      = date("F d, Y", strtotime($rs["due_on"]));
                            $assigned_to = getUserFullName($rs["user_id"]);

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
                                    <h6><?php echo "<a href='index.php?ss=$ss&mod=users.assigned_tasks#task_$id' title='$details'>$title</a>" ?></h6>
                                    <p class="mb-0">Assigned To: <?php echo $assigned_to; ?></p>
                                </div>
                                <span class="text-muted" style="<?php echo $style; ?>"><?php echo $due_on; ?></span>
                            </div>

                            <?php
                        }
                    } else {
                        ?>
                        <div class="media border-bottom pt-3 pb-3">
                            <div class="media-body">
                                <p class="mb-0">No new task, <?php echo "<a href='index.php?ss=$ss&mod=users.assigned_tasks'>Click here</a>"; ?> to see all tasks.</p>
                            </div>
                        </div>
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>
</div>