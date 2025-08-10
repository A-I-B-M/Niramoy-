<?php
session_start();
include "../Connection.php"; 

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];
$admin_username = $_SESSION['admin_username'] ?? 'Admin';
$search = $_GET['search'] ?? '';
$search_param = '%' . $search . '%';
$sql = "SELECT id, name, hospital_city, phone_no FROM hospital_list WHERE name LIKE ? OR hospital_city LIKE ? ORDER BY name";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $search_param, $search_param);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Chat Hospitals - নিরাময়</title>
  <link rel="stylesheet" href="dashboard.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      background: #fff;
      border-radius: 8px;
      overflow: hidden;
    }
    th, td {
      padding: 10px 10px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }
    th {
      background-color: #00bfff;
      color: white;
    }
    tr:hover {
      background-color: #f1f9ff;
    }
    .chat-btn {
      background-color: #00bfff;
      color: white;
      padding: 6px 12px;
      margin-right: 100px;
      border-radius: 5px;
      text-decoration: none;
      font-weight: 600;
      display: inline-block;
      transition: background-color 0.3s;
      margin-left: 0px;
    }
    .chat-btn:hover {
      background-color: #0099cc;
    }
    main {
      margin-left: 220px;
      padding: 30px;
      min-height: 100vh;
      background: #f5f9ff;
    }
    .search-box {
      margin-bottom: 15px;
    }
    .search-box input[type="text"] {
      width: 300px;
      padding: 8px 12px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 16px;
      outline: none;
    }
    .search-box button {
      padding: 8px 16px;
      background-color: #00bfff;
      border: none;
      color: white;
      font-weight: 600;
      border-radius: 6px;
      cursor: pointer;
      margin-left: 8px;
      transition: background-color 0.3s;
    }
    .search-box button:hover {
      background-color: #0099cc;
    }
  </style>
</head>
<body>

<div class="container">
  <aside class="sidebar" style="position: fixed; top: 0; left: 0; width: 220px; height: 100vh; background: #00bfff; color: white; padding: 30px; display: flex; flex-direction: column; justify-content: space-between;">
    <div class="logo">Admin Panel</div>
    <nav>
      <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="manageHospital.php">Manage Hospitals</a></li>
        <li><a href="manageDoctor.php">Manage Doctors</a></li>
        <li><a href="managePatients.php">Manage Patients</a></li>
        <li><a href="manageBlogs.php">Manage Blogs</a></li>
        <li><a href="sampleList.php">Sample List</a></li>
        <li><a href="sampleRequests.php">Sample Requests</a></li>
        <li><a href="reports.php">Reports</a></li>
        <li class="active"><a href="chatHospital.php">Chat Hospitals</a></li>
      </ul>
    </nav>
    <button class="logout-btn"><a href="../logout.php" style="color: black; text-decoration: none;">Logout</a></button>
  </aside>

  <main class="main-content">
    <section class="section">
      <h2>Chat With Hospitals</h2>

      <form method="get" class="search-box" action="">
        <input type="text" name="search" placeholder="Search hospital name or city..." value="<?php echo htmlspecialchars($search); ?>" />
        <button type="submit"><i class="fa fa-search"></i> Search</button>
      </form>

      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Hospital Name</th>
            <th>City</th>
            <th>Phone</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              ?>
              <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['hospital_city']); ?></td>
                <td><?php echo htmlspecialchars($row['phone_no']); ?></td>
                <td>
                  <a href="chatWithHospital.php?admin_id=<?php echo urlencode($admin_id); ?>&hospital_id=<?php echo urlencode($row['id']); ?>" 
                     class="chat-btn">
                    <i class="fa fa-comments"></i> Chat
                  </a>
                </td>
              </tr>
              <?php
            }
          } else {
            echo '<tr><td colspan="5">No hospitals found.</td></tr>';
          }
          ?>
        </tbody>
      </table>
    </section>
  </main>
</div>

</body>
</html>
