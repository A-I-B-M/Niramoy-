<?php 
session_start();
include "Connection.php";

$globalAvgResult = mysqli_query($conn, "SELECT AVG(rating) as global_avg FROM hospital_review");
$globalAvgRow = mysqli_fetch_assoc($globalAvgResult);
$C = $globalAvgRow['global_avg'] ?? 0;

$m = 3;

$topHospitalsQuery = "
    SELECT 
      h.id, h.name, h.phone_no, h.img, h.hospital_city, h.hospital_area, h.tag_line,
      ROUND(AVG(r.rating), 2) AS avg_rating,
      COUNT(r.rating) AS review_count,
      (
        (COUNT(r.rating)*AVG(r.rating) + $m*$C) / (COUNT(r.rating) + $m)
      ) AS weighted_rating
    FROM hospital_list h
    JOIN hospital_review r ON h.id = r.hospital_id
    GROUP BY h.id
    HAVING review_count > 0
    ORDER BY weighted_rating DESC, review_count DESC
    LIMIT 3
";

$topHospitalsResult = mysqli_query($conn, $topHospitalsQuery);
if (!$topHospitalsResult) {
    die("Query failed: " . mysqli_error($conn));
}

$topDoctorsQuery = "
SELECT 
  d.id AS doctor_id,
  d.name AS doctor_name,
  d.specialization,
  h.id AS hospital_id,
  h.name AS hospital_name,
  ROUND(AVG(dr.rating), 2) AS avg_doctor_rating,
  COUNT(dr.review_id) AS doctor_review_count
FROM doctor_list d
JOIN hospital_list h ON d.hospital_id = h.id
LEFT JOIN doctor_review dr ON dr.doctor_id = d.id
GROUP BY d.id, h.id
HAVING doctor_review_count > 0
ORDER BY avg_doctor_rating DESC, doctor_review_count DESC
LIMIT 9
";

