<?php
session_start();
include "Connection.php"; 

if (isset($_POST['adsub'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username1']);
    $password = mysqli_real_escape_string($conn, $_POST['password2']);

    $sql = "SELECT id, name, password FROM hospital_list WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $hospital = mysqli_fetch_assoc($result);
        if ($password === $hospital['password']) {
            $_SESSION['hospital_id'] = $hospital['id'];
            $_SESSION['hospital_name'] = $hospital['name'];

            header("Location: Hospital_DashBord/dashboard.php");
            exit();
        } else {
            echo "<script>alert('Incorrect password'); window.location.href='login.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Invalid hospital username'); window.location.href='login.php';</script>";
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>
