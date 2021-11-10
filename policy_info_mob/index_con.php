<?php
require_once './RemoteConnector.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        try {
            $remote_url = new Pos_RemoteConnector('http://202.51.184.66/policy_info/');
            $remote_status = $remote_url->getErrorMessage();
        } catch (Exception $e) { 
            echo $e->getMessage();
        }
        if(isset($remote_status) && !$remote_status){
            header("Location: http://202.51.184.66/policy_info/");
        } else {
            echo 'Server is not available';
        }
        ?>
    </body>
</html>
