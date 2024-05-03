<?php

// require './config/mysql_connection.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_REQUEST['action'])) {

    if(!empty($_REQUEST['action']) && function_exists($_REQUEST['action'])){
        $function = $_REQUEST['action'];
        $function();
    }else{
        http_response_code(400);
        echo json_encode("invalid action given");
    }

}else{
    http_response_code(405);
    echo json_encode("method not allowed or missing action");
}

function toggle_testimonial()
{
    global $connection;
    // Processing form data when form is submitted

    $testimonial_id = trim($_POST['testimonial_id']) ?? null;
    $status = trim($_POST['status']) ?? null;

    if (isset($testimonial_id, $status)) {
        $sql = "UPDATE testimonials SET approved = ? WHERE id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('ii', $status, $testimonial_id);
        $stmt->execute();
        $stmt->close();
        echo json_encode($status?'testimonial shown' : 'testimonial is not shown');
    }
}

?>