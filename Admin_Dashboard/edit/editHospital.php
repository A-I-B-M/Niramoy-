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
$name = $phone_no = $hospital_state = $hospital_city = $hospital_area = $tag_line = $email = $username = $password = "";
$latitude = $longitude = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateHospital'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone_no = mysqli_real_escape_string($conn, $_POST['phone_no']);
    $hospital_state = mysqli_real_escape_string($conn, $_POST['hospital_state']);
    $hospital_city = mysqli_real_escape_string($conn, $_POST['hospital_city']);
    $hospital_area = mysqli_real_escape_string($conn, $_POST['hospital_area']);
    $tag_line = mysqli_real_escape_string($conn, $_POST['tag_line']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $latitude = floatval($_POST['latitude']);
    $longitude = floatval($_POST['longitude']);

    $updateSql = "UPDATE hospital_list SET
        name = '$name',
        phone_no = '$phone_no',
        hospital_state = '$hospital_state',
        hospital_city = '$hospital_city',
        hospital_area = '$hospital_area',
        tag_line = '$tag_line',
        email = '$email',
        username = '$username',
        password = '$password',
        latitude = $latitude,
        longitude = $longitude
        WHERE id = $hospital_id";

    if (mysqli_query($conn, $updateSql)) {
        header("Location: ../manageHospital.php");
        exit();
    } else {
        $error = "Error updating hospital: " . mysqli_error($conn);
    }
} else {
    $sql = "SELECT * FROM hospital_list WHERE id = $hospital_id LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $name = $row['name'];
        $phone_no = $row['phone_no'];
        $hospital_state = $row['hospital_state'];
        $hospital_city = $row['hospital_city'];
        $hospital_area = $row['hospital_area'];
        $tag_line = $row['tag_line'];
        $email = $row['email'];
        $username = $row['username'];
        $password = $row['password'];
        $latitude = $row['latitude'];
        $longitude = $row['longitude'];
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
  <title>Edit Hospital - নিরাময়</title>
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    body {
      background: #f4f7fa;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100vh;
      padding: 40px 15px;
    }
    form {
      background: white;
      padding: 30px 40px;
      border-radius: 12px;
      max-width: 600px;
      width: 100%;
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #007bff;
      font-weight: 700;
      font-size: 28px;
    }
    label {
      display: block;
      margin-top: 18px;
      font-weight: 600;
      color: #333;
      user-select: none;
    }
    input[type="text"],
    input[type="email"],
    input[type="number"],
    input[type="password"] {
      width: 100%;
      padding: 10px 14px;
      margin-top: 6px;
      border: 1.8px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
      transition: border-color 0.3s ease;
    }
    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="number"]:focus,
    input[type="password"]:focus {
      border-color: #007bff;
      outline: none;
    }
    .buttons {
      margin-top: 30px;
      display: flex;
      justify-content: center;
      gap: 15px;
    }
    button, .btn-cancel {
      padding: 12px 28px;
      font-size: 16px;
      font-weight: 600;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      user-select: none;
      transition: background-color 0.3s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 110px;
    }
    button {
      background-color: #007bff;
      color: white;
    }
    button:hover {
      background-color: #0056b3;
    }
    .btn-cancel {
      background-color: #6c757d;
      color: white;
    }
    .btn-cancel:hover {
      background-color: #565e64;
    }
    .error {
      color: #d9534f;
      text-align: center;
      margin-top: 15px;
      font-weight: 600;
    }

    @media (max-width: 640px) {
      form {
        padding: 25px 20px;
      }
      h2 {
        font-size: 24px;
      }
      button, .btn-cancel {
        min-width: 90px;
        padding: 10px 18px;
        font-size: 14px;
      }
    }
  </style>
</head>
<body>

<form method="POST" action="">
  <h2>Edit Hospital</h2>

  <?php if (isset($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <label for="name">Hospital Name</label>
  <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required />

  <label for="phone_no">Phone Number</label>
  <input type="text" id="phone_no" name="phone_no" value="<?= htmlspecialchars($phone_no) ?>" required />

  <label for="hospital_state">State</label>
  <input type="text" id="hospital_state" name="hospital_state" value="<?= htmlspecialchars($hospital_state) ?>" required />

  <label for="hospital_city">City</label>
  <input type="text" id="hospital_city" name="hospital_city" value="<?= htmlspecialchars($hospital_city) ?>" required />

  <label for="hospital_area">Area</label>
  <input type="text" id="hospital_area" name="hospital_area" value="<?= htmlspecialchars($hospital_area) ?>" required />

  <label for="tag_line">Tag Line</label>
  <input type="text" id="tag_line" name="tag_line" value="<?= htmlspecialchars($tag_line) ?>" />

  <label for="email">Email</label>
  <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required />

  <label for="username">Username</label>
  <input type="text" id="username" name="username" value="<?= htmlspecialchars($username) ?>" required />

  <label for="password">Password</label>
  <input type="password" id="password" name="password" value="<?= htmlspecialchars($password) ?>" required />

  <label for="latitude">Latitude</label>
  <input type="number" step="0.000001" id="latitude" name="latitude" value="<?= htmlspecialchars($latitude) ?>" />

  <label for="longitude">Longitude</label>
  <input type="number" step="0.000001" id="longitude" name="longitude" value="<?= htmlspecialchars($longitude) ?>" />

  <div class="buttons">
    <button type="submit" name="updateHospital">Update</button>
    <a href="../manageHospital.php" class="btn-cancel">Cancel</a>
  </div>
</form>

</body>
</html>
