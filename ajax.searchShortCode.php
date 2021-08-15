<?
    include("common/common.start.php");

    $search = explode('-', strtoupper($search));
    $response = array();

    // Project
    $sql = "select id as project_id from projects where short_code='".mysql_real_escape_string($search[0])."'";
    $result = mysql_query($sql, $conn) or die(mysql_error());

    if(mysql_num_rows($result) > 0) {
        if ($row = mysql_fetch_assoc($result)) {
            $project_id = $row['project_id'];
            $plot_type = ($search[1] == 'R') ? 'Residential' : 'Shop';

            // Plot
            $sql = "select id as plot_id from plots where project_id='".mysql_real_escape_string($project_id)."' AND plot_type='".$plot_type."' AND plot_number='".mysql_real_escape_string($search[2])."'";
            $result = mysql_query($sql, $conn) or die(mysql_error());

            if ($row = mysql_fetch_assoc($result)) {
                $plot_id = $row['plot_id'];

                $response = array (
                    'success' => true,
                    'project_id' => $project_id,
                    'plot_id' => $plot_id
                );
            } else {
                $response = array (
                    'success' => false,
                    'msg' => 'Plot number doesn\'t exist. Enter correct Plot number.'
                );
            }
        }
    } else {
        $response = array (
            'success' => false,
            'msg' => 'Enter correct project shorcode'
        );
    }

    echo json_encode($response);

    include("common/common.end.php");
?>