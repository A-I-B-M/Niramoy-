<?php
session_start();
include '../../Connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: ../manageDoctor.php");
    exit();
}

$doctor_id = intval($_GET['id']);

$sql = "SELECT * FROM doctor_list WHERE id = $doctor_id LIMIT 1";
$result = mysqli_query($conn, $sql);
if (!$result || mysqli_num_rows($result) !== 1) {
    echo "Doctor not found.";
    exit();
}
$doctor = mysqli_fetch_assoc($result);

$hospitalSql = "SELECT id, name FROM hospital_list ORDER BY name ASC";
$hospitalResult = mysqli_query($conn, $hospitalSql);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $specialization = mysqli_real_escape_string($conn, $_POST['specialization']);
    $phone_no = mysqli_real_escape_string($conn, $_POST['phone_no']);
    $hospital_id = intval($_POST['hospital_id']);
    $experience_years = intval($_POST['experience_years']);
    $availability = mysqli_real_escape_string($conn, $_POST['availability']);
    $fees = intval($_POST['fees']);

    $updateSql = "UPDATE doctor_list SET 
        name='$name', 
        specialization='$specialization', 
        phone_no='$phone_no', 
        hospital_id=$hospital_id, 
        experience_years=$experience_years, 
        availability='$availability', 
        fees=$fees 
        WHERE id=$doctor_id";

    if (mysqli_query($conn, $updateSql)) {
        header("Location: ../manageDoctor.php");
        exit();
    } else {
        $error = "Error updating doctor: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Doctor - Admin Panel</title>
  <link rel="stylesheet" href="../dashboard.css" />
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f6f9;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      min-height: 100vh;
      align-items: flex-start;
      padding-top: 40px;
    }
    form {
      background: #fff;
      padding: 30px 40px;
      border-radius: 8px;
      box-shadow: 0 6px 12px rgba(0,0,0,0.1);
      max-width: 600px;
      width: 100%;
    }
    h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #333;
    }
    label {
      display: block;
      margin-top: 15px;
      font-weight: 600;
      color: #555;
    }
    input, select {
      width: 100%;
      padding: 10px 12px;
      margin-top: 6px;
      border-radius: 5px;
      border: 1.5px solid #ccc;
      font-size: 1rem;
      transition: border-color 0.3s ease;
    }
    input:focus, select:focus {
      outline: none;
      border-color: #00bfff;
      box-shadow: 0 0 5px #00bfff33;
    }
    button {
      margin-top: 25px;
      padding: 12px 25px;
      border: none;
      border-radius: 5px;
      font-size: 1.1rem;
      cursor: pointer;
      transition: background-color 0.3s ease;
      color: white;
    }
    button[type="submit"] {
      background-color: #00bfff;
    }
    button[type="submit"]:hover {
      background-color: #0099cc;
    }
    button.cancel-btn {
      background-color: #777;
      margin-left: 15px;
    }
    button.cancel-btn:hover {
      background-color: #555;
    }
    .error {
      color: #d93025;
      margin-top: 15px;
      font-weight: 600;
      text-align: center;
    }
  </style>
</head>
<body>

<form method="POST" action="">
  <h2>Edit Doctor</h2>

  <?php if (isset($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <label for="name">Name:</label>
  <input type="text" id="name" name="name" value="<?= htmlspecialchars($doctor['name']) ?>" required />

  <label for="specialization">Specialization:</label>
  <input type="text" id="specialization" name="specialization" value="<?= htmlspecialchars($doctor['specialization']) ?>" required />

  <label for="phone_no">Phone Number:</label>
  <input type="text" id="phone_no" name="phone_no" value="<?= htmlspecialchars($doctor['phone_no']) ?>" required />

  <label for="hospital_id">Hospital:</label>
  <select id="hospital_id" name="hospital_id" required>
    <option value="">-- Select Hospital --</option>
    <?php while ($hosp = mysqli_fetch_assoc($hospitalResult)): ?>
      <option value="<?= $hosp['id'] ?>" <?= $hosp['id'] == $doctor['hospital_id'] ? 'selected' : '' ?>>
        <?= htmlspecialchars($hosp['name']) ?>
      </option>
    <?php endwhile; ?>
  </select>

  <label for="experience_years">Experience (years):</label>
  <input type="number" id="experience_years" name="experience_years" min="0" value="<?= htmlspecialchars($doctor['experience_years']) ?>" required />

  <label for="availability">Availability:</label>
  <input type="text" id="availability" name="availability" value="<?= htmlspecialchars($doctor['availability']) ?>" required />

  <label for="fees">Fees (in Taka):</label>
  <input type="number" id="fees" name="fees" min="0" value="<?= htmlspecialchars($doctor['fees']) ?>" required />

  <div style="margin-top: 30px; text-align: center;">
    <button type="submit">Update</button>
    <button type="button" class="cancel-btn" onclick="window.location.href='../manageDoctor.php'">Cancel</button>
  </div>
</form>

</body>
</html>
