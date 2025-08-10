<?php
session_start();
include "../../Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../login.php");
    exit();
}

$request_id = intval($_GET['id'] ?? 0);
if ($request_id <= 0) {
    die("Invalid request ID.");
}

$sql = "
SELECT sr.*, p.first_name, p.last_name, p.phone_no, p.email
FROM sample_requests sr
JOIN patient_list p ON sr.patient_id = p.id
WHERE sr.id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $request_id);
$stmt->execute();
$requestResult = $stmt->get_result();
if ($requestResult->num_rows === 0) {
    die("Sample request not found.");
}
$request = $requestResult->fetch_assoc();

$sqlHospitals = "
SELECT h.name AS hospital_name, srh.status
FROM sample_request_hospitals srh
JOIN hospital_list h ON srh.hospital_id = h.id
WHERE srh.request_id = ?
";
$stmt2 = $conn->prepare($sqlHospitals);
$stmt2->bind_param("i", $request_id);
$stmt2->execute();
$hospitalsResult = $stmt2->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>View Sample Request #<?= htmlspecialchars($request_id) ?></title>
  <link rel="stylesheet" href="dashboard.css" />
  <style>
    table { border-collapse: collapse; width: 100%; max-width: 600px; }
    th, td { padding: 8px 12px; border: 1px solid #ccc; }
    th { background: #f4f4f4; }
  </style>
</head>
<body>
<div class="container">
  <h2>Sample Request Details</h2>
  <p><strong>Request ID:</strong> <?= htmlspecialchars($request['id']) ?></p>
  <p><strong>Patient Name:</strong> <?= htmlspecialchars($request['first_name'] . ' ' . $request['last_name']) ?></p>
  <p><strong>Phone:</strong> <?= htmlspecialchars($request['phone_no']) ?></p>
  <p><strong>Email:</strong> <?= htmlspecialchars($request['email']) ?></p>
  <p><strong>Test Name:</strong> <?= htmlspecialchars($request['test_name']) ?></p>
  <p><strong>Preferred Date:</strong> <?= htmlspecialchars($request['preferred_date']) ?></p>
  <p><strong>Address:</strong> <?= htmlspecialchars($request['address']) ?></p>
  <h3>Hospital Statuses</h3>
  <table>
    <thead>
      <tr>
        <th>Hospital</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $hospitalsResult->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['hospital_name']) ?></td>
        <td><?= htmlspecialchars($row['status']) ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <p><a href="../sampleRequests.php">Back to Sample Requests</a></p>
</div>
</body>
</html>
