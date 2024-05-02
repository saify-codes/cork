<?php

// Start session
session_start();

require_once 'config/mysql_connection.php';
require_once 'middleware/middleware.php';
require_once 'helper/helper.php';

// login user if cookies persist
if (isset($_COOKIE['user'])) {
    $user = json_decode($_COOKIE['user'], true);
    $_SESSION['user'] = $user;
}

// Define routes and attach middleware
$routes = [

    /*Public routes*/
    '/' => ['file' => 'index1.php'],
    '/register' => ['file' => 'register.php', 'middleware' => ['redirectAuthenticated']],
    '/login' => ['file' => 'login.php', 'middleware' => ['redirectAuthenticated']],
    '/logout' => ['file' => 'logout.php', 'middleware' => ['authenticated']],
    '/reset' => ['file' => 'resetPassword.php'],
    '/properties' => ['file' => 'properties.php'],
    
    
    /*Admin | Land lord | Tenant*/
    '/dashboard' => ['file' => 'dashboard.php', 'middleware' => ['authenticated']],

    /*Admin routes*/
    '/landlords' => ['file' => 'adminListLandLords.php', 'middleware' => ['authenticated', 'isAdmin']],
    '/testimonials' => ['file' => 'adminTestimonials.php', 'middleware' => ['authenticated', 'isAdmin']],
    '/contact' => ['file' => 'contact.php'],
];

// Function to handle routes
function handleRoute($uri, $routes)
{
    global $connection; // make db obj available for all views
    $path = ($uri == '/')? '/' : rtrim($uri, '/');
    if (array_key_exists($path, $routes)) {
        $route = $routes[$path];
        if (isset($route['middleware'])) {
            $middlewares = $route['middleware'];
            foreach ($middlewares as $middleware) {
                if (function_exists($middleware)) {
                    $middleware();
                }
            }
        }
        if (isset($route['file'])) {
            include $route['file'];
            exit();
        }
    }
    // Route not found
    http_response_code(404);
    include '404.php';
}

// Get current URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Handle route
handleRoute($uri, $routes);
