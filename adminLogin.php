<?php
session_start();
include "Connection.php";  

if (isset($_POST['adminsub'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT id, username, password FROM admin WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);
        if ($password === $admin['password']) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];

            header("Location: Admin_Dashboard/dashboard.php"); 
            exit();
        } else {
            echo "<script>alert('Incorrect password'); window.location.href='login.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Invalid username'); window.location.href='login.php';</script>";
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>
