<?php
session_start();
include "../Connection.php";

if (!isset($_SESSION['hospital_id'])) {
    header("Location: ../login.php");
    exit();
}

$hospital_id = $_SESSION['hospital_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id = intval($_POST['id']);
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE sample_request_hospitals SET status = ? WHERE id = ? AND hospital_id = ?");
    $stmt->bind_param("sii", $status, $id, $hospital_id);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    exit();
}
$sql = "
SELECT 
  srh.id AS request_hospital_id,
  sr.test_name,
  sr.preferred_date,
  sr.address,
  p.first_name,
  p.last_name,
  p.phone_no,
  p.email,
  srh.status
FROM sample_request_hospitals srh
JOIN sample_requests sr ON srh.request_id = sr.id
JOIN patient_list p ON sr.patient_id = p.id
WHERE srh.hospital_id = ? 
  AND (
    srh.status = 'Accepted'
    OR srh.request_id NOT IN (
      SELECT request_id FROM sample_request_hospitals WHERE status = 'Accepted'
    )
  )
ORDER BY sr.preferred_date DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $hospital_id);
$stmt->execute();
$rows = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Sample Requests - Niramoy</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="sampleRq.css" />
  <style>
    
  </style>
</head>
<body>
<nav class="navbar bg-gradient text-white px-4 py-3 d-flex justify-content-between">
  <h2 class="mb-0"><i class="fa-solid fa-hospital"></i> নিরাময়</h2>
  <a href="../login.php" class="btn btn-light btn-sm">Logout</a>
</nav>

<div class="d-flex" >
  <aside class="sidebar d-flex flex-column p-3" style="width:220px; min-height:100vh;color:#2e3192;">
    <h5 class="mb-4 fw-bold text-white" style="color: #010102;">Dashboard</h5>
    <ul class="nav flex-column">
      <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
      <li class="nav-item"><a class="nav-link" href="doctorlist.php">Doctor List</a></li>
      <li class="nav-item"><a class="nav-link" href="patient.php">Patient List</a></li>
      <li class="nav-item"><a class="nav-link" href="appointmentDetails.php">Appointment Details</a></li>
      <li class="nav-item"><a class="nav-link" href="report.php">Report List</a></li>
      <li class="nav-item"><a class="nav-link active" href="sampleRq.php">Sample Request</a></li>
      <li class="nav-item"><a class="nav-link" href="blogWrite.php">Blog Write</a></li>
      <li class="nav-item"><a class="nav-link" href="addDoctor.php">Add Doctor</a></li>
      <li class="nav-item"><a class="nav-link" href="chatAdmin.php">Chat Admin</a></li>

    </ul>
  </aside>

  <main class="content flex-fill p-4">
    <h3 class="text-center mb-4">Sample Request Management</h3>

    <div class="table-responsive">
      <table class="table table-striped table-hover shadow-sm bg-white rounded">
        <thead class="table-light">
          <tr>
            <th>Patient Name</th>
            <th>Contact</th>
            <th>Email</th>
            <th>Test Type</th>
            <th>Preferred Date</th>
            <th>Address</th>
            <th>Status</th>
            <th>Chat</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($rows->num_rows > 0): ?>
            <?php while ($row = $rows->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
              <td><?= htmlspecialchars($row['phone_no']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= htmlspecialchars($row['test_name']) ?></td>
              <td><?= htmlspecialchars($row['preferred_date']) ?></td>
              <td><?= htmlspecialchars($row['address']) ?></td>
              <td>
                <select class="form-select form-select-sm status-select" data-id="<?= $row['request_hospital_id'] ?>">
                  <option value="Pending" <?= $row['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                  <option value="Accepted" <?= $row['status'] === 'Accepted' ? 'selected' : '' ?>>Accepted</option>
                  <option value="Rejected" <?= $row['status'] === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
              </td>
              <td>
                <a href="hospital_chat.php?request_hospital_id=<?= $row['request_hospital_id'] ?>" class="btn btn-primary btn-sm">
                  <i class="fa fa-comments"></i> Chat
                </a>
              </td>
            </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="8" class="text-center">No sample requests available.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $('.status-select').change(function() {
    const id = $(this).data('id');
    const status = $(this).val();
    $.post('<?= basename(__FILE__) ?>', { update_status: 1, id, status }, function(res) {
    }, 'json');
  });
</script>
</body>
</html>
