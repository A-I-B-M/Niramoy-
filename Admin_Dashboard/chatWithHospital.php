<?php
session_start();
include "../Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];
$hospital_id = isset($_GET['hospital_id']) ? intval($_GET['hospital_id']) : 0;
if (!$hospital_id) {
    die("Invalid hospital selected.");
}

$stmt = $conn->prepare("SELECT id, name, hospital_city FROM hospital_list WHERE id = ?");
$stmt->bind_param("i", $hospital_id);
$stmt->execute();
$hospital = $stmt->get_result()->fetch_assoc();
if (!$hospital) {
    die("Hospital not found.");
}

$sql_latest = "SELECT id FROM sample_request_hospitals WHERE hospital_id = ? ORDER BY id DESC LIMIT 1";
$stmt_latest = $conn->prepare($sql_latest);
$stmt_latest->bind_param("i", $hospital_id);
$stmt_latest->execute();
$res_latest = $stmt_latest->get_result();
if ($res_latest->num_rows > 0) {
    $row_latest = $res_latest->fetch_assoc();
    $request_hospital_id = $row_latest['id'];
} else {
    die("No chat context available with this hospital.");
}
$stmt_latest->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty(trim($_POST['message']))) {
    $msg = trim($_POST['message']);
    $stmt = $conn->prepare("INSERT INTO chat_messages (request_hospital_id, admin_id, sender, message, sent_at) VALUES (?, ?, 'admin', ?, NOW())");
    $stmt->bind_param("iis", $request_hospital_id, $admin_id, $msg);
    $stmt->execute();
    $stmt->close();

    header("Location: chatWithHospital.php?hospital_id=$hospital_id");
    exit();
}

$sql = "
SELECT sender, message, sent_at 
FROM chat_messages 
WHERE request_hospital_id = ? AND admin_id = ? AND sender IN ('admin', 'hospital') 
ORDER BY sent_at ASC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $request_hospital_id, $admin_id);
$stmt->execute();
$messages = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Chat with <?= htmlspecialchars($hospital['name']) ?> - Admin - নিরাময়</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <style>
    body, html { height: 100%; margin: 0; background: #f9f9f9; }
    .chat-container { max-width: 700px; margin: 30px auto; background: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1);}
    .chat-box { max-height: 400px; overflow-y: auto; padding: 10px; border: 1px solid #ddd; border-radius: 6px; background: #fafafa; margin-bottom: 15px;}
    .message { padding: 8px 12px; border-radius: 20px; margin-bottom: 10px; max-width: 75%; }
    .message.admin { background: #0d6efd; color: white; margin-left: auto; border-bottom-right-radius: 0; }
    .message.hospital { background: #e9ecef; color: #333; margin-right: auto; border-bottom-left-radius: 0; }
    .timestamp { font-size: 0.75rem; color: #666; margin-top: 4px; }
    form textarea { resize: none; height: 60px; padding: 10px; border-radius: 8px; border: 1px solid #ddd; }
    form button { margin-left: 10px; }
    a.back-link { margin-bottom: 20px; display: inline-block; color: #0d6efd; text-decoration: none; }
    a.back-link:hover { text-decoration: underline; }
  </style>
</head>
<body>

<div class="chat-container">
  <a href="chatHospital.php" class="back-link"><i class="fa fa-arrow-left"></i> Back to Hospital List</a>
  <h4>Chat with <?= htmlspecialchars($hospital['name']) ?> (<?= htmlspecialchars($hospital['hospital_city']) ?>)</h4>

  <div class="chat-box" id="chat-box">
    <?php if ($messages->num_rows > 0): ?>
      <?php while ($msg = $messages->fetch_assoc()): ?>
        <div class="message <?= $msg['sender'] === 'admin' ? 'admin' : 'hospital' ?>">
          <?= nl2br(htmlspecialchars($msg['message'])) ?>
          <div class="timestamp"><?= date("d M Y, H:i", strtotime($msg['sent_at'])) ?></div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No messages yet. Start the conversation!</p>
    <?php endif; ?>
  </div>

  <form method="post" class="d-flex">
    <textarea name="message" placeholder="Type your message..." required></textarea>
    <button type="submit" class="btn btn-primary">Send</button>
  </form>
</div>

</body>
</html>
