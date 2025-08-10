<?php
session_start();
include "../Connection.php";

if (!isset($_SESSION['hospital_id']) || !isset($_SESSION['hospital_name'])) {
    header("Location: ../login.php");
    exit();
}

$hospital_id = $_SESSION['hospital_id'];
$hospitalName = $_SESSION['hospital_name'];
$admin_id = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty(trim($_POST['message']))) {
    $msg = trim($_POST['message']);
    $sql_latest = "SELECT id FROM sample_request_hospitals WHERE hospital_id = ? ORDER BY id DESC LIMIT 1";
    $stmt = $conn->prepare($sql_latest);
    if ($stmt === false) {
        die("Prepare failed (sql_latest): " . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("i", $hospital_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $request_hospital_id = $row['id'];
    } else {
        die("No chat context available to send message.");
    }
    $stmt->close();

    $insert_sql = "INSERT INTO chat_messages (request_hospital_id, admin_id, sender, message, sent_at) VALUES (?, ?, 'hospital', ?, NOW())";
    $stmt = $conn->prepare($insert_sql);
    if ($stmt === false) {
        die("Prepare failed (insert_sql): " . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("iis", $request_hospital_id, $admin_id, $msg);
    $stmt->execute();
    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$sql = "
SELECT cm.sender, cm.message, cm.sent_at
FROM chat_messages cm
JOIN sample_request_hospitals srh ON cm.request_hospital_id = srh.id
WHERE srh.hospital_id = ?
AND cm.admin_id = ?
AND cm.sender IN ('hospital', 'admin')
ORDER BY cm.sent_at ASC
";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed (fetch chat): " . htmlspecialchars($conn->error));
}
$stmt->bind_param("ii", $hospital_id, $admin_id);
$stmt->execute();
$messages = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Chat with Admin - Hospital - নিরাময়</title>
  <link rel="stylesheet" href="dashboard.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <style>
    main.content {
      margin-left: 220px;
      padding: 30px;
      min-height: 100vh;
      background: #f5f9ff;
    }
    .chat-container {
      max-width: 700px;
      margin: 0 auto;
      background: #fff;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .chat-box {
      max-height: 400px;
      overflow-y: auto;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 6px;
      background: #fafafa;
      margin-bottom: 15px;
    }
    .message {
      padding: 8px 12px;
      border-radius: 20px;
      margin-bottom: 10px;
      max-width: 75%;
    }
    .message.hospital {
      background: #0d6efd;
      color: white;
      margin-left: auto;
      border-bottom-right-radius: 0;
    }
    .message.admin {
      background: #e9ecef;
      color: #333;
      margin-right: auto;
      border-bottom-left-radius: 0;
    }
    .timestamp {
      font-size: 0.75rem;
      color: #666;
      margin-top: 4px;
    }
    form textarea {
      resize: none;
      height: 60px;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ddd;
      flex-grow: 1;
    }
    form button {
      margin-left: 10px;
    }
    form.d-flex {
      display: flex;
    }
  </style>
</head>
<body>
  <nav class="navbar bg-gradient text-white px-4 py-3 d-flex justify-content-between">
    <h2 id="h2tag" class="mb-0"><i class="fa-solid fa-hospital"></i> নিরাময়</h2>
    <a href="../logout.php" class="btn btn-light btn-sm">Logout</a>
  </nav>

  <div class="d-flex">
    <aside class="sidebar d-flex flex-column p-3" style="position: fixed; top: 0; left: 0; height: 100vh; width: 220px; background: #00bfff; color: white;">
      <h5 class="mb-4 fw-bold">Dashboard</h5>
      <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="doctorlist.php">Doctor List</a></li>
        <li class="nav-item"><a class="nav-link" href="patient.php">Patient List</a></li>
        <li class="nav-item"><a class="nav-link" href="appointmentDetails.php">Appointment Details</a></li>
        <li class="nav-item"><a class="nav-link" href="report.php">Report List</a></li>
        <li class="nav-item"><a class="nav-link" href="sampleRq.php">Sample Request</a></li>
        <li class="nav-item"><a class="nav-link" href="blogWrite.php">Blog Write</a></li>
        <li class="nav-item"><a class="nav-link" href="addDoctor.php">Add Doctor</a></li>
        <li class="nav-item"><a class="nav-link active" href="chatAdmin.php">Chat Admin</a></li>
      </ul>
    </aside>

    <main class="content flex-fill">
      <h2 class="text-center mb-4">Welcome to: <?= htmlspecialchars($hospitalName) ?></h2>

      <div class="chat-container">
        <h4>Chat with Admin</h4>

        <div class="chat-box" id="chat-box">
          <?php if ($messages->num_rows > 0): ?>
            <?php while ($msg = $messages->fetch_assoc()): ?>
              <div class="message <?= $msg['sender'] === 'hospital' ? 'hospital' : 'admin' ?>">
                <?= nl2br(htmlspecialchars($msg['message'])) ?>
                <div class="timestamp"><?= date("d M Y, H:i", strtotime($msg['sent_at'])) ?></div>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <p>No messages yet. Start the conversation!</p>
          <?php endif; ?>
        </div>

        <form method="post" class="d-flex" autocomplete="off">
          <textarea name="message" placeholder="Type your message..." required></textarea>
          <button type="submit" class="btn btn-primary">Send</button>
        </form>
      </div>
    </main>
  </div>
</body>
</html>
