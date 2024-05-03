<?php

class Auth
{
    public static function login($user_id)
    {
        global $connection;
        $sql = "SELECT * FROM users WHERE id = ?";

        if ($stmt = $connection->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $user_id);
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                $user = $stmt->get_result()->fetch_assoc();
                if ($user) {
                    $_SESSION['user'] = [
                        'id'    => $user['id'],
                        'name'  => $user['name'],
                        'email' => $user['email'],
                        'phone' => $user['phone'],
                        'role'  => $user['role'],
                    ];
                    setcookie('user', json_encode($_SESSION['user']), time() + 606024 * 5, httponly: true);
                    header("Location: /dashboard");
                    exit;
                }
                // Close statement
                $stmt->close();
            }
        }
    }

    public static function check()
    {
        return isLoggedIn();
    }
    
    public static function user()
    {
        return $_SESSION['user'] ?? NULL;
    }
}
