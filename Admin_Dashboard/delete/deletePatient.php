<?php
session_start();
include '../../Connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "DELETE FROM patient_list WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        header("Location: ../managePatients.php");
        exit();
    } else {
        echo "Delete failed: " . mysqli_error($conn);
    }
} else {
    header("Location: ../managePatients.php");
    exit();
}
