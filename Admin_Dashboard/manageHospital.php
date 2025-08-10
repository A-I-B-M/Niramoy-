<?php
include '../Connection.php';

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

$sql = "SELECT id, name, phone_no, hospital_city  FROM hospital_list";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage Hospitals - নিরাময়</title>
  <link rel="stylesheet" href="manageHospital.css">
  <link rel="stylesheet" href="dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    .edit-btn, .delete-btn {
      padding: 6px 12px;
      margin: 0 3px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      color: white;
      font-weight: 600;
      text-decoration: none;
      display: inline-block;
    }
    .edit-btn {
      background-color: #0d6efd;
    }
    .delete-btn {
      background-color: #dc3545; 
    }
    .message {
      font-weight: 600;
      margin-bottom: 20px;
    }
    .error {
      color: red;
    }
    .success {
      color: green;
    }
  </style>
</head>
<body>

<div class="container">

  <aside class="sidebar" style="position: fixed;
  top: 0;
  left: 0;
  width: 220px;
  height: 100vh;
  background: #00bfff;
  color: white;
  padding: 30px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;">
    <div class="logo">Admin Panel</div>
    <nav>
      <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li class="active"><a href="manageHospital.php">Manage Hospitals</a></li>
        <li><a href="manageDoctor.php">Manage Doctors</a></li>
        <li><a href="managePatients.php">Manage Patients</a></li>
        <li><a href="manageBlogs.php">Manage Blogs</a></li>
        <li><a href="sampleList.php">Sample List</a></li>
        <li><a href="sampleRequests.php">Sample Requests</a></li>
        <li><a href="reports.php">Reports</a></li>
        <li><a href="chatHospital.php">Chat Hospitals</a></li>
      </ul>
    </nav>
    <button class="logout-btn"><a href="../logout.php">Logout</a></button>
  </aside>

  <main class="main-content" style="margin-left: 220px;
  padding: 30px;
  height: 100vh;
  overflow-y: auto;
  flex: 1;">
    <h1>Manage Hospitals</h1>

    <?php 
    if (isset($_SESSION['error_message'])) {
        echo '<div class="message error">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
        unset($_SESSION['error_message']);
    }
    if (isset($_SESSION['success_message'])) {
        echo '<div class="message success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
        unset($_SESSION['success_message']);
    }
    ?>

    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Hospital Name</th>
            <th>City</th>
            <th>Phone</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($row['id']) . "</td>";
              echo "<td>" . htmlspecialchars($row['name']) . "</td>";
              echo "<td>" . htmlspecialchars($row['hospital_city']) . "</td>";
              echo "<td>" . htmlspecialchars($row['phone_no']) . "</td>";
              echo "<td>
                      <a href='edit/editHospital.php?id=" . urlencode($row['id']) . "' class='edit-btn'>Edit</a>
                      <a href='delete/deleteHospital.php?id=" . urlencode($row['id']) . "' class='delete-btn'>Delete</a>
                    </td>";
              echo "</tr>";
            }
          } else {
            echo "<tr><td colspan='5'>No hospitals found</td></tr>";
          }
          ?>
        </tbody>
      </table>
      <a href="addHospital.php" class="add-btn"><i class="fa fa-plus"></i> Add Hospital</a>
    </div>
  </main>

</div>

<script>
  document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function(event) {
      if (!confirm('Are you sure you want to delete this hospital?')) {
        event.preventDefault();
      }
    });
  });
</script>

</body>
</html>
