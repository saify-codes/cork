<?php require './partials/header.inc.php' ?>
<section class="section">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-half">
                <h1 class="title">Reset Password</h1>
                <form action="/ajax?action=send_otp" method="POST" id="password-reset-form">
                    <div class="field mb-3">
                        <label class="label">Email</label>
                        <div class="control">
                            <input class="input" type="email" placeholder="Enter your email" name="email" required>
                            <p class="help is-danger"></p>
                        </div>
                    </div>
                    <div class="field">
                        <div class="control">
                            <button class="button is-primary" type="submit">Reset Password</button>
                        </div>
                    </div>
                </form>

                <form class="is-hidden" action="/ajax?action=verify_otp" method="post" id="otp-form">
                    <h2>Enter OTP send to: </h2>
                    <div class="mt-5">
                        <input class="input" type="text" size="1" maxlength="1" autofocus oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        <input class="input" type="text" size="1" maxlength="1" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        <input class="input" type="text" size="1" maxlength="1" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>
                    <p class="help is-danger"></p>
                </form>

                <form class="is-hidden" action="/ajax?action=create_new_password" method="POST" id="password-create-form">
                    <div class="field mb-3">
                        <label class="label">New password</label>
                        <div class="control">
                            <input class="input" type="text" placeholder="Enter your password" name="password" required>
                            <p class="help is-danger"></p>
                        </div>
                    </div>
                    <div class="field">
                        <div class="control">
                            <button class="button is-primary" type="submit">Create password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    const boxes = document.querySelectorAll('#otp-form input')
    for (const box of boxes) {
        box.addEventListener('input', ({
            target
        }) => {
            if (target.value.length == 1) {
                target.nextElementSibling?.focus()
            } else {
                target.previousElementSibling?.focus()
            }
        })
    }
</script>
<?php require './partials/footer.inc.php' ?>