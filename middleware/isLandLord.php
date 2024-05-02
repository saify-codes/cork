<?php

function isLandLord() {
    if(isset($_SESSION['user']) && $_SESSION['user']['role'] != 'LAND LORD'){
        header('Location: /');
        exit;
    }
}

