<?php
// Define variables and initialize with empty values
$email = $password =  "";
$email_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));


    // Validate password
    if (empty($password)) {
        $password_err = "Password is required.";
    }

    // Validate email
    if (empty($email)) {
        $email_err = "Email is required.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id, email, password FROM users WHERE email = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $email, $password_hash);
            $stmt->fetch();
            if (password_verify($password, $password_hash)) {
                Auth::login($id);
            } else {
                $password_err = "Invalid password given.";
            }
        } else {
            $email_err = "Email not found.";
        }

        // Close statement
        $stmt->close();
    }
}


?>

<?php require './partials/header.inc.php' ?>

<section class="section">
    <div class="container">
        <h1 class="title">Login</h1>

        <?php if (Session::has('message')) : ?>
            <div class="notification <?= Session::alert() ?>">
                <button class="delete"></button>
                <span><?= Session::get('message') ?></span>
            </div>
        <?php endif ?>


        <form action="/login" method="post">

            <div class="field">
                <label class="label">Email</label>
                <div class="control">
                    <input class="input <?php echo (!empty($email_err)) ? 'is-danger' : ''; ?>" type="text" name="email" value="<?php echo $email; ?>">
                </div>
                <p class="help is-danger"><?php echo $email_err; ?></p>
            </div>
            <div class="field">
                <label class="label">Password</label>
                <div class="control">
                    <input class="input <?php echo (!empty($password_err)) ? 'is-danger' : ''; ?>" type="password" name="password" value="<?php echo $password; ?>">
                </div>
                <p class="help is-danger"><?php echo $password_err; ?></p>
            </div>
            <div class="field">
                <p>Forgot your password? <a href="/reset">Click here</a> to reset it or <a href="/register">create an account</a>.</p>
            </div>
            <div class="field">
                <div class="control">
                    <button type="submit" class="button is-primary">Login</button>
                </div>
            </div>
        </form>

    </div>
</section>
<?php require './partials/footer.inc.php' ?>