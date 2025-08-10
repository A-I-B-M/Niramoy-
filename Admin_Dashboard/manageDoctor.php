<?php
include '../Connection.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

$sql = "SELECT d.id, d.name, d.specialization, d.phone_no, h.name AS hospital_name
        FROM doctor_list d
        LEFT JOIN hospital_list h ON d.hospital_id = h.id";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage Doctors - Admin Panel</title>
  <link rel="stylesheet" href="dashboard.css" />
  <link rel="stylesheet" href="manageDoctor.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
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
        <li class="active"><a href="manage-doctors.php">Manage Doctors</a></li>
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
    <h1>Manage Doctors</h1>

    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Specialization</th>
            <th>Phone</th>
            <th>Hospital</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['specialization']) ?></td>
              <td><?= htmlspecialchars($row['phone_no']) ?></td>
              <td><?= htmlspecialchars($row['hospital_name'] ?? 'N/A') ?></td>
              <td>
              <td>
                <a href="edit/editDoctor.php?id=<?= $row['id'] ?>" class="edit-btn">Edit</a>
                <a href="delete/deleteDoctor.php?id=<?= $row['id'] ?>" class="delete-btn">Delete</a>
              </td>

              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </main>

</div>
</body>
</html>
