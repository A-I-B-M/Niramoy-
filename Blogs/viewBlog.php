<?php
session_start();
include "../Connection.php";

$blogId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($blogId <= 0) {
    die("Invalid blog ID.");
}

$updateViewSql = "UPDATE blog_posts SET views = views + 1 WHERE id = $blogId";
mysqli_query($conn, $updateViewSql);

$sql = "SELECT b.*, h.name AS hospital_name 
        FROM blog_posts b 
        JOIN hospital_list h ON b.hospital_id = h.id 
        WHERE b.id = $blogId";
$result = mysqli_query($conn, $sql);
if (!$result || mysqli_num_rows($result) == 0) {
    die("Blog not found.");
}
$blog = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['patient_logged_in'])) {
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $patient_id = $_SESSION['patient_id'];

    if ($rating >= 1 && $rating <= 5) {
        $check = mysqli_query($conn, "SELECT id FROM blog_ratings WHERE blog_id = $blogId AND patient_id = $patient_id");
        if (mysqli_num_rows($check) > 0) {
            mysqli_query($conn, "UPDATE blog_ratings SET rating = $rating, comment = '$comment', created_at = NOW() WHERE blog_id = $blogId AND patient_id = $patient_id");
        } else {
            mysqli_query($conn, "INSERT INTO blog_ratings (blog_id, patient_id, rating, comment) VALUES ($blogId, $patient_id, $rating, '$comment')");
        }
    }
}

$reviews = mysqli_query($conn, "
    SELECT r.*, p.first_name, p.last_name 
    FROM blog_ratings r 
    JOIN patient_list p ON r.patient_id = p.id 
    WHERE r.blog_id = $blogId
    ORDER BY r.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title><?php echo htmlspecialchars($blog['title']); ?> - Niramoy Blog</title>
  <link rel="stylesheet" href="viewBlog.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>

<header class="minimal-header">
  <button onclick="history.back()" class="back-btn"><i class="fas fa-arrow-left"></i> Back</button>
</header>

<main class="blog-container">
  <div class="blog-content">
    <h2><?php echo htmlspecialchars($blog['title']); ?></h2>
    <p class="blog-meta">
      By <?php echo htmlspecialchars($blog['author'] ?: $blog['hospital_name']); ?> • 
      <?php echo date('F j, Y', strtotime($blog['created_at'])); ?> • 
      👁️ <?php echo (int)$blog['views']; ?> views
    </p>

    <div class="blog-body">
      <?php echo nl2br(htmlspecialchars($blog['content'])); ?>
    </div>

    <section class="rating-section">
      <h4>Rate this blog</h4>
      <?php if (isset($_SESSION['patient_logged_in']) && $_SESSION['patient_logged_in']): ?>
        <form method="post" class="rating-form">
          <div class="stars">
            <?php for ($i = 5; $i >= 1; $i--): ?>
              <input type="radio" name="rating" id="star<?php echo $i; ?>" value="<?php echo $i; ?>">
              <label for="star<?php echo $i; ?>" title="<?php echo $i; ?> stars"></label>
            <?php endfor; ?>
          </div>
          <div class="comment-section">
            <label for="comment">Leave a comment</label>
            <textarea id="comment" name="comment" placeholder="Write your thoughts here..."></textarea>
          </div>
          <button type="submit">Submit</button>
        </form>
      <?php else: ?>
        <p style="color: #ff4c4c;">
          Please <a href="../login.php" style="color:#00c6ff;">log in</a> to comment or rate this blog.
        </p>
      <?php endif; ?>
    </section>

    <section class="reviews">
      <h4>All Comments</h4>
      <?php if (mysqli_num_rows($reviews) > 0): ?>
        <?php while($r = mysqli_fetch_assoc($reviews)): ?>
          <div class="review" >
            <strong><?php echo htmlspecialchars($r['first_name'] . ' ' . $r['last_name']); ?></strong> 
            <span class="stars"><?php echo str_repeat("★", (int)$r['rating']); ?></span>
            <p style="margin-top: -10px;"><?php echo htmlspecialchars($r['comment']); ?></p>
            <small><?php echo date('F j, Y H:i', strtotime($r['created_at'])); ?></small>
            <p style="margin-bottom: 70px;"></p>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No reviews yet.</p>
      <?php endif; ?>
    </section>
  </div>
</main>

<footer>
  <p>&copy; 2025 নিরাময়. All rights reserved.</p>
</footer>

</body>
</html>
