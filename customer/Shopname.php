<?php 
if (isset($_POST['shopname'])) {
    $qry = "select service_name from `shopservices` where id=" . $_POST['shopname'];
    $rec = mysql_query($qry);
    if (mysql_num_rows($rec) > 0) {
        while ($res = mysql_fetch_array($rec)) {
            echo $res['service_name'];
        }
    }
}
die();
?>