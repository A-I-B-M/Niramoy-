<?php 
session_start();
include "../Connection.php";

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_esc = mysqli_real_escape_string($conn, $search);
$query = "
  SELECT 
    b.id, b.title, b.author, b.content, b.thumbnail_url, b.views,
    h.name AS hospital_name,
    IFNULL(AVG(br.rating), 0) AS avg_rating,
    COUNT(br.id) AS review_count,
    IFNULL(AVG(br.rating), 0) * LOG(1 + COUNT(br.id)) AS weighted_score
  FROM blog_posts b
  JOIN hospital_list h ON b.hospital_id = h.id
  LEFT JOIN blog_ratings br ON b.id = br.blog_id
";

if ($search_esc !== '') {
    $query .= " WHERE (b.title LIKE '%$search_esc%' OR b.author LIKE '%$search_esc%') ";
}

$query .= " GROUP BY b.id ORDER BY weighted_score DESC, b.created_at DESC";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Niramoy Blog</title>
  <link rel="stylesheet" href="blog.css" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    
    body {
      font-family: 'Poppins', sans-serif;
    }
    .search-bar {
      max-width: 500px;
      margin: 20px auto;
      display: flex;
    }
    .search-bar input {
      flex: 1;
      padding: 8px 12px;
      border: 1px solid #ccc;
      border-radius: 5px 0 0 5px;
      font-size: 16px;
    }
    .search-bar button {
      background-color: #007bff;
      border: none;
      color: white;
      padding: 8px 16px;
      border-radius: 0 5px 5px 0;
      font-size: 16px;
    }
    .avg-rating {
      color: #ffb400;
      font-weight: 600;
    }
    .blog-card {
      transition: box-shadow 0.3s ease;
    }
    .blog-card:hover {
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
   
  </style>
</head>
<body>

<header>
  <div class="container d-flex justify-content-between align-items-center py-3">
    <h1>নিরাময়</h1>
    <nav class="d-flex align-items-center">
      <a href="../index.php" class="mr-3">Home</a>
      <a href="../hospitals.php" class="mr-3">Hospitals</a>
      <a href="blog.php" class="mr-3">Blog</a>
      <a href="../Sample_Collection/sampleCollection.php" class="mr-3">Sample Collection</a>
      <?php if(isset($_SESSION['patient_logged_in'])): ?>
        <div class="dropdown">
          <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown">
            <?php echo htmlspecialchars($_SESSION['patient_name']); ?>
          </button>
          <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="../Patient_DashBord/dashboard.php">Dashboard</a>
            <a class="dropdown-item" href="../logout.php">Logout</a>
          </div>
        </div>
      <?php else: ?>
        <a href="../login.php" class="btn btn-success ml-3">Login</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<section class="hero text-center py-4 bg-light">
  <h2>Explore Wellness 💙</h2>
  <p>Get inspired with fresh, authentic health stories & lifestyle guides.</p>
</section>

<div class="search-bar">
  <form method="get" action="">
    <input type="text" name="search" placeholder="Search by title or author..." value="<?php echo htmlspecialchars($search); ?>" />
    <button type="submit">Search</button>
  </form>
</div>

<section class="blog-section container">
  <?php if (mysqli_num_rows($result) > 0): ?>
    <div class="row">
      <?php while($row = mysqli_fetch_assoc($result)): ?>
        <div class="col-md-4 mb-4">
          <div class="blog-card p-3 border rounded shadow-sm h-100 d-flex flex-column">
            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
            <p><?php echo htmlspecialchars(substr(strip_tags($row['content']), 0, 120)); ?>...</p>
            <p><strong>By:</strong> <?php echo htmlspecialchars($row['author'] ?: $row['hospital_name']); ?></p>
            <p class="avg-rating">
              ★ <?php echo number_format($row['avg_rating'], 2); ?> |
              Reviews: <?php echo $row['review_count']; ?> |
              Views: <?php echo (int)$row['views']; ?>
            </p>
            <a href="viewBlog.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-primary mt-auto align-self-start">Read More →</a>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p style="text-align:center;">No blog posts found.</p>
  <?php endif; ?>
</section>

<footer class="text-center mt-5 mb-3">
  <p>&copy; 2025 নিরাময়. All rights reserved.</p>
</footer>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>

</body>
</html>
