<?php
session_start();
include '../../Connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: ../managePatients.php");
    exit();
}

$patient_id = intval($_GET['id']);

$sql = "SELECT * FROM patient_list WHERE id = $patient_id";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) != 1) {
    echo "Patient not found.";
    exit();
}

$patient = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone_no = mysqli_real_escape_string($conn, $_POST['phone_no']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);

    $updateSql = "UPDATE patient_list SET 
                    first_name='$first_name', 
                    last_name='$last_name', 
                    email='$email', 
                    phone_no='$phone_no', 
                    password='$password',
                    gender='$gender'
                  WHERE id=$patient_id";

    if (mysqli_query($conn, $updateSql)) {
        header("Location: ../managePatients.php");
        exit();
    } else {
        $error = "Update failed: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Patient</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f2f4f8;
      margin: 0;
      padding: 0;
    }
    h2 {
      text-align: center;
      margin-top: 30px;
    }
    form {
      background: #fff;
      max-width: 600px;
      margin: 40px auto;
      padding: 25px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    label {
      display: block;
      margin-top: 15px;
      font-weight: 600;
    }
    input, select {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 1rem;
    }
    button {
      margin-top: 20px;
      padding: 10px 20px;
      font-size: 1rem;
      background-color: #00bfff;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    button.cancel-btn {
      background-color: #888;
      margin-left: 10px;
    }
    .error {
      color: red;
      margin-top: 15px;
    }
  </style>
</head>
<body>

<h2>Edit Patient</h2>

<?php if (isset($error)) echo "<p class='error'>" . htmlspecialchars($error) . "</p>"; ?>

<form method="POST">
  <label for="first_name">First Name:</label>
  <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($patient['first_name']) ?>" required>

  <label for="last_name">Last Name:</label>
  <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($patient['last_name']) ?>" required>

  <label for="email">Email:</label>
  <input type="email" id="email" name="email" value="<?= htmlspecialchars($patient['email']) ?>" required>

  <label for="phone_no">Phone Number:</label>
  <input type="text" id="phone_no" name="phone_no" value="<?= htmlspecialchars($patient['phone_no']) ?>" required>

  <label for="password">Password:</label>
  <input type="text" id="password" name="password" value="<?= htmlspecialchars($patient['password']) ?>" required>

  <label for="gender">Gender:</label>
  <select id="gender" name="gender" required>
    <option value="Male" <?= $patient['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
    <option value="Female" <?= $patient['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
  </select>

  <button type="submit">Update</button>
  <button type="button" class="cancel-btn" onclick="window.location.href='../managePatients.php'">Cancel</button>
</form>

</body>
</html>
