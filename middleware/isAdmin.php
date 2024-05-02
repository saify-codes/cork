<?php
function isAdmin() {
    if(isset($_SESSION['user']) && $_SESSION['user']['role'] != 'ADMIN'){
        include '401.php';
        exit;
    }
}

