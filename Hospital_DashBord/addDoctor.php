<?php
session_start();
include "../Connection.php"; 

if (!isset($_SESSION['hospital_id'])) {
    header("Location: ../login.php");
    exit();
}

$hospital_id = $_SESSION['hospital_id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $specialization = trim($_POST['specialization'] ?? '');
    $phone_no = trim($_POST['phone_no'] ?? '');
    $experience_years = intval($_POST['experience_years'] ?? 0);
    $availability = trim($_POST['availability'] ?? '');
    $fees = intval($_POST['fees'] ?? 0);
    $password = $_POST['password'] ?? '';

    if (!$name || !$phone_no || !$password) {
        $message = "Name, Phone Number, and Password are required.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM doctor_list WHERE phone_no = ? AND hospital_id = ?");
        $stmt->bind_param("si", $phone_no, $hospital_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Phone number already exists for this hospital.";
        } else {
            $stmt->close();

            $stmt = $conn->prepare("INSERT INTO doctor_list 
                (name, specialization, phone_no, hospital_id, experience_years, availability, fees, password) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param(
                "sssiisss",
                $name,
                $specialization,
                $phone_no,
                $hospital_id,
                $experience_years,
                $availability,
                $fees,
                $password
            );

            if ($stmt->execute()) {
                $message = "Doctor added successfully.";
            } else {
                $message = "Error adding doctor: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Add Doctor - Niramoy</title>
  <link rel="stylesheet" href="addDoctor.css" />
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
        <li class="nav-item"><a class="nav-link" href="patient.php">Patient List</a></li>
        <li class="nav-item"><a class="nav-link" href="appointmentDetails.php">Appointment Details</a></li>
        <li class="nav-item"><a class="nav-link" href="report.php">Report List</a></li>
        <li class="nav-item"><a class="nav-link" href="sampleRq.php">Sample Request</a></li>
        <li class="nav-item"><a class="nav-link" href="blogWrite.php">Blog Write</a></li>
        <li class="nav-item"><a class="nav-link active" href="addDoctor.php">Add Doctor</a></li>
        <li class="nav-item"><a class="nav-link" href="chatAdmin.php">Chat Admin</a></li>

      </ul>
    </aside>

    <main class="content flex-fill">
      <h3 class="text-center mb-4">Add Doctor</h3>

      <?php if ($message): ?>
        <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="mb-3">
          <label for="name" class="form-label">Doctor Name</label>
          <input type="text" id="name" name="name" class="form-control" placeholder="Enter full name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label for="specialization" class="form-label">Specialization</label>
          <input type="text" id="specialization" name="specialization" class="form-control" placeholder="e.g. Cardiology" value="<?= htmlspecialchars($_POST['specialization'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label for="phone_no" class="form-label">Phone Number</label>
          <input type="tel" id="phone_no" name="phone_no" class="form-control" placeholder="017xxxxxxxx" required value="<?= htmlspecialchars($_POST['phone_no'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label for="experience_years" class="form-label">Experience (Years)</label>
          <input type="number" id="experience_years" name="experience_years" class="form-control" placeholder="e.g. 5" value="<?= htmlspecialchars($_POST['experience_years'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label for="availability" class="form-label">Availability</label>
          <input type="text" id="availability" name="availability" class="form-control" placeholder="Mon–Thu, 10AM–2PM" value="<?= htmlspecialchars($_POST['availability'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label for="fees" class="form-label">Consultation Fees (BDT)</label>
          <input type="number" id="fees" name="fees" class="form-control" placeholder="Enter amount" value="<?= htmlspecialchars($_POST['fees'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" id="password" name="password" class="form-control" placeholder="Set a password" required>
        </div>

        <div class="text-center">
          <button type="submit" class="btn btn-primary">Add Doctor</button>
        </div>
      </form>
    </main>
  </div>
</body>
</html>

<?php
$conn->close();
?>
