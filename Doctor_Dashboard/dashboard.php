<?php
session_start();
include "../Connection.php";

if (!isset($_SESSION['doctor_id'])) {
    header("Location: ../login.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];

$sql = "SELECT name FROM doctor_list WHERE id = $doctor_id LIMIT 1";
$result = mysqli_query($conn, $sql);
$doctor_name = ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result)['name'] : "Doctor";

$appointments = [];
$appointment_query = "SELECT a.appointment_date, p.first_name, p.last_name 
                      FROM appointment_patient a
                      JOIN patient_list p ON a.patient_id = p.id
                      WHERE a.doctor_id = $doctor_id";

$appointment_result = mysqli_query($conn, $appointment_query);
while ($row = mysqli_fetch_assoc($appointment_result)) {
    $appointments[] = [
        'title' => $row['first_name'] . ' ' . $row['last_name'],
        'start' => $row['appointment_date'],
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Doctor Dashboard - নিরাময়</title>
  <link rel="stylesheet" href="dashboard.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet" />
  <style>
    #calendar {
      background: #fff;
      padding: 15px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      margin-top: 20px;
    }
    .fc .fc-day-today {
      background-color: #ffeeba !important;
    }
    h2 {
      margin-top: 40px;
      margin-bottom: 10px;
    }
    .sidebar {
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  width: 220px;
  background: #00bfff;
  color: white;
  padding: 30px 20px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  z-index: 1000;
}
.main-content {
  margin-left: 220px;
  padding: 30px;
  width: calc(100% - 220px);
  min-height: 100vh;
}

.main-content h1 {
  margin-bottom: 30px;
  color: #333;
}
  </style>
</head>
<body>

<div class="container">

  <aside class="sidebar">
    <div class="logo">🩺 নিরাময়</div>
    <nav>
      <ul>
        <li class="active"><a href="dashboard.php">Dashboard</a></li>
        <li><a href="appointmentDetails.php">Appointment Details</a></li>
        <li><a href="precriptionList.php">Prescription List</a></li>
      </ul>
    </nav>
    <button class="logout-btn"><a href="../logout.php">Logout</a></button>
  </aside>

  <main class="main-content">
    <h1>Welcome Doctor : <?= htmlspecialchars($doctor_name) ?></h1>
    <div class="card-grid">

      <div class="card gray">
        <i class="fa-solid fa-calendar-check icon"></i>
        <h3>Appointment Details</h3>
        <a href="appointmentDetails.php">View</a>
      </div>

      <div class="card gray">
        <i class="fa-solid fa-file-medical icon"></i>
        <h3>Prescription List</h3>
        <a href="precriptionList.php">View</a>
      </div>

    </div>
    <h2>📅 Appointment Calendar</h2>
    <div id='calendar'></div>

  </main>
</div>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      height: 600,
      events: <?= json_encode($appointments) ?>,
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: ''
      },
      eventColor: '#007bff',
      eventTextColor: 'white'
    });

    calendar.render();
  });
</script>

</body>
</html>
