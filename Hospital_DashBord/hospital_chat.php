<?php
session_start();
include "../Connection.php";

if (!isset($_SESSION['hospital_id'])) {
    header("Location: ../login.php");
    exit();
}

$hospital_id = $_SESSION['hospital_id'];

if (!isset($_GET['request_hospital_id'])) {
    echo "Invalid request.";
    exit();
}

$request_hospital_id = intval($_GET['request_hospital_id']);
if ($request_hospital_id <= 0) {
    echo "Invalid request.";
    exit();
}

$stmt = $conn->prepare("SELECT 1 FROM sample_request_hospitals WHERE id = ? AND hospital_id = ?");
$stmt->bind_param("ii", $request_hospital_id, $hospital_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Unauthorized access.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    if ($message !== '') {
        $stmt = $conn->prepare("INSERT INTO chat_messages (request_hospital_id, sender, message) VALUES (?, 'hospital', ?)");
        $stmt->bind_param("is", $request_hospital_id, $message);
        $stmt->execute();
    }
    header("Location: hospital_chat.php?request_hospital_id=$request_hospital_id");
    exit();
}

$stmt = $conn->prepare("SELECT sender, message, sent_at FROM chat_messages WHERE request_hospital_id = ? ORDER BY sent_at ASC");
$stmt->bind_param("i", $request_hospital_id);
$stmt->execute();
$messages = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Chat - Niramoy Hospital</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: #f4f9ff;
      padding: 20px;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .chat-box {
      max-width: 700px;
      margin: auto;
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .chat-message {
      margin-bottom: 12px;
      padding: 10px;
      border-radius: 10px;
      max-width: 70%;
      clear: both;
      font-size: 0.9rem;
    }
    .chat-message.patient {
      background: #f8f9fa;
      float: left;
      text-align: left;
    }
    .chat-message.hospital {
      background: #d1ecf1;
      float: right;
      text-align: right;
    }
    .timestamp {
      font-size: 0.75rem;
      color: #666;
      margin-top: 4px;
      clear: both;
    }
  </style>
</head>
<body>
  <div class="chat-box">
    <a href="sampleRq.php" class="btn btn-secondary mb-3">&larr; Back to Sample Requests</a>
    <h2 class="mb-4 text-primary">Chat with Patient</h2>

    <?php while ($row = $messages->fetch_assoc()): ?>
      <div class="chat-message <?= $row['sender'] ?>">
        <strong><?= ucfirst($row['sender']) ?>:</strong> <?= htmlspecialchars($row['message']) ?>
        <div class="timestamp"><?= date("d M Y h:i A", strtotime($row['sent_at'])) ?></div>
      </div>
    <?php endwhile; ?>

    <form method="POST" class="mt-4">
      <textarea name="message" class="form-control" rows="3" placeholder="Type your message here..." required></textarea>
      <button type="submit" class="btn btn-primary mt-2">Send</button>
    </form>
  </div>
</body>
</html>
