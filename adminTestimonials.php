<?php

$external_css = ['./public/css/dashboard.css'];
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $testimonial_id = trim($_POST['testimonial_id']);
    $status = trim($_POST['status']);

    if (isset($testimonial_id, $status)) {
        $sql = "UPDATE testimonials SET approved = ? WHERE id = ?";
        print $sql;
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('ii', $status, $testimonial_id);
        $stmt->execute();
        $stmt->close();
        exit;
    }
}

$sql = "SELECT  *, testimonials.id from testimonials 
        JOIN users
        ON users.id = testimonials.user_id
        WHERE role != 1";
$stmt = $connection->prepare($sql);
$stmt->execute();
$testimonials = $stmt->get_result();
$stmt->close();

?>
<?php require './partials/header.inc.php' ?>
<main>
    <aside>
        <ul>
            <li class="is-flex is-align-content-center" style="gap: 0.5rem;"><box-icon type='solid' name='contact'></box-icon>Contacts</li>
            <li class="is-flex is-align-content-center" style="gap: 0.5rem;"><box-icon type='solid' name='star' color='#ffff00'></box-icon>Testimonials</li>
            <li class="is-flex is-align-content-center" style="gap: 0.5rem;"><box-icon name='user-detail' type='solid'></box-icon>Land loards</li>
            <li class="is-flex is-align-content-center" style="gap: 0.5rem;"><box-icon name='user-circle' type='solid'></box-icon>Tenants</li>
        </ul>
    </aside>
    <div class="content">
        <table id="testimonials" class="table is-striped is-fullwidth">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Review</th>
                    <th>Rating</th>
                    <th>Approved</th>
                </tr>
            </thead>
            <tbody>

                <?php if ($testimonials->num_rows == 0) : ?>
                    <tr>
                        <td>No records</td>
                    </tr>
                <?php endif ?>

                <?php while ($testimonial = $testimonials->fetch_assoc()) : ?>
                    <tr>
                        <td><?= $testimonial['name'] ?></td>
                        <td><?= $testimonial['email'] ?></td>
                        <td><?= $testimonial['phone'] ?></td>
                        <td><?= $testimonial['review'] ?></td>
                        <td><?= Html::rating($testimonial['rating']) ?></td>
                        <td> <?= Html::switch($testimonial['id'], $testimonial['approved'] ? 'checked' : '') ?> </td>
                    </tr>
                <?php endwhile ?>

            </tbody>
        </table>
    </div>
</main>
<?php require './partials/footer.inc.php' ?>