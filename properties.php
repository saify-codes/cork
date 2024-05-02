<?php

$where_clause = "WHERE 1";

// Check if the parameter exists in the GET request and append it to the WHERE clause if it does
if (isset($_GET['rent']) && !empty($_GET['rent'])) {
    $rent = htmlspecialchars($_GET['rent']);
    $where_clause .= " AND rent <= $rent";
}

if (isset($_GET['area']) && !empty($_GET['area'])) {
    $area = htmlspecialchars($_GET['area']);
    $where_clause .= " AND area = '$area'";
}

if (isset($_GET['tenancy']) && !empty($_GET['tenancy'])) {
    $tenancy = htmlspecialchars($_GET['tenancy']);
    $where_clause .= " AND tenancy <= $tenancy";
}

if (isset($_GET['category']) && !empty($_GET['category'])) {
    $category = htmlspecialchars($_GET['category']);
    $where_clause .= " AND category = '$category'";
}


// fetch properties
$sql = "SELECT * from properties
        JOIN users ON users.id = properties.landlord_id $where_clause";  // trim the last AND to not cause sql syntaxt error
$stmt = $connection->prepare($sql);
$stmt->execute();
$properties = $stmt->get_result();
$stmt->close();

?>
<?php require './partials/header.inc.php' ?>
<section class="section">
    <div class="container">
        <h1 class="title">Properties</h1>
        <div class="grid is-col-min-12">
            <?php if ($properties->num_rows == 0) : ?>
                <div class="cell"> No property found </div>
            <?php endif ?>

            <?php while ($property = $properties->fetch_assoc()) : ?>
                <div class="cell">
                    <div class="card">
                        <div class="card-image">
                            <figure class="image is-4by3">
                                <img src="/storage/placeholder.jpg" alt="Placeholder image" />
                            </figure>
                        </div>
                        <div class="card-content">
                            <div class="media">
                                <div class="media-left">
                                    <figure class="image is-48x48">
                                        <img src="/storage/avatar.jpg" alt="Placeholder image" />
                                    </figure>
                                </div>
                                <div class="media-content">
                                    <p class="title is-4 is-capitalized"><?= $property['name'] ?></p>
                                    <p class="subtitle is-6"><?= $property['email'] ?></p>
                                </div>
                            </div>

                            <div class="content">
                                <?= $property['description'] ?>
                                <br />
                                <span class="has-text-weight-bold">
                                    Tenancy: <?= $property['tenancy'] ?> years | Rent $<?= $property['rent'] ?> | <?= $property['category'] ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile ?>
        </div>
    </div>
</section>
<?php require './partials/footer.inc.php' ?>