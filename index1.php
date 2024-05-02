<?php
// fetch properties
$sql = "SELECT area from properties";
$stmt = $connection->prepare($sql);
$stmt->execute();
$areas = $stmt->get_result();
$stmt->close();

// fetch testimonials
$sql = "SELECT  * from testimonials 
        JOIN users
        ON users.id = testimonials.user_id
        WHERE role != 1 AND approved = 1";
$stmt = $connection->prepare($sql);
$stmt->execute();
$testimonials = $stmt->get_result();
$stmt->close();
?>
<?php require './partials/header.inc.php' ?>
<section class="hero is-primary is-fullheight">
    <!-- Hero header: title and subtitle -->
    <div class="hero-body">
        <div class="container has-text-centered">
            <h1 class="title">
                Welcome to CorkHouse Lettings
            </h1>
            <h2 class="subtitle">
                Your trusted partner in property lettings in Cork
            </h2>
            <!-- search bar -->
            <form class="searchbar" action="/properties">
                <input name="rent" type="nummber" min="1" placeholder="Rent" />
                <select name="category">
                    <option value="">Bed rooms</option>
                    <option value="1 bed">1 Bed</option>
                    <option value="2 bed">2 Bed</option>
                    <option value="3 bed">3 Bed</option>
                    <option value="4 bed">4 Bed</option>
                </select>
                <select name="area">
                    <option value="">Area</option>

                    <?php if ($areas->num_rows == 0) : ?>
                        <option>No area found</option>
                    <?php endif ?>

                    <?php while ($area = $areas->fetch_assoc()) : ?>
                        <option value="<?= $area['area'] ?>">
                            <?= $area['area'] ?>
                        </option>
                    <?php endwhile ?>
                </select>
                <input name="tenancy" type="nummber" min="1" placeholder="Tenancy" />
                <button>Search</button>
            </form>
        </div>

    </div>

    <!-- Hero footer: additional info -->
    <div class="hero-foot">
        <nav class="tabs is-boxed is-fullwidth">
            <div class="container">
                <ul>
                    <li class="is-active"><a>Home</a></li>
                    <li><a href="property_listing.php">Properties</a></li>
                    <!-- Add more navigation links as needed -->
                </ul>
            </div>
        </nav>
    </div>

</section>

<!-- Testimonials -->
<?php if ($testimonials->num_rows > 0) : ?>
    <section class="section">
        <div class="container">
            <h1 class="title">Testimonials</h1>
            <?php while ($testimonial = $testimonials->fetch_assoc()) : ?>
                <div class="box has-background-white has-text-grey-dark">
                    <p>
                        <span class="font-bold has-text-primary is-size-5">“</span>
                        <?= $testimonial['review'] ?>
                        <span class="font-bold has-text-primary is-size-5">”</span>
                    </p>
                    <div class="is-flex is-align-items-center is-justify-content-start mt-4">
                        <div class="is-flex is-flex-direction-column ml-2 is-align-content-space-between">
                            <span class="font-semibold has-text-weight-bold"><?= $testimonial['name'] ?></span>
                            <span class="is-size-7 is-flex is-align-items-center"><?= $testimonial['email'] ?></span>
                        </div>
                    </div>
                </div>
            <?php endwhile ?>
        </div>
    </section>
<?php endif ?>


<!-- Feature Boxes Section -->
<section class="section">
    <div class="container">
        <div class="columns is-multiline">
            <!-- Feature Box 1 -->
            <div class="column is-one-third">
                <div class="box">
                    <h3 class="title">Featured Property 1</h3>
                    <p class="content">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod
                        tempor incididunt ut labore et dolore magna aliqua.</p>
                    <!-- Add image here -->
                    <a href="#" class="button is-primary">View Details</a>
                </div>
            </div>

            <!-- Feature Box 2 -->
            <div class="column is-one-third">
                <div class="box">
                    <h3 class="title">Featured Property 2</h3>
                    <p class="content">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod
                        tempor incididunt ut labore et dolore magna aliqua.</p>
                    <!-- Add image here -->
                    <a href="#" class="button is-primary">View Details</a>
                </div>
            </div>

            <!-- Feature Box 3 -->
            <div class="column is-one-third">
                <div class="box">
                    <h3 class="title">Featured Property 3</h3>
                    <p class="content">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod
                        tempor incididunt ut labore et dolore magna aliqua.</p>
                    <!-- Add image here -->
                    <a href="#" class="button is-primary">View Details</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require './partials/footer.inc.php' ?>