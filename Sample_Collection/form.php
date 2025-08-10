<?php 
session_start();
include "../Connection.php";

if (!isset($_SESSION['patient_logged_in'])) {
  header("Location: ../login.php");
  exit();
}

if (isset($_GET['load_hospitals'])) {
  $city = mysqli_real_escape_string($conn, $_GET['load_hospitals']);
  $result = mysqli_query($conn, "SELECT id, name FROM hospital_list WHERE hospital_city = '$city'");
  $hospitals = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $hospitals[] = $row;
  }
  header('Content-Type: application/json');
  echo json_encode($hospitals);
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $test = mysqli_real_escape_string($conn, $_POST['selectedTest']);
  $patientName = mysqli_real_escape_string($conn, $_POST['patientName']);
  $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
  $address = mysqli_real_escape_string($conn, $_POST['address']);
  $date = $_POST['preferredDate'];
  $city = mysqli_real_escape_string($conn, $_POST['city']);
  $patientId = $_SESSION['patient_id'];
  $hospitalChoice = $_POST['hospital_choice'];

  if (!preg_match('/^\d{11}$/', $mobile)) {
    echo "<script>alert('Please enter a valid 11-digit phone number.'); window.history.back();</script>";
    exit();
  }

  if (strtotime($date) < strtotime(date('Y-m-d'))) {
    echo "<script>alert('Please choose a future date.'); window.history.back();</script>";
    exit();
  }

  $insertRequest = "
    INSERT INTO sample_requests (patient_id, test_name, patient_name, mobile, address, preferred_date, city)
    VALUES ('$patientId', '$test', '$patientName', '$mobile', '$address', '$date', '$city')
  ";
  if (!mysqli_query($conn, $insertRequest)) {
    echo "<script>alert('Error saving request.'); window.history.back();</script>";
    exit();
  }

  $requestId = mysqli_insert_id($conn); 

  if ($hospitalChoice === "all") {
    $hospitals = mysqli_query($conn, "SELECT id FROM hospital_list WHERE hospital_city = '$city'");
    while ($row = mysqli_fetch_assoc($hospitals)) {
      $hospitalId = $row['id'];
      mysqli_query($conn, "INSERT INTO sample_request_hospitals (request_id, hospital_id) VALUES ('$requestId', '$hospitalId')");
    }
    echo "<script>alert('Request sent to all hospitals in $city.'); window.location.href='sampleCollection.php';</script>";
  } else {
    if (!isset($_POST['selected_hospitals']) || count($_POST['selected_hospitals']) === 0) {
      echo "<script>alert('Please select at least one hospital.'); window.history.back();</script>";
      exit();
    }
    foreach ($_POST['selected_hospitals'] as $hospitalId) {
      $safeHospitalId = mysqli_real_escape_string($conn, $hospitalId);
      mysqli_query($conn, "INSERT INTO sample_request_hospitals (request_id, hospital_id) VALUES ('$requestId', '$safeHospitalId')");
    }
    echo "<script>alert('Request sent to selected hospital(s).'); window.location.href='sampleCollection.php';</script>";
  }
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sample Collection - নিরাময়</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="form.css" />
</head>

<body>

<header>
  <div class="container d-flex justify-content-between align-items-center">
    <h1 class="logo">নিরাময়</h1>
    <nav>
      <a href="../index.php">Home</a>
      <a href="../hospitals.php">Hospitals</a>
      <a href="../Blogs/blog.php">Blog</a>
      <a href="sampleCollection.php">Sample Collection</a>
      <?php if(isset($_SESSION['patient_logged_in'])): ?>
        <div class="dropdown d-inline-block">
          <button class="btn btn-light dropdown-toggle" data-toggle="dropdown">
            <?= htmlspecialchars($_SESSION['patient_name']); ?>
          </button>
          <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="../Patient_DashBord/dashboard.php">Dashboard</a>
            <a class="dropdown-item" href="../logout.php">Logout</a>
          </div>
        </div>
      <?php endif; ?>
    </nav>
  </div>
</header>

<section class="form-section">
  <h2 class="form-title">Request Sample Collection</h2>
  <form method="POST">
    <div class="form-group">
      <label for="selectedTest">Selected Test</label>
      <input type="text" name="selectedTest" id="selectedTest" class="form-control" readonly>
    </div>

    <div class="form-group">
      <label for="patientName">Full Name</label>
      <input type="text" id="patientName" name="patientName" class="form-control" required>
    </div>

    <div class="form-group">
      <label for="mobile">Mobile Number</label>
      <input type="tel" id="mobile" name="mobile" class="form-control" required pattern="\d{11}" maxlength="11">
    </div>

    <div class="form-group">
      <label for="address">Full Address</label>
      <textarea id="address" name="address" rows="3" class="form-control" required></textarea>
    </div>

    <div class="form-group">
      <label for="preferredDate">Preferred Date</label>
      <input type="date" id="preferredDate" name="preferredDate" class="form-control" required min="<?= date('Y-m-d'); ?>">
    </div>

    <div class="form-group">
      <label for="city">Select City</label>
      <select id="city" name="city" class="form-control" required>
        <option value="">--Choose City--</option>
        <?php
          $cities = mysqli_query($conn, "SELECT DISTINCT hospital_city FROM hospital_list WHERE hospital_city IS NOT NULL");
          while ($row = mysqli_fetch_assoc($cities)) {
            echo "<option value='" . htmlspecialchars($row['hospital_city']) . "'>" . htmlspecialchars($row['hospital_city']) . "</option>";
          }
        ?>
      </select>
    </div>

    <div class="form-group">
      <label>Hospital Selection</label>
      <div class="card p-3">
        <div class="custom-control custom-radio mb-2">
          <input type="radio" class="custom-control-input" id="allHospitals" name="hospital_choice" value="all" checked>
          <label class="custom-control-label" for="allHospitals">Send to all hospitals</label>
        </div>
        <div class="custom-control custom-radio">
          <input type="radio" class="custom-control-input" id="selectHospitals" name="hospital_choice" value="select">
          <label class="custom-control-label" for="selectHospitals">Select specific hospitals</label>
        </div>
      </div>
    </div>

    <div class="form-group hospital-list-group">
      <label for="hospitalList">Choose Hospitals</label>
      <select name="selected_hospitals[]" id="hospitalList" class="form-control selectpicker" multiple data-live-search="true" title="Choose hospital(s)">
      </select>
    </div>

    <button type="submit" class="btn btn-block submit-btn mt-3">Submit Request</button>
  </form>
</section>

<footer>
  &copy; <?= date("Y") ?> নিরাময়. All Rights Reserved.
</footer>

<script>
  const params = new URLSearchParams(window.location.search);
  const selectedTest = params.get("test");
  if (selectedTest) {
    document.getElementById("selectedTest").value = decodeURIComponent(selectedTest);
  }

  document.querySelectorAll('input[name="hospital_choice"]').forEach(r => {
    r.addEventListener('change', function () {
      document.querySelector('.hospital-list-group').style.display = (this.value === 'select') ? 'block' : 'none';
    });
  });

  document.getElementById('city').addEventListener('change', function () {
    const city = this.value;
    const list = document.getElementById('hospitalList');
    list.innerHTML = '';
    if (!city) return;

    fetch(`form.php?load_hospitals=${encodeURIComponent(city)}`)
      .then(res => res.json())
      .then(data => {
        data.forEach(h => {
          const opt = document.createElement('option');
          opt.value = h.id;
          opt.textContent = h.name;
          list.appendChild(opt);
        });
        $('.selectpicker').selectpicker('refresh');
      });
  });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

</body>
</html>
