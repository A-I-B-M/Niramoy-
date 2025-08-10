<?php
session_start();
include "../Connection.php";

if (!isset($_SESSION['hospital_id'])) {
    header("Location: ../login.php");
    exit();
}
$hospital_id = $_SESSION['hospital_id'];

$message = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['upload_report'])) {
        $request_hospital_id = intval($_POST['request_hospital_id']);

        if (isset($_FILES['report_file']) && $_FILES['report_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['report_file']['tmp_name'];
            $fileName = $_FILES['report_file']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedfileExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];

            if (in_array($fileExtension, $allowedfileExtensions)) {
                $uploadFileDir = '../uploads/reports/';
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }

                $newFileName = 'report_' . $request_hospital_id . '_' . time() . '.' . $fileExtension;
                $dest_path = $uploadFileDir . $newFileName;
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $relativePath = 'uploads/reports/' . $newFileName;
                    $old = $conn->prepare("SELECT report_file FROM sample_request_hospitals WHERE id = ? AND hospital_id = ?");
                    $old->bind_param("ii", $request_hospital_id, $hospital_id);
                    $old->execute();
                    $oldResult = $old->get_result();
                    if ($row = $oldResult->fetch_assoc() && !empty($row['report_file'])) {
                        @unlink('../' . $row['report_file']);
                    }

                    $stmt = $conn->prepare("UPDATE sample_request_hospitals SET report_file = ? WHERE id = ? AND hospital_id = ?");
                    $stmt->bind_param("sii", $relativePath, $request_hospital_id, $hospital_id);
                    if ($stmt->execute()) {
                        $message = "Report uploaded successfully.";
                    } else {
                        $error = "Database update failed.";
                    }
                    $stmt->close();
                } else {
                    $error = "Error moving the uploaded file.";
                }
            } else {
                $error = "Allowed file types: " . implode(', ', $allowedfileExtensions);
            }
        } else {
            $error = "No file uploaded or upload error.";
        }
    } elseif (isset($_POST['delete_report'])) {
        $request_hospital_id = intval($_POST['request_hospital_id']);

        $getFile = $conn->prepare("SELECT report_file FROM sample_request_hospitals WHERE id = ? AND hospital_id = ?");
        $getFile->bind_param("ii", $request_hospital_id, $hospital_id);
        $getFile->execute();
        $res = $getFile->get_result()->fetch_assoc();
        $filePath = $res['report_file'];
        if ($filePath && file_exists('../' . $filePath)) {
            unlink('../' . $filePath);
        }

        $stmt = $conn->prepare("UPDATE sample_request_hospitals SET report_file = NULL WHERE id = ? AND hospital_id = ?");
        $stmt->bind_param("ii", $request_hospital_id, $hospital_id);
        if ($stmt->execute()) {
            $message = "Report deleted successfully.";
        } else {
            $error = "Failed to delete report.";
        }
    }
}

$sql = "SELECT srh.id, sr.test_name, sr.preferred_date, p.first_name, p.last_name, srh.report_file
        FROM sample_request_hospitals srh
        JOIN sample_requests sr ON srh.request_id = sr.id
        JOIN patient_list p ON sr.patient_id = p.id
        WHERE srh.hospital_id = ? AND srh.status = 'Accepted'
        ORDER BY sr.preferred_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $hospital_id);
$stmt->execute();
$requests = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Patient Report - Niramoy</title>
  <link rel="stylesheet" href="report.css" />
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
        <li class="nav-item"><a class="nav-link" href="doctorlist.php">Doctor List</a></li>
        <li class="nav-item"><a class="nav-link" href="patient.php">Patient List</a></li>
        <li class="nav-item"><a class="nav-link" href="appointmentDetails.php">Appointment Details</a></li>
        <li class="nav-item"><a class="nav-link active" href="report.php">Report List</a></li>
        <li class="nav-item"><a class="nav-link" href="sampleRq.php">Sample Request</a></li>
        <li class="nav-item"><a class="nav-link" href="blogWrite.php">Blog Write</a></li>
        <li class="nav-item"><a class="nav-link" href="addDoctor.php">Add Doctor</a></li>
        <li class="nav-item"><a class="nav-link" href="chatAdmin.php">Chat Admin</a></li>
      </ul>
    </aside>

    <main class="content flex-fill">
      <h2 class="text-center mb-4">Patient Report List</h2>

      <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
      <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <table class="table table-striped table-bordered">
        <thead class="table-light">
          <tr>
            <th>Patient Name</th>
            <th>Test Type</th>
            <th>Preferred Date</th>
            <th>Report</th>
            <th>Upload/Replace</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $requests->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
            <td><?= htmlspecialchars($row['test_name']) ?></td>
            <td><?= htmlspecialchars($row['preferred_date']) ?></td>
            
            
            <td style="text-align: center;">
              <?php if ($row['report_file']): ?>
                <a href="../<?= $row['report_file'] ?>" target="_blank" class="btn btn-success btn-sm">View</a>

                <form method="POST" style="display:inline; padding :0px ;gap :10px;">
                  <input type="hidden" name="request_hospital_id" value="<?= $row['id'] ?>">
                  <button type="submit" name="delete_report" class="btn btn-danger btn-sm" onclick="return confirm('Delete this report?')">
                    <i class="fa fa-trash"></i>
                  </button>
                </form>

              <?php else: ?>
                <span class="text-muted">Not uploaded</span>
              <?php endif; ?>
            </td>
            <td>
              <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="request_hospital_id" value="<?= $row['id'] ?>">
                <input type="file" name="report_file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
                <button type="submit" name="upload_report" class="btn btn-primary btn-sm mt-1">Upload</button>
              </form>
            </td>   
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </main>
  </div>
</body>
</html>
