<?php
session_start();
include "Connection.php";  

if (isset($_POST['docsub1'])) {
    $phone = mysqli_real_escape_string($conn, $_POST['username3']);
    $password = $_POST['password3']; 
    $stmt = $conn->prepare("SELECT id, name, password FROM doctor_list WHERE phone_no = ? LIMIT 1");
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows == 1) {
        $doctor = $result->fetch_assoc();

        if (password_needs_rehash($doctor['password'], PASSWORD_DEFAULT)) {
            if ($password === $doctor['password']) {
                $_SESSION['doctor_id'] = $doctor['id'];
                $_SESSION['doctor_name'] = $doctor['name'];
                header("Location: Doctor_Dashboard/dashboard.php");
                exit();
            } else {
                echo "<script>alert('Incorrect password'); window.location.href='login.php';</script>";
                exit();
            }
        } else {
            if (password_verify($password, $doctor['password'])) {
                $_SESSION['doctor_id'] = $doctor['id'];
                $_SESSION['doctor_name'] = $doctor['name'];
                header("Location: Doctor_Dashboard/dashboard.php");
                exit();
            } else {
                echo "<script>alert('Incorrect password'); window.location.href='login.php';</script>";
                exit();
            }
        }
    } else {
        echo "<script>alert('Invalid phone number'); window.location.href='login.php';</script>";
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>
