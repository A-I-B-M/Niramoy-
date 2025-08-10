<?php
session_start();

if (!isset($_SESSION['hospital_id']) || !isset($_SESSION['hospital_name'])) {
    header("Location: ../login.php");
    exit();
}

$hospitalName = $_SESSION['hospital_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Niramoy Dashboard</title>
  <link rel="stylesheet" href="dashboard.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
</head>
<body>
  <nav class="navbar bg-gradient text-white px-4 py-3 d-flex justify-content-between">
    <h2 id="h2tag" class="mb-0"><i class="fa-solid fa-hospital"></i> নিরাময়</h2>
    <a href="../logout.php" class="btn btn-light btn-sm">Logout</a>
  </nav>

  <div class="d-flex">
    <aside class="sidebar d-flex flex-column p-3">
      <h5 class="mb-4 fw-bold">Dashboard</h5>
      <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="doctorlist.php">Doctor List</a></li>
        <li class="nav-item"><a class="nav-link" href="patient.php">Patient List</a></li>
        <li class="nav-item"><a class="nav-link" href="appointmentDetails.php">Appointment Details</a></li>
        <li class="nav-item"><a class="nav-link" href="report.php">Report List</a></li>
        <li class="nav-item"><a class="nav-link" href="sampleRq.php">Sample Request</a></li>
        <li class="nav-item"><a class="nav-link" href="blogWrite.php">Blog Write</a></li>
        <li class="nav-item"><a class="nav-link" href="addDoctor.php">Add Doctor</a></li>
        <li class="nav-item"><a class="nav-link" href="chatAdmin.php">Chat Admin</a></li>
      </ul>
    </aside>

    <main class="content flex-fill">
      <h2 class="text-center mb-4">Welcome to: <?= htmlspecialchars($hospitalName) ?></h2>
      <div class="row g-4 text-center">

        <div class="col-md-4">
          <div class="card shadow-sm">
            <div class="card-body">
              <i class="fa-solid fa-user-doctor fa-2x icon"></i>
              <h5 class="card-title">Doctor List</h5>
              <a href="doctorlist.php" class="btn btn-primary w-100 mt-2">
                <i class="fa-solid fa-user-doctor me-2"></i> View Doctors
              </a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card shadow-sm">
            <div class="card-body">
              <i class="fa-solid fa-users fa-2x icon"></i>
              <h5 class="card-title">Patient List</h5>
              <a href="patient.php" class="btn btn-info w-100 mt-2">
                <i class="fa-solid fa-users me-2"></i> View Patients
              </a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card shadow-sm">
            <div class="card-body">
              <i class="fa-solid fa-calendar-check fa-2x icon"></i>
              <h5 class="card-title">Appointment Details</h5>
              <a href="appointmentDetails.php" class="btn btn-warning w-100 mt-2 text-white">
                <i class="fa-solid fa-calendar-check me-2"></i> View Appointments
              </a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card shadow-sm">
            <div class="card-body">
              <i class="fa-solid fa-file-medical fa-2x icon"></i>
              <h5 class="card-title">Report List</h5>
              <a href="report.php" class="btn btn-secondary w-100 mt-2">
                <i class="fa-solid fa-file-medical me-2"></i> View Reports
              </a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card shadow-sm">
            <div class="card-body">
              <i class="fa-solid fa-vials fa-2x icon"></i>
              <h5 class="card-title">Sample Request</h5>
              <a href="sampleRq.php" class="btn btn-secondary w-100 mt-2">
                <i class="fa-solid fa-vial me-2"></i> View Requests
              </a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card shadow-sm">
            <div class="card-body">
              <i class="fa-solid fa-user-plus fa-2x icon text-success"></i>
              <h5 class="card-title">Add Doctor</h5>
              <a href="addDoctor.php" class="btn btn-success w-100 mt-2">
                <i class="fa-solid fa-user-plus me-2"></i> Add Doctor
              </a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card shadow-sm">
            <div class="card-body">
              <i class="fa-solid fa-user-minus fa-2x icon text-danger"></i>
              <h5 class="card-title">Delete Doctor</h5>
              <a href="removeDoctor.php" class="btn btn-danger w-100 mt-2">
                <i class="fa-solid fa-user-minus me-2"></i> Delete Doctor
              </a>
            </div>
          </div>
        </div>

      </div>
    </main>
  </div>
</body>
</html>
