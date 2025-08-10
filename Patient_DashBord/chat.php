<?php
session_start();
include "../Connection.php";

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../patientLogin.php");
    exit();
}

if (!isset($_GET['request_hospital_id'])) {
    echo "Invalid request.";
    exit();
}

$patient_id = $_SESSION['patient_id'];
$request_hospital_id = intval($_GET['request_hospital_id']);

if ($request_hospital_id <= 0) {
    echo "Invalid request.";
    exit();
}

$check = $conn->prepare("
    SELECT sr.patient_id 
    FROM sample_requests sr 
    JOIN sample_request_hospitals srh ON sr.id = srh.request_id 
    WHERE srh.id = ?
");
$check->bind_param("i", $request_hospital_id);
$check->execute();
$check_result = $check->get_result();

if ($check_result->num_rows === 0 || $check_result->fetch_assoc()['patient_id'] != $patient_id) {
    echo "Unauthorized access.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO chat_messages (request_hospital_id, sender, message) VALUES (?, 'patient', ?)");
        $stmt->bind_param("is", $request_hospital_id, $message);
        $stmt->execute();
    }
    header("Location: chat.php?request_hospital_id=$request_hospital_id");
    exit();
}

$messages_stmt = $conn->prepare("SELECT sender, message, sent_at FROM chat_messages WHERE request_hospital_id = ? ORDER BY sent_at ASC");
$messages_stmt->bind_param("i", $request_hospital_id);
$messages_stmt->execute();
$messages = $messages_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Chat with Hospital - Niramoy</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f9ff;
      padding: 20px;
    }

    .chat-box {
      max-width: 750px;
      margin: auto;
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #00bfff;
    }

    .chat-message {
      margin-bottom: 12px;
      padding: 10px;
      border-radius: 10px;
      max-width: 70%;
    }

    .chat-message.patient {
      background: #d1ecf1;
      text-align: right;
      margin-left: auto;
    }

    .chat-message.hospital {
      background: #f8f9fa;
      text-align: left;
      margin-right: auto;
    }

    .chat-message .timestamp {
      font-size: 0.75rem;
      color: #777;
      margin-top: 4px;
    }

    form {
      display: flex;
      gap: 10px;
      margin-top: 20px;
    }

    textarea {
      flex: 1;
      padding: 10px;
      font-size: 1rem;
      border-radius: 6px;
      border: 1px solid #ccc;
      resize: none;
    }

    button {
      background: #00bfff;
      border: none;
      color: white;
      padding: 10px 20px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
    }

    .back-link {
      display: inline-block;
      margin-bottom: 15px;
      color: #333;
      text-decoration: none;
      font-weight: 500;
    }

    .back-link i {
      margin-right: 5px;
    }
  </style>
</head>
<body>
  <div class="chat-box">
    <a class="back-link" href="reportlist.php"><i class="fa fa-arrow-left"></i> Back to Reports</a>
    <h2>Chat with Hospital</h2>

    <?php while ($row = $messages->fetch_assoc()): ?>
      <div class="chat-message <?= $row['sender'] ?>">
        <div><strong><?= ucfirst($row['sender']) ?>:</strong> <?= htmlspecialchars($row['message']) ?></div>
        <div class="timestamp"><?= date("d M Y h:i A", strtotime($row['sent_at'])) ?></div>
      </div>
    <?php endwhile; ?>

    <form method="POST">
      <textarea name="message" rows="2" placeholder="Type your message..." required></textarea>
      <button type="submit">Send</button>
    </form>
  </div>
</body>
</html>