<?php
// Define variables and initialize with empty values
$email = $password =  $cpassword = "";
$email_err = $password_err = $cpassword_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $cpassword = htmlspecialchars(trim($_POST['confirm_password']));


    // Validate password
    if (empty($password)) {
        $password_err = "Password is required.";
    }

    // Validate password
    if (empty($cpassword)) {
        $cpassword_err = "Confirm password is required.";
    }

    // Validate password
    if (!empty($password) && !empty($cpassword) && $password != $cpassword) {
        $cpassword_err = "Password doesn't match.";
    }

    // Validate email
    if (empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email.";
    }

    if (empty($email_err) && empty($password_err) && empty($cpassword_err)) {

        $sql = "SELECT email FROM users WHERE email = ?";

        if ($stmt = $connection->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $email);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();
                if ($stmt->num_rows > 0) {

                    // Reseting password
                    $sql = "UPDATE users SET password = ? where email = ?";
                    $password_hash =  password_hash($password, PASSWORD_BCRYPT);
                    $stmt = $connection->prepare($sql);
                    $stmt->bind_param('ss', $password_hash, $email);
                    if ($stmt->execute()) {
                        Session::flash('message','Password reset successfully!', 'is-info');
                        header('Location: /login');
                    } else {
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                } else {
                    $email_err = "Email not found.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
}
?>

<?php require './partials/header.inc.php' ?>
<section class="section">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-half">
                <h1 class="title">Reset Password</h1>
                <form action="/reset" method="POST">
                    <div class="field">
                        <label class="label">Email</label>
                        <div class="control">
                            <input class="input" type="email" placeholder="Enter your email" name="email">
                            <p class="help is-danger"><?php echo $email_err; ?></p>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">New Password</label>
                        <div class="control">
                            <input class="input" type="password" placeholder="Enter your new password" name="password">
                        </div>
                        <p class="help is-danger"><?php echo $password_err; ?></p>
                    </div>
                    <div class="field">
                        <label class="label">Confirm New Password</label>
                        <div class="control">
                            <input class="input" type="password" placeholder="Confirm your new password" name="confirm_password">
                        </div>
                        <p class="help is-danger"><?php echo $cpassword_err; ?></p>
                    </div>
                    <div class="field">
                        <div class="control">
                            <button class="button is-primary" type="submit">Reset Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php require './partials/footer.inc.php' ?>