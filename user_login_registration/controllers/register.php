<?php

session_start();
require_once "../config/database.php";
require_once "../models/User.php";
require_once "../helpers/functions.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnection();
    $user = new User($db);

    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($email) && !empty($password)) {
        if ($user->register($username, $email, $password)) {
            header("Location: ../views/login.php?success=1");
        } else {
            echo "Registration failed.";
        }
    } else {
        echo "All fields are required.";
    }
}