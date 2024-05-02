<?php

function isTenant() {
    if(isset($_SESSION['user']) && $_SESSION['user']['role'] != 'TENANT'){
        header('Location: /');
        exit;
    }
}

