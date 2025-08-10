<?php
session_start();
include "Connection.php";

$searchQuery = $_GET['q'] ?? '';
$searchQuery = trim($searchQuery);
$searchQuerySafe = mysqli_real_escape_string($conn, $searchQuery);

$sql = "SELECT * FROM hospital_list 
        WHERE name LIKE '%$searchQuerySafe%' 
           OR hospital_area LIKE '%$searchQuerySafe%'
           OR hospital_city LIKE '%$searchQuerySafe%'
           OR hospital_state LIKE '%$searchQuerySafe%'";

$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Search Results - নিরাময়</title>
    <link rel="stylesheet" href="search.css">
  
</head>
<body>

<header>
  নিরাময়
  <button onclick="history.back()" class="back-button">← Back</button>
</header>

<main class="container">
  <h2 class="search-title">Search Results for "<?php echo htmlspecialchars($searchQuery); ?>"</h2>

  <?php if (mysqli_num_rows($result) > 0): ?>
    <section class="hospital-list">
      <?php while($row = mysqli_fetch_assoc($result)): ?>
        <div class="hospital-card">
          <h3><?php echo htmlspecialchars($row['name']); ?></h3>
          <p><strong>Location:</strong> <?php echo htmlspecialchars($row['hospital_area'] . ', ' . $row['hospital_city'] . ', ' . $row['hospital_state']); ?></p>
          <p><strong>Phone:</strong> <a href="tel:<?php echo htmlspecialchars($row['phone_no']); ?>"><?php echo htmlspecialchars($row['phone_no']); ?></a></p>
          
          <a href="patient_hospital/dashboard.php?id=<?php echo $row['id']; ?>" class="details-btn">
                View Details
          </a>

        </div>
      <?php endwhile; ?>
    </section>
  <?php else: ?>
    <p class="no-results">No hospitals found for "<?php echo htmlspecialchars($searchQuery); ?>".</p>
  <?php endif; ?>
</main>

<footer>
  &copy; 2025 নিরাময়. All Rights Reserved.
</footer>

</body>
</html>
