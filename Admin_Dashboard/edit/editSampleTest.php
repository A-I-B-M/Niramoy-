<?php
session_start();
include "../../Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: ../sampleList.php");
    exit();
}

$message = '';

$stmt = $conn->prepare("SELECT * FROM sample_tests WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    $stmt->close();
    header("Location: sampleList.php");
    exit();
}
$test = $res->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $test_name = trim($_POST['test_name']);
    $image_url = trim($_POST['image_url']);

    if ($test_name === '' || $image_url === '') {
        $message = "Please fill in all fields.";
    } else {
        $check_stmt = $conn->prepare("SELECT id FROM sample_tests WHERE test_name = ? AND id != ?");
        $check_stmt->bind_param("si", $test_name, $id);
        $check_stmt->execute();
        $check_stmt->store_result();
        if ($check_stmt->num_rows > 0) {
            $message = "Sample test with this name already exists.";
        } else {
            $update_stmt = $conn->prepare("UPDATE sample_tests SET test_name = ?, image_url = ? WHERE id = ?");
            $update_stmt->bind_param("ssi", $test_name, $image_url, $id);
            if ($update_stmt->execute()) {
                $message = "Sample test updated successfully.";
                $test['test_name'] = $test_name;
                $test['image_url'] = $image_url;
            } else {
                $message = "Error updating: " . $conn->error;
            }
            $update_stmt->close();
        }
        $check_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Sample Test</title>
  <link rel="stylesheet" href="dashboard.css" />
  <style>
    main {
      max-width: 600px;
      margin: 40px auto;
      background: #fff;
      padding: 20px 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    h1 {
      margin-bottom: 20px;
      color: #333;
    }
    label {
      display: block;
      margin-top: 15px;
      font-weight: 600;
      color: #444;
    }
    input[type="text"], input[type="url"] {
      width: 100%;
      padding: 10px 12px;
      margin-top: 6px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 1rem;
    }
    button {
      margin-top: 25px;
      background-color: #00bfff;
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      font-size: 1rem;
    }
    button:hover {
      background-color: #009acd;
    }
    .message {
      margin-top: 20px;
      font-weight: bold;
      color: green;
    }
    .error {
      color: red;
    }
    .back-link {
      display: inline-block;
      margin-top: 30px;
      color: #00bfff;
      text-decoration: none;
      font-weight: 600;
    }
    .back-link:hover {
      text-decoration: underline;
    }
    .img-preview {
      margin-top: 15px;
      max-width: 150px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
  </style>
</head>
<body>

<main>
  <h1>Edit Sample Test</h1>

  <?php if ($message): ?>
    <div class="message <?php echo (strpos($message, 'Error') !== false || strpos($message, 'fill') !== false) ? 'error' : ''; ?>">
      <?= htmlspecialchars($message) ?>
    </div>
  <?php endif; ?>

  <form method="POST" action="">
    <label for="test_name">Test Name</label>
    <input type="text" id="test_name" name="test_name" required value="<?= htmlspecialchars($test['test_name']) ?>" />

    <label for="image_url">Image URL</label>
    <input type="url" id="image_url" name="image_url" required value="<?= htmlspecialchars($test['image_url']) ?>" />
    
    <?php if ($test['image_url']): ?>
      <img src="<?= htmlspecialchars($test['image_url']) ?>" alt="Sample Test Image" class="img-preview" />
    <?php endif; ?>

    <button type="submit">Update Test</button>
  </form>

  <a href="../sampleList.php" class="back-link">&larr; Back to Sample Tests</a>
</main>

</body>
</html>
