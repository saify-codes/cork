<?php

// require './config/mysql_connection.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_REQUEST['action'])) {

    if (!empty($_REQUEST['action']) && function_exists($_REQUEST['action'])) {
        $function = $_REQUEST['action'];
        $response = $function();
    } else {
        http_response_code(400);
        echo json_encode("invalid action given");
    }
} else {
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
        echo json_encode($status ? 'testimonial shown' : 'testimonial is not shown');
    }
}

function send_otp()
{

    if (isset($_REQUEST['email']) && !empty($_REQUEST['email'])) {

        global $connection;
        $email = htmlspecialchars($_REQUEST['email']);
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->bind_result($user_id);
        $stmt->execute();
        $stmt->store_result();


        if ($stmt->num_rows > 0) {
            $stmt->fetch();
            $otp = new OTP();
            $otp->generate($user_id);
            if (Mailer::send($email, $otp->get_otp())) {
                $_SESSION['otp']['user_id'] = $user_id;
                echo json_encode(['message' => 'otp generated']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => "something went wrong"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => "email is invalid"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => "email is missing"]);
    }
}

function verify_otp()
{
    // Check if OTP data is stored in session
    if (isset($_SESSION['otp'])) {
        $user_id = $_SESSION['otp']['user_id']; // Get user ID from session

        // Get JSON data from request body
        $json = file_get_contents('php://input');
        $json = json_decode($json);

        // Check if JSON data is received
        if (isset($json)) {
            // Check if OTP is expired
            $is_expired = OTP::is_expired($user_id, $json->otp);

            // Handle OTP verification based on expiration status
            if ($is_expired === false) {
                $_SESSION['otp']['verified'] = true; // Set OTP as verified in session
                echo json_encode(['message' => 'OTP verified']); // Return success message
            } else if ($is_expired === true) {
                http_response_code(400); // Set HTTP status code to 400 (Bad Request)
                echo json_encode(['error' => 'OTP expired']); // Return error message
            } else {
                http_response_code(400); // Set HTTP status code to 400 (Bad Request)
                echo json_encode(['error' => 'OTP invalid']); // Return error message
            }
        } else {
            http_response_code(400); // Set HTTP status code to 400 (Bad Request)
            echo json_encode(['error' => 'OTP missing']); // Return error message
        }
    } else {
        http_response_code(400); // Set HTTP status code to 400 (Bad Request)
        echo json_encode(['error' => 'OTP not generated']); // Return error message
    }
}

function create_new_password()
{
    if (isset($_SESSION['otp']['verified'])) {

        if (isset($_REQUEST['password']) && !empty($_REQUEST['password'])) {

            global $connection;
            $user_id = $_SESSION['otp']['user_id'];
            $password = password_hash($_REQUEST['password'], PASSWORD_BCRYPT);
            $sql = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("si", $password, $user_id);
            $stmt->execute();
            unset($_SESSION['otp']); // Remove OTP related information from the user'
            Session::flash('message', 'Your password has been reset');
            echo json_encode(['message' => 'passwors has been reset']);
        } else {
            http_response_code(400); // Set HTTP status code to 400 (Bad Request)
            echo json_encode(['error' => 'passwors is required']); // Return error message
        }
    } else {
        http_response_code(400); // Set HTTP status code to 400 (Bad Request)
        echo json_encode(['error' => 'Something went wrong']); // Return error message
    }
}


// function send_otp()
// {
//     // Define variables and initialize with empty values
//     $email = $password = $cpassword = "";
//     $email_err = $password_err = $cpassword_err = "";

//     // Processing form data when form is submitted
//     if ($_SERVER["REQUEST_METHOD"] == "POST") {

//         global $connection;
//         $email = htmlspecialchars(trim($_POST['email']));
//         $password = htmlspecialchars(trim($_POST['password']));
//         $cpassword = htmlspecialchars(trim($_POST['confirm_password']));


//         // Validate password
//         if (empty($password)) {
//             $password_err = "Password is required.";
//         }

//         // Validate password
//         if (empty($cpassword)) {
//             $cpassword_err = "Confirm password is required.";
//         }

//         // Validate password
//         if (!empty($password) && !empty($cpassword) && $password != $cpassword) {
//             $cpassword_err = "Password doesn't match.";
//         }

//         // Validate email
//         if (empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
//             $email_err = "Please enter a valid email.";
//         }

//         if (empty($email_err) && empty($password_err) && empty($cpassword_err)) {


//             $sql = "SELECT id FROM users WHERE email = ?";

//             if ($stmt = $connection->prepare($sql)) {
//                 // Bind variables to the prepared statement as parameters
//                 $stmt->bind_param("s", $email);
//                 $stmt->bind_result($user_id);

//                 // Attempt to execute the prepared statement
//                 if ($stmt->execute()) {
//                     // Store result
//                     $stmt->store_result();
//                     if ($stmt->num_rows > 0) {

//                         print_r($stmt->fetch());
//                         print_r($user_id);
//                         $otp = new OTP();
//                         $otp->generate($user_id);

//                         // Reseting password
//                         // $sql = "UPDATE users SET password = ? where email = ?";
//                         // $password_hash = password_hash($password, PASSWORD_BCRYPT);
//                         // $stmt = $connection->prepare($sql);
//                         // $stmt->bind_param('ss', $password_hash, $email);
//                         // if ($stmt->execute()) {
//                         //     Session::flash('message', 'Password reset successfully!', 'is-info');
//                         //     header('Location: /login');
//                         // } else {
//                         //     echo "Oops! Something went wrong. Please try again later.";
//                         // }
//                     } else {
//                         $email_err = "Email not found.";
//                     }
//                 } else {
//                     echo "Oops! Something went wrong. Please try again later.";
//                 }

//                 // Close statement
//                 $stmt->close();
//             }
//         }
//     }
// }