$topDoctorsResult = mysqli_query($conn, $topDoctorsQuery);
if (!$topDoctorsResult) {
    die("Doctor query failed: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>নিরাময় Hospital Management</title>

  <link rel="stylesheet" href="index.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">

  <style>
    .star {
      font-size: 1.2rem;
      color: gold;
    }
    .star.empty {
      color: #ccc;
    }
  </style>
</head>
<body>

<header>
  <div class="container d-flex justify-content-between align-items-center">
    <h1>নিরাময়</h1>

    <nav class="d-flex align-items-center">
      <a href="index.php" class="mr-3">Home</a>
      <a href="hospitals.php" class="mr-3">Hospitals</a>
      <a href="Blogs/blog.php" class="mr-3">Blog</a>
      <a href="Sample_Collection/sampleCollection.php" class="mr-3">Sample Collection</a>

      <?php if(isset($_SESSION['patient_logged_in'])): ?>
        <div class="dropdown">
          <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown">
            <?php echo htmlspecialchars($_SESSION['patient_name']); ?>
          </button>
          <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="Patient_DashBord/dashboard.php">Dashboard</a>
            <a class="dropdown-item" href="logout.php">Logout</a>
          </div>
        </div>
      <?php else: ?>
        <a href="login.php" class="btn btn-success ml-3">Login</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<section class="hero">
  <div class="hero-content">
    <h2>Find the Best Hospitals Near You</h2>
    <p>Your health journey starts here</p>
    <form action="search.php" method="get" class="search-bar">
      <input type="text" name="q" placeholder="Enter hospital name or location" required>
      <button type="submit">Search</button>
    </form>
  </div>
</section>

<section>
  <a href="symptom_doctor_suggestion.php" 
   style="display: inline-flex; align-items: center; background: #2F80ED; color: white; padding: 0.5rem 1rem; 
          border-radius: 25px; font-weight: 600; text-decoration: none; margin: 1rem auto; cursor: pointer; margin-left:700px">
  <svg xmlns="http://www.w3.org/2000/svg" fill="white" width="20" height="20" viewBox="0 0 24 24" style="margin-right: 8px;">
    <path d="M12 2a7 7 0 00-7 7c0 4.633 7 13 7 13s7-8.367 7-13a7 7 0 00-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z"/>
  </svg>
  Find Doctor by Symptom
</a>
</section>

<section class="hospital-section">
  <h3>Top Featured Hospitals</h3>
  <div class="hospital-list">
    <?php if (mysqli_num_rows($topHospitalsResult) > 0): ?>
      <?php while ($hospital = mysqli_fetch_assoc($topHospitalsResult)): ?>
        <div class="hospital-card">
          <?php if (!empty($hospital['img'])):?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($hospital['img']); ?>" alt="Hospital Image" class="hospital-img" />
          <?php else: ?>
            <img src="image/hospital_image.jpg" alt="Default Image" class="hospital-img" />
          <?php endif; ?>

          <h4><?php echo htmlspecialchars($hospital['name']); ?></h4>
          <p><strong>Location:</strong> <?php echo htmlspecialchars($hospital['hospital_area'] . ', ' . $hospital['hospital_city']); ?></p>
          <p><strong>Phone:</strong> <?php echo htmlspecialchars($hospital['phone_no']); ?></p>
          <?php if ($hospital['tag_line']): ?>
            <p><em><?php echo htmlspecialchars($hospital['tag_line']); ?></em></p>
          <?php endif; ?>

          <p><strong>Avg Rating:</strong> 
            <?php
              $roundedRating = round($hospital['avg_rating'] * 2) / 2;
              $fullStars = floor($roundedRating);
              $halfStar = ($roundedRating - $fullStars) == 0.5 ? true : false;
              $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

              for ($i = 0; $i < $fullStars; $i++) {
                echo '<span class="star">&#9733;</span>';
              }
              if ($halfStar) {
                echo '<span class="star">&#189;</span>'; 
              }
              for ($i = 0; $i < $emptyStars; $i++) {
                echo '<span class="star empty">&#9733;</span>';
              }
            ?>
            (<?php echo $hospital['avg_rating']; ?>/5, <?php echo $hospital['review_count']; ?> reviews)
          </p>

          <a href="patient_hospital/dashboard.php?id=<?php echo $hospital['id']; ?>" class="details-btn">
            View Details
          </a>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No top-rated hospitals found.</p>
    <?php endif; ?>
  </div>
</section>
<section class="doctor-section" style="padding: 2rem 1rem; max-width: 1200px; margin: 3rem auto 4rem;">
  <h3 style="font-weight: 600; font-size: 2rem; margin-bottom: 1.5rem; color: #2F80ED; text-align: center;">
    Top Rated Doctors
  </h3>
  <div class="doctor-list" style="display: flex; flex-wrap: wrap; gap: 1.5rem; justify-content: center;">
    <?php if (mysqli_num_rows($topDoctorsResult) > 0): ?>
      <?php while ($doctor = mysqli_fetch_assoc($topDoctorsResult)): ?>
        <div class="hospital-card" style="background: #e6f0ff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); 
                                         width: calc((100% / 3) - 1rem); padding: 1.5rem 1.2rem; text-align: center; display: flex; flex-direction: column;">
          <h4 style="font-size: 1.3rem; font-weight: 600; margin-bottom: 0.8rem; color: #003d99;">
            <?php echo htmlspecialchars($doctor['doctor_name']); ?> 
            (<span style="font-weight: 500; color: #3366cc;"><?php echo htmlspecialchars($doctor['specialization']); ?></span>)
          </h4>
          <p style="font-weight: 600; margin: 0.5rem 0; color: #004080;">
            <strong>Hospital:</strong> <?php echo htmlspecialchars($doctor['hospital_name']); ?>
          </p>
          <p style="font-size: 0.95rem; line-height: 1.5; color: #003366; margin: 0.5rem 0 1rem;">
            Doctor Rating: <strong><?php echo $doctor['avg_doctor_rating']; ?></strong> 
            (<?php echo $doctor['doctor_review_count']; ?> reviews)
          </p>
          <a href="patient_hospital/dashboard.php?id=<?php echo $doctor['hospital_id']; ?>" 
             style="margin-top: auto; background-color: #2F80ED; color: white; padding: .8rem 1rem; 
                    border-radius: 10px; text-decoration: none; font-weight: 600; transition: background-color 0.3s ease; display: inline-block;">
            View Hospital
          </a>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="text-align: center; color: #777; font-style: italic;">No doctors found with reviews yet.</p>
    <?php endif; ?>
  </div>
</section>


<section class="contact-section" style="background-color: #f5faff; padding: 3rem 1rem;">
  <h3 style="text-align: center; color: #2F80ED; font-size: 2rem; margin-bottom: 1.5rem;">Contact Us</h3>
  <div style="display: flex; justify-content: center;">
    <div style="width: 100%; max-width: 500px; background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); text-align: center;">
      <h4 style="margin-bottom: 1rem; color: #004080;">Get in Touch</h4>
      <p><strong>Phone:</strong> +880-1234-567890</p>
      <p><strong>Email:</strong> support@niramoy.com</p>
      <p><strong>Address:</strong> 123 Niramoy Street, Dhaka, Bangladesh</p>
      <p><strong>Hours:</strong> Mon - Fri, 9am - 6pm</p>
    </div>
  </div>
</section>

<footer>
  <p>&copy; 2025 নিরাময়. All Rights Reserved.</p>
</footer>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>

</body>
</html>
