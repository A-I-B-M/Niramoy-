<?php
session_start();
include "../../Connection.php";

if (!isset($_SESSION['hospital_id'])) {
    header("Location: ../login.php");
    exit();
}
if (isset($_GET['id'])) {
    $doctorId = intval($_GET['id']);
    $hospitalId = $_SESSION['hospital_id'];

    $sql = "DELETE FROM doctor_list WHERE id = $doctorId AND hospital_id = $hospitalId";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../doctorlist.php");
        exit();
    } else {
        echo "Error deleting doctor: " . mysqli_error($conn);
    }
} else {
    header("Location: doctorlist.php");
    exit();
}
?>
