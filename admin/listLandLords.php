<?php $external_css = ['./public/css/dashboard.css'] ?>
<?php require './partials/header.inc.php' ?>
<?php

$sql = "SELECT *, count(*) AS properties from users 
        JOIN properties
        ON users.id = properties.landlord_id
        WHERE role = 2 
        GROUP BY id";
$stmt = $connection->prepare($sql);
$stmt->execute();
$landlords = $stmt->get_result();
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
                    <th>Properties</th>
                </tr>
            </thead>
            <tbody>

                <?php if ($landlords->num_rows == 0) : ?>
                    <tr>
                        <td>No records</td>
                    </tr>
                <?php endif ?>

                <?php while ($landlord = $landlords->fetch_assoc()) : ?>
                    <tr>
                        <td><?= $landlord['name'] ?></td>
                        <td><?= $landlord['email'] ?></td>
                        <td><?= $landlord['phone'] ?></td>
                        <td><?= $landlord['properties'] ?></td>
                    </tr>
                <?php endwhile ?>

            </tbody>
        </table>
    </div>
</main>
<?php require './partials/footer.inc.php' ?>