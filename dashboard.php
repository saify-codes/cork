<?php

$user = Auth::user();
match ($user['role']) {
    'ADMIN' => include 'adminDashboard.php',
    'LAND LORD' => include 'landLordDashboard.php',
    'TENANT' => include 'tenantDashboard.php',
};