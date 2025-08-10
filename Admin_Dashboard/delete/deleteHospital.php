<?php
session_start();
include '../../Connection.php'; 

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: ../manageHospital.php");
    exit();
}

$hospital_id = intval($_GET['id']);
$error = "";

$sql = "SELECT name, hospital_city FROM hospital_list WHERE id = $hospital_id LIMIT 1";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: ../manageHospital.php");
    exit();
}

$hospital = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm_delete'])) {

        // Check foreign key dependencies before deleting
        $checkSql = "SELECT COUNT(*) as cnt FROM sample_request_hospitals WHERE hospital_id = $hospital_id";
        $checkResult = mysqli_query($conn, $checkSql);
        $row = mysqli_fetch_assoc($checkResult);
        if ($row['cnt'] > 0) {
            $error = "Cannot delete hospital because it has related sample requests.";
        } else {
            $deleteSql = "DELETE FROM hospital_list WHERE id = $hospital_id";
            if (mysqli_query($conn, $deleteSql)) {
                header("Location: ../manageHospital.php?msg=deleted");
                exit();
            } else {
                $error = "Error deleting hospital: " . mysqli_error($conn);
            }
        }

    } else {
        header("Location: ../manageHospital.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Delete Hospital - নিশ্চিতকরণ</title>
  <style>
    body {
      background: #f4f7fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
    }
    .confirm-box {
      background: white;
      padding: 30px 40px;
      border-radius: 12px;
      max-width: 460px;
      width: 100%;
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
      text-align: center;
    }
    h2 {
      color: #dc3545;
      margin-bottom: 20px;
      font-weight: 700;
    }
    p {
      font-size: 18px;
      color: #333;
      margin-bottom: 30px;
    }
    .buttons {
      display: flex;
      justify-content: center;
      gap: 20px;
    }
    button, .btn-cancel {
      padding: 12px 30px;
      font-size: 16px;
      font-weight: 600;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      user-select: none;
      transition: background-color 0.3s ease;
      min-width: 110px;
    }
    button {
      background-color: #dc3545;
      color: white;
    }
    button:hover {
      background-color: #a71d2a;
    }
    .btn-cancel {
      background-color: #6c757d;
      color: white;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
    .btn-cancel:hover {
      background-color: #565e64;
    }
    .error {
      color: #d9534f;
      font-weight: 600;
      margin-top: 15px;
    }
  </style>
</head>
<body>

<div class="confirm-box">
  <h2>Delete Hospital</h2>
  <p>Are you sure you want to delete <strong><?= htmlspecialchars($hospital['name']) ?></strong> located in <strong><?= htmlspecialchars($hospital['hospital_city']) ?></strong>?</p>

  <?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <form method="POST" action="">
    <div class="buttons">
      <button type="submit" name="confirm_delete">Yes, Delete</button>
      <button type="submit" name="cancel" class="btn-cancel">Cancel</button>
    </div>
  </form>
</div>

</body>
</html>
