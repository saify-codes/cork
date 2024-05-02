<?php

// Define variables and initialize with empty values
$name = $password = $email = $phone = $role = "";
$name_err = $password_err = $email_err = $phone_err = $role_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $password = htmlspecialchars(trim($_POST['password']));
    $role = htmlspecialchars(trim($_POST['role']));


    // Validate name
    if (empty($name)) {
        $name_err = "Please enter a name.";
    }

    // Validate phone
    if (empty($phone)) {
        $phone_err = "Please enter a phone.";
    }

    // Validate password
    if (empty($password)) {
        $password_err = "Please enter a password.";
    }
    
    // Validate role
    if (empty($role)) {
        $role_err = "Please select a role.";
    }

    // Restrict user from injection to be admin
    if ($role == 1) {
        $role_err = "Please select a valid role.";
    }

    // Validate email
    if (empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $connection->prepare($sql);
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $email_err = "This email is already taken.";
        }
        $stmt->close();
    }

    if (empty($name_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err) && empty($phone_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO users (name, password, email, phone, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ssssi", $param_name, $param_password, $param_email, $param_phone, $param_role);
        // Set parameters
        $param_name = $name;
        $param_password = password_hash($password, PASSWORD_BCRYPT); // Creates a password hash
        $param_email = $email;
        $param_phone = $phone;
        $param_role = $role;
        $stmt->execute();
        $stmt->close();
        Session::flash('message', 'Account created successfully');
        header("location: /login");
    }
}



// Check input errors before inserting into database

?>

<?php require './partials/header.inc.php' ?>
<section class="section">
    <div class="container">
        <h1 class="title">Register</h1>
        <p class="subtitle">Please fill this form to create an account.</p>
        <form action="/register" method="post">
            <div class="field">
                <label class="label">Name</label>
                <div class="control">
                    <input class="input <?php echo (!empty($name_err)) ? 'is-danger' : ''; ?>" type="text" name="name" value="<?php echo $name; ?>">
                </div>
                <p class="help is-danger"><?php echo $name_err; ?></p>
            </div>
            <div class="field">
                <label class="label">Password</label>
                <div class="control">
                    <input class="input <?php echo (!empty($password_err)) ? 'is-danger' : ''; ?>" type="password" name="password" value="<?php echo $password; ?>">
                </div>
                <p class="help is-danger"><?php echo $password_err; ?></p>
            </div>
            <div class="field">
                <label class="label">Email</label>
                <div class="control">
                    <input class="input <?php echo (!empty($email_err)) ? 'is-danger' : ''; ?>" type="text" name="email" value="<?php echo $email; ?>">
                </div>
                <p class="help is-danger"><?php echo $email_err; ?></p>
            </div>
            <div class="field">
                <label class="label">Phone</label>
                <div class="control">
                    <input class="input <?php echo (!empty($phone_err)) ? 'is-danger' : ''; ?>" type="text" name="phone" value="<?php echo $phone; ?>">
                </div>
                <p class="help is-danger"><?php echo $phone_err; ?></p>
            </div>
            <div class="field">
                <label class="label">You are</label>
                <div class="control has-icons-left">
                    <div class="select ">
                        <select name="role">
                            <option value="2">Land lord</option>
                            <option value="3">Tenant</option>
                        </select>
                    </div>
                    <span class="icon is-medium is-left">
                        <box-icon type='solid' name='user'></box-icon>
                    </span>
                </div>
                <p class="help is-danger"><?php echo $role_err; ?></p>
            </div>
            <div class="field">
                <div class="control">
                    <button type="submit" class="button is-primary">Register</button>
                </div>
            </div>
        </form>
    </div>
</section>
<?php require './partials/footer.inc.php' ?>