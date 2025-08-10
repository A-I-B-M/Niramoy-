<?php
session_start();
include "../Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_sample'])) {
    $test_name = trim($_POST['test_name']);
    $image_url = trim($_POST['image_url']);

    if ($test_name === '' || $image_url === '') {
        $message = "Please fill in all fields.";
    } else {
        $check_stmt = $conn->prepare("SELECT id FROM sample_tests WHERE test_name = ?");
        $check_stmt->bind_param("s", $test_name);
        $check_stmt->execute();
        $check_stmt->store_result();
        if ($check_stmt->num_rows > 0) {
            $message = "Sample test with this name already exists.";
        } else {
            $admin_id = $_SESSION['admin_id'];
            $insert_stmt = $conn->prepare("INSERT INTO sample_tests (test_name, image_url, admin_id) VALUES (?, ?, ?)");
            $insert_stmt->bind_param("ssi", $test_name, $image_url, $admin_id);
            if ($insert_stmt->execute()) {
                $message = "Sample test added successfully!";
            } else {
                $message = "Error adding sample test: " . $conn->error;
            }
            $insert_stmt->close();
        }
        $check_stmt->close();
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $delete_id = intval($_GET['id']);
    $del_stmt = $conn->prepare("DELETE FROM sample_tests WHERE id = ?");
    $del_stmt->bind_param("i", $delete_id);
    $del_stmt->execute();
    $del_stmt->close();

    header("Location: sampleList.php");
    exit();
}

$sql = "SELECT * FROM sample_tests ORDER BY test_name";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sample Tests - Admin Panel</title>
  <link rel="stylesheet" href="dashboard.css" />
  <link rel="stylesheet" href="sampleRequests.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .message {
      margin-bottom: 20px;
      font-weight: bold;
      color: green;
    }
    .error {
      color: red;
    }
    form {
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      margin-bottom: 30px;
      max-width: 600px;
    }
    form input[type="text"], form input[type="url"] {
      width: 100%;
      padding: 8px 12px;
      margin-bottom: 15px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 1rem;
    }
    form button {
      background-color: #00bfff;
      color: white;
      border: none;
      padding: 10px 16px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
    }
    form button:hover {
      background-color: #009acd;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
      vertical-align: middle;
    }
    th {
      background-color: #00bfff;
      color: white;
    }
    .delete-btn, .edit-btn {
      padding: 6px 12px;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      text-decoration: none;
      display: inline-block;
      color: white;
      cursor: pointer;
      margin-right: 5px;
    }
    .delete-btn {
      background-color: #dc3545;
    }
    .delete-btn:hover {
      background-color: #b52b31;
    }
    .edit-btn {
      background-color: #007bff;
    }
    .edit-btn:hover {
      background-color: #0056b3;
    }
    .img-thumb {
      max-width: 60px;
      max-height: 60px;
      border-radius: 8px;
      object-fit: contain;
    }
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 220px;
      background: #00bfff;
      color: white;
      padding: 30px 20px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      z-index: 1000;
    }
    .main-content {
      margin-left: 220px; 
      padding: 30px;
      width: calc(100% - 220px);
      min-height: 100vh;
    }
    .main-content h1 {
      margin-bottom: 30px;
      color: #333;
    }
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
        <li class="active"><a href="sampleList.php">Sample Tests</a></li>
        <li><a href="sampleRequests.php">Sample Requests</a></li>
        <li><a href="reports.php">Reports</a></li>
        <li><a href="chatHospital.php">Chat Hospitals</a></li>
      </ul>
    </nav>
    <button class="logout-btn"><a href="../logout.php">Logout</a></button>
  </aside>

  <main class="main-content">
    <h1>Manage Sample Tests</h1>

    <?php if ($message): ?>
      <div class="message <?php echo (strpos($message, 'Error') !== false) ? 'error' : ''; ?>">
        <?php echo htmlspecialchars($message); ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="sampleList.php" novalidate>
      <h2>Add New Sample Test</h2>
      <label for="test_name">Test Name</label>
      <input type="text" id="test_name" name="test_name" required placeholder="Enter test name" />

      <label for="image_url">Image URL</label>
      <input type="url" id="image_url" name="image_url" required placeholder="Enter image URL" />

      <button type="submit" name="add_sample">Add Test</button>
    </form>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Test Name</th>
          <th>Image</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?php echo $row['id']; ?></td>
              <td><?php echo htmlspecialchars($row['test_name']); ?></td>
              <td><img class="img-thumb" src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['test_name']); ?>"></td>
              <td>
                <a href="edit/editSampleTest.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                <a href="sampleList.php?action=delete&id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this test?');">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="4" style="text-align:center;">No sample tests found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </main>
</div>
</body>
</html>
