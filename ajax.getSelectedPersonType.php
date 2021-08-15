<?
include("common/common.start.php");

if (!empty($person_type)) {

    $trActArray = array();
    // customers
    if ($person_type == 'CUSTOMERS') {
        $trActArray = createAccountsArray($CFG_ACCOUNT_CUSTOMERS, 0);
    // dealers
    } else if ($person_type == 'DEALERS') {
        $trActArray = createAccountsArray($CFG_ACCOUNT_DEALERS, 0);
    // land owners
    } else if ($person_type == 'LANDOWNERS') {
        $trActArray = createAccountsArray($CFG_ACCOUNT_LANDOWNERS, 0);
    // investors
    } else if ($person_type == 'INVESTORS') {
        $trActArray = createAccountsArray($CFG_ACCOUNT_INVESTORS, 0);
    // partners
    } else if ($person_type == 'PARTNERS') {
        $trActArray = createAccountsArray($CFG_ACCOUNT_PARTNERS, 0);
    // vendors
    } else if ($person_type == 'VENDORS') {
        $trActArray = createAccountsArray($CFG_ACCOUNT_VENDORS, 0);
    // employees
    } else if ($person_type == 'EMPLOYEES') {
        if($project_id != '') {
            $sql = "SELECT account_id, full_name FROM employees WHERE project_id = $project_id ORDER BY full_name";
            $result = mysql_query($sql, $conn) or die(mysql_error());

            while ($rs = mysql_fetch_array($result)) {
                $trActArray[] = array(
                    "id" => $rs["account_id"],
                    "master_id" => "0",
                    "title" => $rs["full_name"]
                );
            }
        }
    }

    echo createInvoicePersonSelectHTML($trActArray, $account_id, "--Select Name--");

} else {
    echo '<option value="">--Select Name--</option>';
}

include("common/common.end.php");
?>