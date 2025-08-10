<?php
session_start();

include "../Connection.php";  

if (!isset($_SESSION['hospital_id'])) {
    header("Location: ../login.php");
    exit();
}

$hospital_id = $_SESSION['hospital_id'];

$sql = "
SELECT 
    p.id AS patient_id,
    CONCAT(p.first_name, ' ', p.last_name) AS patient_name,
    p.email,
    p.phone_no,
    p.gender,
    d.name AS doctor_name,
    a.appointment_date
FROM appointment_patient a
INNER JOIN patient_list p ON a.patient_id = p.id
INNER JOIN doctor_list d ON a.doctor_id = d.id
WHERE a.hospital_id = ?
ORDER BY a.appointment_date DESC
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

$stmt->bind_param("i", $hospital_id);

if (!$stmt->execute()) {
    die("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
}

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Patient List - Niramoy</title>
  <link rel="stylesheet" href="patient.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
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
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="doctorlist.php">Doctor List</a></li>
        <li class="nav-item"><a class="nav-link active" href="patient.php">Patient List</a></li>
        <li class="nav-item"><a class="nav-link" href="appointmentDetails.php">Appointment Details</a></li>
        <li class="nav-item"><a class="nav-link" href="report.php">Report List</a></li>
        <li class="nav-item"><a class="nav-link" href="sampleRq.php">Sample Request</a></li>
        <li class="nav-item"><a class="nav-link" href="blogWrite.php">Blog Write</a></li>
        <li class="nav-item"><a class="nav-link" href="addDoctor.php">Add Doctor</a></li>
        <li class="nav-item"><a class="nav-link" href="chatAdmin.php">Chat Admin</a></li>

      </ul>
    </aside>

    <main class="content flex-fill">
      <h2 class="text-center mb-4">List of Patients</h2>
      <div class="table-responsive">
        <table class="table table-bordered table-striped shadow-sm">
          <thead class="table-primary">
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone No</th>
              <th>Gender</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $count = 1;
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $count++ . "</td>";
                    echo "<td>" . htmlspecialchars($row['patient_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['phone_no']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                }
            } else {
                echo '<tr><td colspan="7" class="text-center">No patients found for this hospital.</td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
