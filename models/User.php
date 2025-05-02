<?php
// models/User.php

class User
{
    public $id;
    public $email;
    public $password;
    public $role;

    public static function findByEmail($email)
    {
        global $con;
        $stmt = mysqli_prepare($con, "SELECT id, email, password, role FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }
}
?>
