<?php
session_start();
include "../Connection.php";

if (!isset($_SESSION['hospital_id'])) {
    header("Location: ../login.php");
    exit();
}

$hospitalId = $_SESSION['hospital_id'];
$hospitalName = $_SESSION['hospital_name'] ?? "Your Hospital";

$query = "SELECT id, name, specialization, availability, phone_no, experience_years, fees FROM doctor_list WHERE hospital_id = $hospitalId";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Doctor List - Niramoy</title>
  <link rel="stylesheet" href="doctorlist.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
</head>
<body>
  <nav class="navbar bg-gradient text-white px-4 py-3 d-flex justify-content-between">
    <h2 id="h2tag" class="mb-0"><i class="fa-solid fa-hospital"></i> নিরাময়</h2>
    <a href="../login.php" class="btn btn-light btn-sm">Logout</a>
  </nav>

  <div class="d-flex">
    <aside class="sidebar d-flex flex-column p-3">
      <h5 class="mb-4 fw-bold">Dashboard</h5>
      <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link active" href="doctorlist.php">Doctor List</a></li>
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
      <h2 class="text-center mb-4">Doctors in <?php echo htmlspecialchars($hospitalName); ?></h2>
      <div class="table-responsive px-3">
        <table class="table table-bordered table-striped shadow-sm">
          <thead class="table-primary text-center">
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Specialization</th>
              <th>Availability</th>
              <th>Experience (Years)</th>
              <th>Fees (৳)</th>
              <th>Phone</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody class="text-center">
            <?php
            if (mysqli_num_rows($result) > 0) {
                $count = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                          <td>{$count}</td>
                          <td>" . htmlspecialchars($row['name']) . "</td>
                          <td>" . htmlspecialchars($row['specialization']) . "</td>
                          <td>" . htmlspecialchars($row['availability']) . "</td>
                          <td>" . htmlspecialchars($row['experience_years']) . "</td>
                          <td>" . htmlspecialchars($row['fees']) . "</td>
                          <td>" . htmlspecialchars($row['phone_no']) . "</td>
                          <td>
                              <a href='edit/editDoctor.php?id=" . $row['id'] . "' class='btn btn-sm btn-warning me-1'>Edit</a>
                              <a href='delete/deleteDoctor.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure you want to delete this doctor?');\">Delete</a>
                          </td>
                        </tr>";
                    $count++;
                }
            } else {
                echo "<tr><td colspan='8' class='text-center text-muted'>No doctors found for this hospital.</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</body>
</html>
