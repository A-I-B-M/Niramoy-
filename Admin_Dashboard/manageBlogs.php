<?php
include '../Connection.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

$query = "SELECT * FROM blog_posts ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage Blogs - Admin Panel</title>
  <link rel="stylesheet" href="dashboard.css" />
  <link rel="stylesheet" href="manageBlogs.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .edit-btn, .delete-btn {
      padding: 6px 12px;
      margin: 0 4px;
      border-radius: 5px;
      font-weight: 600;
      text-decoration: none;
      color: white;
      display: inline-block;
    }
    .edit-btn { background: #0d6efd; }
    .delete-btn { background: #dc3545; }
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
        <li class="active"><a href="manageBlogs.php">Manage Blogs</a></li>
        <li><a href="sampleList.php">Sample List</a></li>
        <li><a href="sampleRequests.php">Sample Requests</a></li>
        <li><a href="reports.php">Reports</a></li>
        <li><a href="chatHospital.php">Chat Hospitals</a></li>

      </ul>
    </nav>
    <button class="logout-btn"><a href="../logout.php">Logout</a></button>
  </aside>

  <main class="main-content">
    <h1>Manage Blogs</h1>

    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Date</th>
            <th>Views</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['author']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
                <td><?= htmlspecialchars($row['views']) ?></td>
                <td>
                  <a href="edit/editBlog.php?id=<?= urlencode($row['id']) ?>" class="edit-btn">Edit</a>
                  <a href="delete/deleteBlog.php?id=<?= urlencode($row['id']) ?>" class="delete-btn"
                     onclick="return confirm('Are you sure you want to delete this blog?');">Delete</a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="6">No blog posts found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>

</div>
</body>
</html>
