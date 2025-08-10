<?php
session_start();
include "../Connection.php";

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../patientLogin.php");
    exit();
}

$patient_id = $_SESSION['patient_id'];

// === Handle cancellation form submission (POST) ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_appointment'])) {
    $appointment_date = mysqli_real_escape_string($conn, $_POST['appointment_date']);
    $serial_no = intval($_POST['serial_no']);
    $doctor_id = intval($_POST['doctor_id']);
    $hospital_id = intval($_POST['hospital_id']);

    if (function_exists('mysqli_begin_transaction')) {
        mysqli_begin_transaction($conn);
    } else {
        mysqli_autocommit($conn, false);
    }

    try {
        $delete_sql = "
            DELETE FROM appointment_patient
            WHERE patient_id = $patient_id
              AND appointment_date = '$appointment_date'
              AND serial_no = $serial_no
              AND doctor_id = $doctor_id
              AND hospital_id = $hospital_id
        ";
        if (!mysqli_query($conn, $delete_sql)) {
            throw new Exception("Failed to cancel appointment: " . mysqli_error($conn));
        }

        $update_sql = "
            UPDATE appointment_patient
            SET serial_no = serial_no - 1
            WHERE doctor_id = $doctor_id
              AND hospital_id = $hospital_id
              AND appointment_date = '$appointment_date'
              AND serial_no > $serial_no
        ";
        if (!mysqli_query($conn, $update_sql)) {
            throw new Exception("Failed to shift serial numbers: " . mysqli_error($conn));
        }

        if (function_exists('mysqli_commit')) {
            mysqli_commit($conn);
        } else {
            mysqli_autocommit($conn, true);
        }

        $_SESSION['cancel_success'] = "Appointment canceled successfully.";
    } catch (Exception $e) {
        if (function_exists('mysqli_rollback')) {
            mysqli_rollback($conn);
        } else {
            mysqli_autocommit($conn, true);
        }

        $_SESSION['cancel_error'] = "Error: " . $e->getMessage();
    }

    header("Location: appointment.php");
    exit();
}

// === Fetch appointments for display ===
$query = "
    SELECT 
        a.appointment_date,
        a.serial_no,
        d.name AS doctor_name,
        d.specialization,
        h.name AS hospital_name,
        a.doctor_id,
        a.hospital_id
    FROM appointment_patient a
    JOIN doctor_list d ON a.doctor_id = d.id
    JOIN hospital_list h ON a.hospital_id = h.id
    LEFT JOIN prescription_list p 
        ON p.patient_id = a.patient_id 
        AND p.doctor_id = a.doctor_id 
        AND p.dates = a.appointment_date
    WHERE a.patient_id = $patient_id
      AND p.patient_id IS NULL
      AND a.appointment_date >= CURDATE()
    ORDER BY a.appointment_date DESC, a.serial_no ASC
";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Appointment Details - নিরাময়</title>
  <link rel="stylesheet" href="appointment.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    /* Button styling */
    .cancel-btn {
      background-color: #ff4d4d;
      color: white;
      border: none;
      padding: 6px 12px;
      cursor: pointer;
      border-radius: 4px;
      font-size: 14px;
      margin-top: 8px;
      transition: background-color 0.3s ease;
    }
    .cancel-btn:hover {
      background-color: #e04343;
    }
    /* Notification message styles */
    .notification {
      max-width: 600px;
      margin: 20px auto 30px auto;
      padding: 15px 20px;
      border-radius: 8px;
      font-weight: 600;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      position: relative;
      animation: slideDownFade 0.5s ease forwards;
    }
    .notification.success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    .notification.error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
    /* Close button */
    .notification .close-btn {
      position: absolute;
      top: 8px;
      right: 12px;
      background: transparent;
      border: none;
      font-size: 18px;
      color: inherit;
      cursor: pointer;
    }
    /* Animation for showing message */
    @keyframes slideDownFade {
      from {opacity: 0; transform: translateY(-10px);}
      to {opacity: 1; transform: translateY(0);}
    }
  </style>
