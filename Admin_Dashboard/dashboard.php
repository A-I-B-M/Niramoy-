<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

$admin_username = $_SESSION['admin_username'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard - নিরাময়</title>
  <link rel="stylesheet" href="dashboard.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>

  <div class="container">
    <aside class="sidebar">
      <div class="logo">Admin Panel</div>
      <nav>
        <ul>
          <li class="active"><a href="dashboard.php">Dashboard</a></li>
          <li><a href="manageHospital.php">Manage Hospitals</a></li>
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

    <main class="main-content">
      <h1>Welcome, <?php echo htmlspecialchars($admin_username); ?></h1>
      <div class="card-grid">

        <div class="card gray">
          <i class="fa-solid fa-hospital icon"></i>
          <h3>Manage Hospitals</h3>
          <a href="manageHospital.php">View</a>
        </div>

        <div class="card gray">
          <i class="fa-solid fa-user-doctor icon"></i>
          <h3>Manage Doctors</h3>
          <a href="manageDoctor.php">View</a>
        </div>

        <div class="card gray">
          <i class="fa-solid fa-users icon"></i>
          <h3>Manage Patients</h3>
          <a href="managePatients.php">View</a>
        </div>

        <div class="card gray">
          <i class="fa-solid fa-blog icon"></i>
          <h3>Manage Blogs</h3>
          <a href="manageBlogs.php">View</a>
        </div>

        <div class="card gray">
          <i class="fa-solid fa-vial icon"></i>
          <h3>Sample Requests</h3>
          <a href="sampleRequests.php">View</a>
        </div>

        <div class="card gray">
          <i class="fa-solid fa-chart-line icon"></i>
          <h3>Reports & Analytics</h3>
          <a href="reports.php">View</a>
        </div>

      </div>
    </main>
  </div>

</body>
</html>
