<?php 
  session_start();
  include "Connection.php";
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Hospitals</title>
    <link rel="stylesheet" href="hospitals.css" />
    
    <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background-color: #f4f9fc;
      color: #333;
    }

    header {
      background: #fff;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
      position: sticky;
      top: 0;
      z-index: 999;
    }

    .container {
      width: 90%;
      max-width: 1200px;
      margin: auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    header h1 {
    font-weight: 900;
    color: #00c6ff;
    font-size: 38px;
  }

    nav a {
    margin-left: 25px;
    text-decoration: none;
    color: #000000aa;
    font-weight: 900;
    font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
    
  }

    nav a:hover {
      color: #00c6ff;
    }

    .services-section {
      text-align: center;
      padding: 80px 20px;
      background: #e0f7fa;
    }

    .services-section h2 {
      font-size: 30px;
      color: #004d40;
      font-weight: 700;
      margin-bottom: 40px;
    }

    .location-filter {
      margin-bottom: 30px;
    }

    .location-filter label {
      font-size: 16px;
      font-weight: 500;
      margin-right: 10px;
    }

    .location-filter select {
      padding: 10px 16px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 16px;
    }

    .services-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 30px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .service-card {
      background: #fff;
      padding: 20px;
      border-radius: 16px;
      box-shadow: 0 6px 16px rgba(0,0,0,0.1);
      transition: transform 0.3s ease;
      text-align: center;
    }

    .service-card:hover {
      transform: translateY(-6px);
    }

    .service-card img {
      width: 100px;
      height: 100px;
      object-fit: contain;
      margin-bottom: 15px;
    }

    .service-card h3 {
      font-size: 18px;
      font-weight: 600;
      margin-bottom: 10px;
    }

    .service-card p {
      font-size: 14px;
      color: #555;
    }

    .explore-btn button {
      margin-top: 15px;
      background: linear-gradient(to right, #00c6ff, #0072ff);
      border: none;
      color: white;
      padding: 10px 16px;
      border-radius: 8px;
      font-size: 14px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .explore-btn button:hover {
      background: linear-gradient(to right, #0072ff, #00c6ff);
    }

    footer {
      text-align: center;
      padding: 20px;
      background: #fff;
      color: #777;
      font-size: 14px;
      border-top: 1px solid #eee;
    }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css"/>
   
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

          <?php if (isset($_SESSION['patient_logged_in'])): ?>
            <div class="dropdown">
              <button
                class="btn btn-primary dropdown-toggle"
                type="button"
                id="dropdownMenuButton"
                data-toggle="dropdown"
              >
                <?php echo htmlspecialchars($_SESSION['patient_name']); ?> 
              </button>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="Patient_DashBord/dashboard.php">Dashboard</a>
                <a class="dropdown-item" href="logout.php">Logout</a>
            </div>
          <?php else: ?>
            <a href="patientLogin.php" class="btn btn-success ml-3">Login</a>
          <?php endif; ?>
        </nav>
      </div>
    </header>

    <div class="services-section">
      <h2>Available Hospitals</h2>
      <div class="location-filter">
        <form method="GET" action="hospitals.php">
          <label for="location">Filter by Location:</label>
          <select id="location" name="location" onchange="this.form.submit()">
            <option value="">-- Select Location --</option>
            <?php
              $cityQuery = "SELECT DISTINCT hospital_city FROM hospital_list ORDER BY hospital_city ASC";
              $cityResult = mysqli_query($conn, $cityQuery);

              if ($cityResult && mysqli_num_rows($cityResult) > 0) {
                while ($cityRow = mysqli_fetch_assoc($cityResult)) {
                  $city = htmlspecialchars($cityRow['hospital_city']);
                  $selected = (isset($_GET['location']) && $_GET['location'] === $city) ? 'selected' : '';
                  echo "<option value=\"$city\" $selected>$city</option>";
                }
              }
            ?>
          </select>
        </form>
      </div>
      <div class="services-grid">
        <?php
          $cityFilter = isset($_GET['location']) && $_GET['location'] !== '' ? $_GET['location'] : null;

          if ($cityFilter) {
              $safeCity = mysqli_real_escape_string($conn, $cityFilter);
              $query = "SELECT * FROM hospital_list WHERE hospital_city = '$safeCity' ORDER BY name ASC";
          } else {
              $query = "SELECT * FROM hospital_list ORDER BY hospital_city ASC, name ASC";
          }

          $result = mysqli_query($conn, $query);

          if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              $name = htmlspecialchars($row['name']);
              $location = htmlspecialchars($row['hospital_city']);
              $area = htmlspecialchars($row['hospital_area']);
              $image = !empty($row['image_url']) ? $row['image_url'] : "image/hospital_image.jpg";
              $id = $row['id'];

              echo '
              <div class="service-card">
                <img src="' . $image . '" alt="Hospital" />
                <h3>' . $name . '</h3>
                <p><strong>Location:</strong> ' . $area . ', ' . $location . '</p>
                <a href="patient_hospital/dashboard.php?id=' . $id . '" class="explore-btn"><button>Explore</button></a>
              </div>';
            }
          } else {
            echo '<p>No hospitals found for the selected location.</p>';
          }
        ?>
      </div>
    </div>

    <footer>
      <p>&copy; 2025 নিরাময়. All Rights Reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script> <!-- Added -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script> <!-- Added -->
  </body>
</html>
