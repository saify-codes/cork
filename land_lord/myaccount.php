<?php $external_css = ['./public/css/dashboard.css'] ?>
<?php require './partials/header.inc.php' ?>
<?php



$user = Auth::user();
// Define variables and initialize with empty values
$title = $category = $tenancy = $rent = $description =  "";
$title_err = $category_err = $tenancy_err = $rent_err = $description_err =  "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $title = htmlspecialchars(trim($_POST['title']));
    $category = htmlspecialchars(trim($_POST['category']));
    $tenancy = htmlspecialchars(trim($_POST['tenancy']));
    $rent = htmlspecialchars(trim($_POST['rent']));
    $description = htmlspecialchars(trim($_POST['description']));


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
        $sql = "INSERT INTO Properties (title, category, tenancy, rent, description, landlord_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("sssssi", $title, $category, $tenancy, $rent, $description, $user['id']);
        $stmt->execute();
        $stmt->close();

        $success = "Property added succesfully";
    }
}

// fetch properties
$sql = "SELECT * from properties WHERE landlord_id = {$user['id']}";
$stmt = $connection->prepare($sql);
$stmt->execute();
$properties = $stmt->get_result();
$stmt->close();

?>
<main>
    <aside>
        <ul>
            <li class="is-flex is-align-content-center" style="gap: 0.5rem;"><box-icon name='buildings'   type='solid'></box-icon>Properties</li>
            <li class="is-flex is-align-content-center" style="gap: 0.5rem;"><box-icon name='user'        type='solid' ></box-icon>My account</li>
            <li class="is-flex is-align-content-center" style="gap: 0.5rem;"><box-icon name='user-detail' type='solid'></box-icon>Land loards</li>
            <li class="is-flex is-align-content-center" style="gap: 0.5rem;"><box-icon name='user-circle' type='solid'></box-icon>Tenants</li>
        </ul>
    </aside>
    <div class="content">
        <form class="mb-6" method="post">
            <div class="field">
                <label class="label">Title</label>
                <div class="control">
                    <input class="input" type="text" name="title" placeholder="Enter title" />
                </div>
                <p class="help is-danger"><?php echo $title_err; ?></p>
            </div>

            <div class="field mb-6">
                <label class="label">Category</label>
                <div class="control">
                    <div class="select" style="width: 100%;">
                        <select name="category" style="width: 100%;">
                            <option value="1" selected>1 Bed</option>
                            <option value="2">2 Bed</option>
                            <option value="3">3 Bed</option>
                            <option value="4">4 Bed</option>
                        </select>
                    </div>
                </div>
                <p class="help is-danger"><?php echo $category_err; ?></p>
            </div>

            <div class="columns">
                <div class="column">
                    <div class="field">
                        <label class="label">Tenancy</label>
                        <div class="control">
                            <input class="input" min="1" type="number" name="tenancy" placeholder="Enter tenancy in year" />
                        </div>
                        <p class="help is-danger"><?php echo $tenancy_err; ?></p>
                    </div>
                </div>
                <div class="column">
                    <div class="field">
                        <label class="label">Rent</label>
                        <div class="control">
                            <input class="input" min="1" type="number" name="rent" placeholder="Enter rent" />
                        </div>
                        <p class="help is-danger"><?php echo $rent_err; ?></p>
                    </div>
                </div>
            </div>


            <div class="field">
                <label class="label">Description</label>
                <div class="control">
                    <textarea class="textarea" name="description" rows="5"></textarea>
                </div>
                <p class="help is-danger"><?php echo $description_err; ?></p>
            </div>

            <button class="button is-primary has-text-white">Add property</button>
        </form>

        <table id="myTabl" class="table is-striped is-fullwidth">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Tenancy</th>
                    <th>Rent</th>
                    <th>Description</th>
                    <th>Date listed</th>
                </tr>
            </thead>
            <tbody>

                <?php if ($properties->num_rows == 0) : ?>
                    <tr>
                        <td>No records</td>
                    </tr>
                <?php endif ?>

                <?php while ($property = $properties->fetch_assoc()) : ?>
                    <tr>
                        <td><?= $property['title'] ?></td>
                        <td><?= $property['category'] ?></td>
                        <td><?= $property['tenancy'] ?></td>
                        <td><?= $property['rent'] ?></td>
                        <td><?= $property['description'] ?></td>
                        <td><?= $property['created_at'] ?></td>
                    </tr>
                <?php endwhile ?>

            </tbody>
        </table>
    </div>
</main>
<?php require './partials/footer.inc.php' ?>