<?php
session_start();
include '../Connection.php';

if (!isset($_SESSION['patient_logged_in'])) {
    die("Error: Patient ID not set in session. Please login first.");
}

$patientId = $_SESSION['patient_id'];

if (isset($_POST['add_reminder'])) {
    $medicine_name = trim($_POST['medicine_name']);
    $reminder_time = trim($_POST['reminder_time']);
    if ($medicine_name !== '' && $reminder_time !== '') {
        $stmt = $conn->prepare("INSERT INTO medicine_reminders (patient_id, medicine_name, reminder_time) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $patientId, $medicine_name, $reminder_time);
        $stmt->execute();
        $stmt->close();
    }
}

if (isset($_POST['delete_reminder'])) {
    $reminder_id = intval($_POST['reminder_id']);
    $stmt = $conn->prepare("DELETE FROM medicine_reminders WHERE id = ? AND patient_id = ?");
    $stmt->bind_param("ii", $reminder_id, $patientId);
    $stmt->execute();
    $stmt->close();
}

if (isset($_POST['add_allergy'])) {
    $allergy_name = trim($_POST['allergy_name']);
    $notes = trim($_POST['notes']);
    if ($allergy_name !== '') {
        $stmt = $conn->prepare("INSERT INTO patient_allergies (patient_id, allergy_name, notes) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $patientId, $allergy_name, $notes);
        $stmt->execute();
        $stmt->close();
    }
}

if (isset($_POST['delete_allergy'])) {
    $allergy_id = intval($_POST['allergy_id']);
    $stmt = $conn->prepare("DELETE FROM patient_allergies WHERE id = ? AND patient_id = ?");
    $stmt->bind_param("ii", $allergy_id, $patientId);
    $stmt->execute();
    $stmt->close();
}

$stmt = $conn->prepare("SELECT first_name, last_name FROM patient_list WHERE id = ?");
$stmt->bind_param("i", $patientId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) die("Patient not found.");
$patient = $result->fetch_assoc();
$stmt->close();

$stmt = $conn->prepare("SELECT id, medicine_name, reminder_time FROM medicine_reminders WHERE patient_id = ? ORDER BY reminder_time ASC");
$stmt->bind_param("i", $patientId);
$stmt->execute();
$reminders_result = $stmt->get_result();
$stmt->close();

$stmt = $conn->prepare("SELECT id, allergy_name, notes FROM patient_allergies WHERE patient_id = ?");
$stmt->bind_param("i", $patientId);
$stmt->execute();
$allergies_result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Patient Dashboard - নিরাময়</title>
  <link rel="stylesheet" href="dashboard.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    .medicine-reminder-section {
      margin-top: 2rem;
      background: #f0f8ff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(47, 128, 237, 0.15);
      max-width: 600px;
    }
    .medicine-reminder-section h2 {
      color: #2F80ED;
      margin-bottom: 15px;
      font-weight: 600;
    }
    .reminder-form input[type="text"],
    .reminder-form input[type="time"] {
      padding: 8px 12px;
      margin-right: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 1rem;
    }
    .reminder-form button {
      background-color: #2F80ED;
      border: none;
      color: white;
      padding: 9px 15px;
      border-radius: 5px;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    .reminder-form button:hover {
      background-color: #1c5dbd;
    }
    .reminder-list {
      margin-top: 20px;
    }
    .reminder-item {
      background: white;
      border-radius: 7px;
      padding: 12px 15px;
      margin-bottom: 10px;
      box-shadow: 0 1px 4px rgba(0,0,0,0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .reminder-item time {
      font-weight: 600;
      color: #2F80ED;
    }
    .delete-btn {
      background: #e74c3c;
      border: none;
      color: white;
      padding: 5px 10px;
      border-radius: 5px;
      cursor: pointer;
      font-weight: 600;
      transition: background-color 0.2s ease;
    }
    .delete-btn:hover {
      background: #c0392b;
    }
  </style>
</head>
<body>

  <div class="container">
    <aside class="sidebar" style="position: fixed; top: 0; left: 0; width: 220px; height: 100vh; background: #00bfff; color: white; padding: 30px; display: flex; flex-direction: column; justify-content: space-between;">
      <div class="logo">🩺 নিরাময়</div>
      <nav>
        <ul>
          <li class="active"><a href="dashboard.php">Dashboard</a></li>
          <li><a href="appointment.php">Appointment Details</a></li>
          <li><a href="prescription.php">Prescription</a></li>
          <li><a href="reportlist.php">Sample Request</a></li>
        </ul>
      </nav>
      <button class="logout-btn"><a href="../logout.php">Logout</a></button>
    </aside>

    <main class="main-content" style="margin-left: 220px; padding: 30px; height: 100vh; overflow-y: auto; flex: 1;">
      <a href="../index.php" class="back-btn"><i class="fa fa-arrow-left"></i> Back</a>
      <h1>Welcome, <?= htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']) ?></h1>
      <div class="card-grid">
        <div class="card gray">
          <i class="fa-solid fa-calendar-check icon"></i>
          <h3>Appointment Details</h3>
          <a href="appointment.php">View Appointments</a>
        </div>
        <div class="card gray">
          <i class="fa-solid fa-vials icon"></i>
          <h3>Prescription</h3>
          <a href="prescription.php">View Prescription</a>
        </div>
        <div class="card gray">
          <i class="fa-solid fa-file-medical icon"></i>
          <h3>Report List</h3>
          <a href="reportlist.php">Sample Request</a>
        </div>
      </div>

      <section class="medicine-reminder-section">
        <h2>Medicine Reminders</h2>
        <form method="post" class="reminder-form">
          <input type="text" name="medicine_name" placeholder="Medicine Name" required />
          <input type="time" name="reminder_time" required />
          <button type="submit" name="add_reminder">Add Reminder</button>
        </form>

        <div class="reminder-list">
          <?php if ($reminders_result->num_rows > 0): ?>
            <?php while ($reminder = $reminders_result->fetch_assoc()): ?>
              <div class="reminder-item">
                <div>
                  <strong><?= htmlspecialchars($reminder['medicine_name']) ?></strong>
                  <time><?= htmlspecialchars(date("h:i A", strtotime($reminder['reminder_time']))) ?></time>
                </div>
                <form method="post" style="margin:0;">
                  <input type="hidden" name="reminder_id" value="<?= $reminder['id'] ?>" />
                  <button type="submit" name="delete_reminder" class="delete-btn" title="Delete Reminder">
                    <i class="fa fa-trash"></i> Delete
                  </button>
                </form>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <p>No medicine reminders set yet.</p>
          <?php endif; ?>
        </div>
      </section>

      <section class="medicine-reminder-section">
        <h2>Allergies</h2>
        <form method="post" class="reminder-form">
          <input type="text" name="allergy_name" placeholder="Allergy Name" required />
          <input type="text" name="notes" placeholder="Optional Notes" />
          <button type="submit" name="add_allergy">Add Allergy</button>
        </form>

        <div class="reminder-list">
          <?php if ($allergies_result->num_rows > 0): ?>
            <?php while ($allergy = $allergies_result->fetch_assoc()): ?>
              <div class="reminder-item">
                <div>
                  <strong><?= htmlspecialchars($allergy['allergy_name']) ?></strong>
                  <?php if (!empty($allergy['notes'])): ?>
                    <div style="font-size: 0.9rem; color: #555;"><?= htmlspecialchars($allergy['notes']) ?></div>
                  <?php endif; ?>
                </div>
                <form method="post" style="margin:0;">
                  <input type="hidden" name="allergy_id" value="<?= $allergy['id'] ?>" />
                  <button type="submit" name="delete_allergy" class="delete-btn" title="Delete Allergy">
                    <i class="fa fa-trash"></i> Delete
                  </button>
                </form>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <p>No allergy records found.</p>
          <?php endif; ?>
        </div>
      </section>


    </main>
  </div>

  <script>
    document.querySelectorAll('.card').forEach(card => {
      card.addEventListener('click', () => {
        document.querySelectorAll('.card').forEach(c => c.classList.remove('selected'));
        card.classList.add('selected');
      });
    });
  </script>

</body>
</html>
