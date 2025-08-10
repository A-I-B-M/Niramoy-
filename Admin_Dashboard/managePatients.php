<?php
session_start();
include "../Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

$query = "SELECT * FROM patient_list ORDER BY id DESC";
$result = mysqli_query($conn, $query);
$patients = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $patients[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage Patients - Admin Panel</title>
  <link rel="stylesheet" href="dashboard.css" />
  <link rel="stylesheet" href="managePatients.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    table th, table td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: left;
    }
    table th {
      background-color: #00bfff;
      color: white;
    }
    .edit-btn, .delete-btn {
      text-decoration: none;
      padding: 6px 12px;
      border-radius: 4px;
      font-size: 0.9rem;
      margin-right: 5px;
    }
    .edit-btn {
      background-color: #4CAF50;
      color: white;
    }
    .delete-btn {
      background-color: #f44336;
      color: white;
    }
    .add-btn {
      margin-top: 20px;
      background-color: #00bfff;
      color: white;
      padding: 10px 16px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
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
        <li><a href="manageHospital.php">Manage Hospitals</a></li>
        <li><a href="manageDoctor.php">Manage Doctors</a></li>
        <li class="active"><a href="managePatients.php">Manage Patients</a></li>
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
    <h1>Manage Patients</h1>

    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Gender</th>
            <th>Phone</th>
            <th>Password</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($patients) > 0): ?>
            <?php foreach ($patients as $patient): ?>
              <tr>
                <td><?= htmlspecialchars($patient['id']) ?></td>
                <td><?= htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']) ?></td>
                <td><?= htmlspecialchars($patient['email']) ?></td>
                <td><?= htmlspecialchars($patient['gender']) ?></td>
                <td><?= htmlspecialchars($patient['phone_no']) ?></td>
                <td><?= htmlspecialchars($patient['password']) ?></td>
                <td>
                  <a href="edit/editPatient.php?id=<?= $patient['id'] ?>" class="edit-btn">Edit</a>
                  <a href="delete/deletePatient.php?id=<?= $patient['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure to delete this patient?')">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="7">No patients found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>
</body>
</html>
