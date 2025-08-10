<?php 
session_start();
include "../Connection.php";

$query = "SELECT * FROM sample_tests ORDER BY test_name";
$tests = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sample Collection - নিরাময়</title>
  <link rel="stylesheet" href="sampleCollection.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" />
</head>
<body>

  <header>
    <div class="container">
      <h1>নিরাময়</h1>
      <nav class="d-flex align-items-center">
        <a href="../index.php" class="mr-3">Home</a>
        <a href="../hospitals.php" class="mr-3">Hospitals</a>
        <a href="../Blogs/blog.php" class="mr-3">Blog</a>
        <a href="sampleCollection.php" class="mr-3">Sample Collection</a>

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

  <section class="sample-section">
    <h2>Select a Test for Sample</h2>
    <div class="test-grid">

      <?php
      if ($tests && mysqli_num_rows($tests) > 0):
        while ($test = mysqli_fetch_assoc($tests)):
      ?>
        <div class="test-card">
          <img src="<?php echo htmlspecialchars($test['image_url']); ?>" alt="<?php echo htmlspecialchars($test['test_name']); ?>" />
          <span><?php echo htmlspecialchars($test['test_name']); ?></span>
          <a href="form.php?test=<?php echo urlencode($test['test_name']); ?>">
            <button>Request Sample</button>
          </a>
        </div>
      <?php
        endwhile;
      else:
      ?>
        <p class="text-center">No tests available at the moment.</p>
      <?php endif; ?>

    </div>
  </section>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>

</body>
</html>

