<?php
session_start();
include '../../Connection.php'; 

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../manageDoctor.php?error=invalid_id");
    exit();
}

$doctor_id = intval($_GET['id']);

$sql = "DELETE FROM doctor_list WHERE id = $doctor_id";
if (mysqli_query($conn, $sql)) {
    $success = true;
} else {
    $error = "Error deleting doctor: " . mysqli_error($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Delete Doctor</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f8fb;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }

    .message-box {
      background: white;
      padding: 30px 40px;
      border-radius: 8px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      text-align: center;
    }

    .message-box h2 {
      color: #00bfff;
      margin-bottom: 15px;
    }

    .success {
      color: green;
    }

    .error {
      color: red;
    }

    .back-btn {
      margin-top: 20px;
      background-color: #00bfff;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      text-decoration: none;
      font-size: 1rem;
    }

    .back-btn:hover {
      background-color: #009cd9;
    }
  </style>
</head>
<body>

<div class="message-box">
  <?php if (isset($success) && $success): ?>
    <h2 class="success">Doctor deleted successfully!</h2>
  <?php else: ?>
    <h2 class="error"><?= htmlspecialchars($error ?? 'Something went wrong.') ?></h2>
  <?php endif; ?>
  
  <a href="../manageDoctor.php" class="back-btn">Back to Manage Doctors</a>
</div>

</body>
</html>
