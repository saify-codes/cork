<?php $external_css = ['./public/css/dashboard.css'] ?>
<?php require './partials/header.inc.php' ?>
<?php

$sql = "SELECT * from contacts";
$stmt = $connection->prepare($sql);
$stmt->execute();
$contacts = $stmt->get_result();
$stmt->close();

?>
<main>
    <aside>
        <ul>
            <li class="is-flex is-align-content-center" style="gap: 0.5rem;"><box-icon type='solid' name='contact'></box-icon>Contacts</li>
            <li class="is-flex is-align-content-center" style="gap: 0.5rem;"><box-icon type='solid' name='star' color='#ffff00' ></box-icon>Testimonials</li>
            <li class="is-flex is-align-content-center" style="gap: 0.5rem;"><box-icon name='user-detail' type='solid'></box-icon>Land loards</li>
            <li class="is-flex is-align-content-center" style="gap: 0.5rem;"><box-icon name='user-circle' type='solid'></box-icon>Tenants</li>
        </ul>
    </aside>
    <div class="content">
        <table id="myTabl" class="table is-striped is-fullwidth">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Message</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>

                <?php if ($contacts->num_rows == 0) : ?>
                    <tr>
                        <td>No records</td>
                    </tr>
                <?php endif ?>

                <?php while ($contact = $contacts->fetch_assoc()) : ?>
                    <tr>
                        <td><?= $contact['name'] ?></td>
                        <td><?= $contact['email'] ?></td>
                        <td><?= $contact['phone'] ?></td>
                        <td><?= $contact['message'] ?></td>
                        <td><?= $contact['created_at'] ?></td>
                    </tr>
                <?php endwhile ?>

            </tbody>
        </table>
    </div>
</main>
<?php require './partials/footer.inc.php' ?>