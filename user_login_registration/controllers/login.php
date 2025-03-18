<?php

session_start();
require_once "../config/database.php";
require_once "../models/User.php";
require_once "../helpers/functions.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnection();
    $user = new User($db);

    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];

    if ($user->login($email, $password)) {
        header("Location: ../views/dashboard.php");
    } else {
        echo "Invalid credentials.";
    }
}