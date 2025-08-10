<?php
session_start();
include "../Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

$action = $_GET['action'] ?? '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($action === 'delete' && $id > 0) {
    $stmt = $conn->prepare("DELETE FROM sample_request_hospitals WHERE request_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt2 = $conn->prepare("DELETE FROM sample_requests WHERE id = ?");
    $stmt2->bind_param("i", $id);
    $stmt2->execute();

    header("Location: sampleRequests.php");
    exit();
}


$acceptedMap = [];
$acceptedSql = "SELECT request_id, hospital_id FROM sample_request_hospitals WHERE status = 'Accepted'";
$acceptedResult = mysqli_query($conn, $acceptedSql);
while ($row = mysqli_fetch_assoc($acceptedResult)) {
    $acceptedMap[$row['request_id']] = $row['hospital_id']; 
}

$sql = "
SELECT 
  sr.id AS request_id,
  sr.test_name,
  sr.preferred_date,
  sr.address,
  p.first_name,
  p.last_name,
  srh.status,
  srh.hospital_id,
  h.name AS hospital_name
FROM sample_requests sr
JOIN patient_list p ON sr.patient_id = p.id
JOIN sample_request_hospitals srh ON sr.id = srh.request_id
JOIN hospital_list h ON srh.hospital_id = h.id
ORDER BY sr.id DESC
";

$result = mysqli_query($conn, $sql);

$filteredRows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $reqId = $row['request_id'];

    if (isset($acceptedMap[$reqId])) {
        if ($row['hospital_id'] == $acceptedMap[$reqId]) {
            $filteredRows[] = $row;
        }
    } else {
        $filteredRows[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sample Requests - Admin Panel</title>
  <link rel="stylesheet" href="dashboard.css" />
  <link rel="stylesheet" href="sampleRequests.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .status {
      padding: 4px 8px;
      border-radius: 5px;
      font-size: 0.9rem;
      color: white;
    }
    .Pending { background-color: #ffc107; }
    .Accepted { background-color: #198754; }
    .Rejected { background-color: #dc3545; }
    .Completed { background-color: #0d6efd; }
    .view-btn, .delete-btn {
      padding: 5px 10px;
      font-size: 0.9rem;
      border-radius: 4px;
      text-decoration: none;
      color: white;
    }
    .view-btn { background-color: #0d6efd; }
    .delete-btn { background-color: #dc3545; }
  </style>
</head>
<body>
<div class="container">
  <aside class="sidebar">
    <div class="logo">Admin Panel</div>
    <nav>
      <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="manageHospital.php">Manage Hospitals</a></li>
        <li><a href="manageDoctor.php">Manage Doctors</a></li>
        <li><a href="managePatients.php">Manage Patients</a></li>
        <li><a href="manageBlogs.php">Manage Blogs</a></li>
        <li><a href="sampleList.php">Sample List</a></li>
        <li class="active"><a href="sampleRequests.php">Sample Requests</a></li>
        <li><a href="reports.php">Reports</a></li>
        <li><a href="chatHospital.php">Chat Hospitals</a></li>
      </ul>
    </nav>
    <button class="logout-btn"><a href="../logout.php">Logout</a></button>
  </aside>

  <main class="main-content">
    <h1>Manage Sample Requests</h1>

    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>Request ID</th>
            <th>Patient Name</th>
            <th>Test Type</th>
            <th>Preferred Date</th>
            <th>Hospital</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($filteredRows)): ?>
            <?php foreach ($filteredRows as $row): ?>
              <tr>
                <td><?= htmlspecialchars($row['request_id']) ?></td>
                <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                <td><?= htmlspecialchars($row['test_name']) ?></td>
                <td><?= htmlspecialchars($row['preferred_date']) ?></td>
                <td><?= htmlspecialchars($row['hospital_name']) ?></td>
                <td><span class="status <?= $row['status'] ?>"><?= $row['status'] ?></span></td>
                <td>
                  <a href="?action=delete&id=<?= $row['request_id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this request?');">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="7" class="text-center">No sample requests found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>
</body>
</html>
