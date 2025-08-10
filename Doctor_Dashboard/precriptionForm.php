<?php
session_start();
include "../Connection.php";

if (!isset($_SESSION['doctor_id'])) {
    header("Location: ../login.php");
    exit();
}

$doctor_id_session = $_SESSION['doctor_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['appointment_id'])) {
        header("Location: appointmentDetails.php");
        exit();
    }
    $appointment_id = intval($_GET['appointment_id']);

    $sql = "SELECT patient_id, doctor_id, hospital_id FROM appointment_patient WHERE appointment_id = $appointment_id LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (!$result || mysqli_num_rows($result) !== 1) {
        die("Invalid appointment or query error.");
    }

    $row = mysqli_fetch_assoc($result);

    $patient_id = $row['patient_id'];
    $doctor_id = $row['doctor_id'];
    $hospital_id = $row['hospital_id'];

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = intval($_POST['appointment_id']);
    $patient_id = intval($_POST['patient_id']);
    $doctor_id = intval($_POST['doctor_id']);
    $hospital_id = intval($_POST['hospital_id']);

    $disease = mysqli_real_escape_string($conn, $_POST['disease']);
    $allergies = mysqli_real_escape_string($conn, $_POST['allergies']);
    $prescription = mysqli_real_escape_string($conn, $_POST['prescription']);
    $date = date('Y-m-d');

    $insert_sql = "INSERT INTO prescription_list (patient_id, doctor_id, hospital_id, disease, allergies, prescription, dates) VALUES
      ('$patient_id', '$doctor_id', '$hospital_id', '$disease', '$allergies', '$prescription', '$date')";

    if (mysqli_query($conn, $insert_sql)) {
        $update_sql = "UPDATE appointment_patient SET status = 'prescribed' WHERE appointment_id = $appointment_id";
        mysqli_query($conn, $update_sql);

        header("Location: appointmentDetails.php?msg=prescribed");
        exit();
    } else {
        $error = "Error saving prescription: " . mysqli_error($conn);
    }
} else {
    header("Location: appointmentDetails.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Prescription - নিরাময়</title>
  <link rel="stylesheet" href="precriptionForm.css" />
  <style>
    .form-group textarea {
      width: 100%;
      border: 1px solid #ccc;
      border-radius: 6px;
      padding: 8px;
      font-size: 1rem;
    }
    .prescribe-btn {
      background-color: #007bff;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
    }
    .prescribe-btn:hover {
      background-color: #0056b3;
    }
    .error-msg {
      color: red;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="sidebar">
      <div class="logo">🩺 নিরাময়</div>
      <button class="logout-btn"><a href="../login.php">Logout</a></button>
    </div>
    <div class="main-content">
      <h1>Prescription Panel</h1>

      <?php if (isset($error)): ?>
        <p class="error-msg"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>

      <form action="precriptionForm.php" method="POST">
        <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($appointment_id); ?>">
        <input type="hidden" name="patient_id" value="<?php echo htmlspecialchars($patient_id); ?>">
        <input type="hidden" name="doctor_id" value="<?php echo htmlspecialchars($doctor_id); ?>">
        <input type="hidden" name="hospital_id" value="<?php echo htmlspecialchars($hospital_id); ?>">

        <div class="form-group" style="margin-bottom:20px;">
          <label for="disease"><strong>Disease:</strong></label><br />
          <textarea id="disease" name="disease" rows="3" required></textarea>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
          <label for="allergies"><strong>Allergies:</strong></label><br />
          <textarea id="allergies" name="allergies" rows="3" required></textarea>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
          <label for="prescription"><strong>Prescription:</strong></label><br />
          <textarea id="prescription" name="prescription" rows="5" required></textarea>
        </div>

        <button type="submit" class="prescribe-btn">Prescribe</button>
        <a href="appointmentDetails.php" style="margin-left: 15px; text-decoration:none; color:#007bff;">← Back</a>
      </form>
    </div>
  </div>
</body>
</html>
