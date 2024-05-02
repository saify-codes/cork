<?php

function isLoggedIn() {
    return isset($_SESSION['user']);
}

function authenticated(){
    if (!isLoggedIn()) {
       header('location: /login');
       exit; 
    }
}
