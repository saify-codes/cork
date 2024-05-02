<?php
unset($_SESSION['user']);
setcookie('user', NULL, -1);
header('location: /login');
Session::flash('message', 'You have been logged out!', 'is-warning');
?>