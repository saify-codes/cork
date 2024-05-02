<?php

function redirectAuthenticated()
{

    if (isLoggedIn()) {
        header("Location: /dashboard");
        exit();
    }
}
