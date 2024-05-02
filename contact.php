<?php
// Define variables and initialize with empty values
$name = $email = $phone = $message =  "";
$name_err = $email_err = $phone_err = $message_err =  "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $message = htmlspecialchars(trim($_POST['message']));


    // Validate name
    if (empty($name)) {
        $name_err = "Name is required.";
    }

    // Validate email
    if (empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email.";
    }

    // Validate phone
    if (empty($phone)) {
        $phone_err = "Phone is required.";
    }

    // Validate message
    if (empty($message)) {
        $message_err = "Message is required.";
    }

    if (empty($name_err) && empty($email_err) && empty($phone_err) && empty($message_err)) {
        $stmt = $connection->prepare("INSERT INTO contacts (name, email, phone, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $phone, $message);
        $stmt->execute();
        $success = "Message submitted successfully";
    }
}

?>

<?php require './partials/header.inc.php' ?>
<section class="section">
    <div class="container">
        <h1 class="title">Leave a Message</h1>
        <?php if (isset($success)) : ?>
            <div class="notification text-white is-success">
                <button class="delete"></button>
                <strong><?= $success ?></strong>
            </div>
        <?php endif ?>
        <form action="/contact" method="POST">
            <div class="field">
                <label class="label">Name</label>
                <div class="control">
                    <input class="input" type="text" placeholder="Enter your name" name="name">
                </div>
                <p class="help is-danger"><?php echo $name_err; ?></p>
            </div>
            <div class="field">
                <label class="label">Email</label>
                <div class="control">
                    <input class="input" type="email" placeholder="Enter your email" name="email">
                </div>
                <p class="help is-danger"><?php echo $email_err; ?></p>
            </div>
            <div class="field">
                <label class="label">Phone Number</label>
                <div class="control">
                    <input class="input" type="tel" placeholder="Enter your phone number" name="phone">
                </div>
                <p class="help is-danger"><?php echo $phone_err; ?></p>

            </div>
            <div class="field">
                <label class="label">Message</label>
                <div class="control">
                    <textarea class="textarea" placeholder="Enter your message" name="message"></textarea>
                </div>
                <p class="help is-danger"><?php echo $message_err; ?></p>

            </div>
            <div class="field">
                <div class="control">
                    <button class="button is-primary" type="submit">Submit</button>
                </div>
            </div>
        </form>
    </div>
</section>
<?php require './partials/footer.inc.php' ?>