</head>
<body>
  <div class="container">
    <aside class="sidebar" style="position: fixed; top: 0; left: 0; width: 220px; height: 100vh; background: #00bfff; color: white; padding: 30px; display: flex; flex-direction: column; justify-content: space-between;">
      <div class="logo">🩺 নিরাময়</div>
      <nav class="ul">
        <ul>
          <li><a href="dashboard.php">Dashboard</a></li>
          <li class="active"><a href="appointment.php">Appointment Details</a></li>
          <li><a href="prescription.php">Prescription</a></li>
          <li><a href="reportlist.php">Sample Request</a></li>
        </ul>
      </nav>
      <button class="logout-btn"><a href="../logout.php" style="color:white; text-decoration:none;">Logout</a></button>
    </aside>

    <main class="main-content" style="margin-left: 220px; padding: 30px;">
      <a href="../index.php" class="back-btn"><i class="fa fa-arrow-left"></i> Back</a>
      <h1>Appointment Details</h1>

      <!-- Notification messages -->
      <?php if (isset($_SESSION['cancel_success'])): ?>
        <div class="notification success" id="notification">
          <?= htmlspecialchars($_SESSION['cancel_success']); unset($_SESSION['cancel_success']); ?>
          <button class="close-btn" onclick="document.getElementById('notification').style.display='none';">&times;</button>
        </div>
      <?php endif; ?>

      <?php if (isset($_SESSION['cancel_error'])): ?>
        <div class="notification error" id="notification">
          <?= htmlspecialchars($_SESSION['cancel_error']); unset($_SESSION['cancel_error']); ?>
          <button class="close-btn" onclick="document.getElementById('notification').style.display='none';">&times;</button>
        </div>
      <?php endif; ?>

      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <section class="hospital-section" style="border: 1px solid #ccc; padding: 15px; margin-bottom: 15px; border-radius: 6px;">
            <h2>🏥 <?= htmlspecialchars($row['hospital_name']) ?></h2>
            <div class="appointment-card">
              <div class="card-header">
                <h3><?= htmlspecialchars($row['doctor_name']) ?></h3>
              </div>
              <p><strong>Date:</strong> <?= htmlspecialchars(date("d M Y", strtotime($row['appointment_date']))) ?></p>
              <p><strong>Serial No:</strong> <?= htmlspecialchars($row['serial_no']) ?></p>
              <p><strong>Department:</strong> <?= htmlspecialchars($row['specialization']) ?></p>

              <form method="post" onsubmit="return confirm('Are you sure you want to cancel this appointment?');" style="display:inline;">
                <input type="hidden" name="appointment_date" value="<?= htmlspecialchars($row['appointment_date']) ?>" />
                <input type="hidden" name="serial_no" value="<?= htmlspecialchars($row['serial_no']) ?>" />
                <input type="hidden" name="doctor_id" value="<?= htmlspecialchars($row['doctor_id']) ?>" />
                <input type="hidden" name="hospital_id" value="<?= htmlspecialchars($row['hospital_id']) ?>" />
                <button type="submit" name="cancel_appointment" class="cancel-btn">Cancel</button>
              </form>
            </div>
          </section>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No upcoming appointments without prescriptions found.</p>
      <?php endif; ?>
    </main>
  </div>

  <script>
    // Auto-hide notification after 5 seconds
    window.onload = function() {
      const notification = document.getElementById('notification');
      if (notification) {
        setTimeout(() => {
          notification.style.transition = 'opacity 0.5s ease';
          notification.style.opacity = '0';
          setTimeout(() => {
            if(notification.parentNode) {
              notification.parentNode.removeChild(notification);
            }
          }, 500);
        }, 5000);
      }
    };
  </script>
</body>
</html>
