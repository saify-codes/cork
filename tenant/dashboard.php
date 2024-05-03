<?php $external_css = ['./public/css/dashboard.css'] ?>
<?php require './partials/header.inc.php' ?>
<?php



$user = Auth::user();
// Define variables and initialize with empty values
$title = $category = $tenancy = $rent = $description = $area = "";
$title_err = $category_err = $tenancy_err = $rent_err = $description_err = $area_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $title = htmlspecialchars(trim($_POST['title']));
    $category = htmlspecialchars(trim($_POST['category']));
    $tenancy = htmlspecialchars(trim($_POST['tenancy']));
    $rent = htmlspecialchars(trim($_POST['rent']));
    $area = htmlspecialchars(trim($_POST['area']));
    $description = htmlspecialchars(trim($_POST['description']));


    // Validate area
    if (empty($area)) {
        $area_err = "Area is required.";
    }

    // Validate title
    if (empty($title)) {
        $title_err = "Title is required.";
    }

    // Validate category
    if (empty($category)) {
        $category_err = "Category is required.";
    }

    // Validate tenancy
    if (empty($tenancy)) {
        $tenancy_err = "Tenancy length is required.";
    }

    // Validate rent
    if (empty($rent)) {
        $rent_err = "Rent amount is required.";
    }

    // Validate description
    if (empty($description)) {
        $description_err = "Description is required.";
    }

    // If all fields are filled, you can proceed with further processing like database insertion or other actions
    if (empty($title_err) && empty($category_err) && empty($tenancy_err) && empty($rent_err) && empty($description_err)) {
        // Assuming $connection is your MySQLi connection object

        // SQL INSERT statement with placeholders
        $sql = "INSERT INTO Properties (title, category, tenancy, rent, description, area, landlord_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ssssssi", $title, $category, $tenancy, $rent, $description, $area, $user['id']);
        $stmt->execute();
        $stmt->close();
    }
}

// fetch tenant properties
$sql = "SELECT * from properties WHERE landlord_id = {$user['id']}";
$stmt = $connection->prepare($sql);
$stmt->execute();
$properties = $stmt->get_result();
$stmt->close();

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
    </div>
</main>
<?php require './partials/footer.inc.php' ?>