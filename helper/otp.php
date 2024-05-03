<?php

class OTP
{
    private $otp;
    private $expiry;

    public function __construct()
    {
        $this->otp = str_pad(mt_rand(0, 999), 3, STR_PAD_LEFT);
        $this->expiry = date('Y-m-d H:i:s', strtotime('+5 minutes'));
    }

    public function generate($user_id)
    {
        global $connection;
        $sql = "INSERT INTO otp (user_id, otp, expiry) values(?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("iss", $user_id, $this->otp, $this->expiry);
        $stmt->execute();
    }

    public function get_otp()
    {
        return $this->otp;
    }

    public function get_expiry()
    {
        return $this->expiry;
    }

    public static function is_expired($user_id, $otp)
    {

        global $connection;
        $sql = "SELECT expiry FROM otp WHERE user_id = ? AND otp = ? ORDER BY expiry DESC LIMIT 1";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("is", $user_id, $otp);
        $stmt->bind_result($expiry);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->fetch();
            // check if the time difference between current and stored expiry date is greater than or equal to 5 minutes.
            return time() > strtotime($expiry);
        } else {
            return null;
        }
    }
}
