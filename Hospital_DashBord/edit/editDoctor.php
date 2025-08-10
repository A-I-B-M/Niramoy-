<?php
session_start();
include "../../Connection.php";

// Check if hospital is logged in
if (!isset($_SESSION['hospital_id'])) {
    header("Location: ../login.php");
    exit();
}

$hospitalId = $_SESSION['hospital_id'];

// Check for doctor ID
if (!isset($_GET['id'])) {
    header("Location: ../doctorlist.php");
    exit();
}

$doctor_id = intval($_GET['id']);

// Fetch doctor data
$sql = "SELECT * FROM doctor_list WHERE id = $doctor_id AND hospital_id = $hospitalId LIMIT 1";
$result = mysqli_query($conn, $sql);
if (!$result || mysqli_num_rows($result) !== 1) {
    echo "Doctor not found or does not belong to your hospital.";
    exit();
}
$doctor = mysqli_fetch_assoc($result);

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $specialization = mysqli_real_escape_string($conn, $_POST['specialization']);
    $phone_no = mysqli_real_escape_string($conn, $_POST['phone_no']);
    $experience_years = intval($_POST['experience_years']);
    $availability = mysqli_real_escape_string($conn, $_POST['availability']);
    $fees = intval($_POST['fees']);

    $updateSql = "UPDATE doctor_list SET 
        name='$name', 
        specialization='$specialization', 
        phone_no='$phone_no', 
        experience_years=$experience_years, 
        availability='$availability', 
        fees=$fees 
        WHERE id=$doctor_id AND hospital_id=$hospitalId";

    if (mysqli_query($conn, $updateSql)) {
        header("Location: ../doctorlist.php"); // ✅ Corrected path
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
  <title>Edit Doctor - Niramoy</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f7f9fc;
      font-family: Arial, sans-serif;
      padding: 40px;
    }
    form {
      max-width: 600px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 25px;
    }
    .form-control {
      margin-bottom: 15px;
    }
    .btn {
      width: 48%;
    }
  </style>
</head>
<body>

<form method="POST">
  <h2>Edit Doctor</h2>

  <?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <label for="name">Full Name</label>
  <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($doctor['name']) ?>" required>

  <label for="specialization">Specialization</label>
  <input type="text" id="specialization" name="specialization" class="form-control" value="<?= htmlspecialchars($doctor['specialization']) ?>" required>

  <label for="phone_no">Phone Number</label>
  <input type="text" id="phone_no" name="phone_no" class="form-control" value="<?= htmlspecialchars($doctor['phone_no']) ?>" required>

  <label for="experience_years">Experience Years</label>
  <input type="number" id="experience_years" name="experience_years" class="form-control" value="<?= htmlspecialchars($doctor['experience_years']) ?>" required>

  <label for="availability">Availability</label>
  <input type="text" id="availability" name="availability" class="form-control" value="<?= htmlspecialchars($doctor['availability']) ?>" required>

  <label for="fees">Consultation Fee (BDT)</label>
  <input type="number" id="fees" name="fees" class="form-control" value="<?= htmlspecialchars($doctor['fees']) ?>" required>

  <div class="d-flex justify-content-between">
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="../doctorlist.php" class="btn btn-secondary">Cancel</a>
  </div>
</form>

</body>
</html>
