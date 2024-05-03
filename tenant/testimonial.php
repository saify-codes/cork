<?php $external_css = ['./public/css/dashboard.css'] ?>
<?php require './partials/header.inc.php' ?>
<?php



// Define variables and initialize with empty values
$review = $rating = "";
$review_err = $rating_err = "";


// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user = Auth::user();
    $review = htmlspecialchars(trim($_POST['review']));
    $rating = htmlspecialchars(trim($_POST['rating']));

    // Validate name
    if (empty($review)) {
        $review_err = "Review is required.";
    }


    // Validate phone
    if (empty($rating)) {
        $rating_err = "Rating is required.";
    }



    if (empty($review_err) && empty($rating_err)) {
        $stmt = $connection->prepare("INSERT INTO testimonials (review, rating, user_id) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $review, $rating, $user['id']);
        $stmt->execute();
        $success = "Testimonial submitted successfully";
    }
}

// fetch tenant properties
// $sql = "SELECT * from properties WHERE landlord_id = {$user['id']}";
// $stmt = $connection->prepare($sql);
// $stmt->execute();
// $properties = $stmt->get_result();
// $stmt->close();

?>
<main>
    <aside>
        <ul>
            <li>
                <a href="/testimonial" class="has-text-white is-flex is-align-content-center" style="gap: 0.5rem;">
                    <box-icon name='user-circle' type='solid'></box-icon>My properties
                </a>
            </li>
            <li>
                <a href="/testimonial" class="has-text-white is-flex is-align-content-center" style="gap: 0.5rem;">
                    <box-icon name='star' type='solid'></box-icon>Give testimonial
                </a>
            </li>
        </ul>
    </aside>
    <div class="content">
        <?php if (isset($success)): ?>
            <div class="notification text-white is-success">
                <button class="delete"></button>
                <strong>
                    <?= $success ?>
                </strong>
            </div>
        <?php endif ?>
        <form method="POST">
            <div class="field">
                <label class="label">Review</label>
                <div class="control">
                    <input class="input" type="text" placeholder="Enter your review" name="review">
                </div>
                <p class="help is-danger">
                    <?php echo $review_err; ?>
                </p>
            </div>
            <div class="field">
                <label class="label">Rating</label>
                <div class="select">
                    <select name="rating" id="rating">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>
                <p class="help is-danger">
                    <?php echo $rating_err; ?>
                </p>

            </div>
            <div class="field">
                <div class="control">
                    <button class="button is-primary" type="submit">Submit</button>
                </div>
            </div>
        </form>

    </div>
</main>
<?php require './partials/footer.inc.php' ?>