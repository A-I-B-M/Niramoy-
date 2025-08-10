<?php
session_start();
include "../Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

$sql = "
SELECT
  total_doctors.total_doctors,
  total_patients.total_patients,
  total_appointments.total_appointments,
  total_pending.total_pending_appointments,
  total_prescribed.total_prescribed_appointments,
  total_sample_tests.total_sample_tests,
  total_sample_accepted.total_sample_tests_accepted,
  total_hospitals.total_hospitals,
  total_prescriptions.total_prescriptions,
  total_blogs.total_blogs,
  COALESCE(total_blog_views.total_blog_views, 0) AS total_blog_views,
  total_blog_ratings.total_blog_ratings,
  ROUND(total_blog_ratings.avg_blog_rating, 2) AS avg_blog_rating,
  total_hospital_reviews.total_hospital_reviews,
  ROUND(total_hospital_reviews.avg_hospital_rating, 2) AS avg_hospital_rating,
  total_chat.total_chat_messages,
  gender_counts.male_patients,
  gender_counts.female_patients,
  total_specializations.total_specializations,
  unique_specs.unique_doctor_specializations,
  patients_multi_appts.patients_with_multiple_appointments
FROM
  (SELECT COUNT(DISTINCT id) AS total_doctors FROM doctor_list) AS total_doctors
  CROSS JOIN (SELECT COUNT(DISTINCT id) AS total_patients FROM patient_list) AS total_patients
  CROSS JOIN (SELECT COUNT(DISTINCT appointment_id) AS total_appointments FROM appointment_patient) AS total_appointments
  CROSS JOIN (SELECT COUNT(*) AS total_pending_appointments FROM appointment_patient WHERE status IS NULL) AS total_pending
  CROSS JOIN (SELECT COUNT(*) AS total_prescribed_appointments FROM appointment_patient WHERE status = 'prescribed') AS total_prescribed
  CROSS JOIN (SELECT COUNT(DISTINCT id) AS total_sample_tests FROM sample_requests) AS total_sample_tests
  CROSS JOIN (SELECT COUNT(*) AS total_sample_tests_accepted FROM sample_request_hospitals WHERE status = 'Accepted') AS total_sample_accepted
  CROSS JOIN (SELECT COUNT(DISTINCT id) AS total_hospitals FROM hospital_list) AS total_hospitals
  CROSS JOIN (SELECT COUNT(DISTINCT prescription_id) AS total_prescriptions FROM prescription_list) AS total_prescriptions
  CROSS JOIN (SELECT COUNT(DISTINCT id) AS total_blogs FROM blog_posts) AS total_blogs
  LEFT JOIN (
    SELECT SUM(views) AS total_blog_views FROM blog_posts
  ) AS total_blog_views ON 1=1
  CROSS JOIN (
    SELECT 
      COUNT(DISTINCT id) AS total_blog_ratings,
      AVG(rating) AS avg_blog_rating
    FROM blog_ratings
  ) AS total_blog_ratings
  CROSS JOIN (
    SELECT
      COUNT(DISTINCT review_id) AS total_hospital_reviews,
      AVG(rating) AS avg_hospital_rating
    FROM hospital_review
  ) AS total_hospital_reviews
  CROSS JOIN (
    SELECT COUNT(DISTINCT id) AS total_chat_messages FROM chat_messages
  ) AS total_chat
  CROSS JOIN (
    SELECT
      SUM(CASE WHEN gender = 'Male' THEN 1 ELSE 0 END) AS male_patients,
      SUM(CASE WHEN gender = 'Female' THEN 1 ELSE 0 END) AS female_patients
    FROM patient_list
  ) AS gender_counts
  CROSS JOIN (
    SELECT COUNT(DISTINCT specialization) AS total_specializations
    FROM symptom_category
    WHERE specialization IS NOT NULL AND specialization <> ''
  ) AS total_specializations
  CROSS JOIN (
    SELECT COUNT(DISTINCT specialization) AS unique_doctor_specializations 
    FROM doctor_list
    WHERE specialization IS NOT NULL AND specialization <> ''
  ) AS unique_specs
  CROSS JOIN (
    SELECT COUNT(patient_id) AS patients_with_multiple_appointments FROM (
      SELECT patient_id, COUNT(*) AS cnt
      FROM appointment_patient
      GROUP BY patient_id
      HAVING cnt > 1
    ) AS multi_appts
  ) AS patients_multi_appts
;"
;

$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reports & Analytics - Admin Panel</title>
  <link rel="stylesheet" href="dashboard.css" />
  <link rel="stylesheet" href="reports.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
      .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 220px;
        background: #00bfff;
        color: white;
        padding: 30px 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        z-index: 1000;
      }
      .main-content {
        margin-left: 220px;
        padding: 30px;
        width: calc(100% - 220px);
        min-height: 100vh;
      }

      .main-content h1 {
        margin-bottom: 30px;
        color: #333;
      }
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
        <li><a href="manageBlogs.php">Manage Blogs</a></li>
        <li><a href="sampleList.php">Sample List</a></li>
        <li><a href="sampleRequests.php">Sample Requests</a></li>
        <li class="active"><a href="reports.php">Reports</a></li>
        <li><a href="chatHospital.php">Chat Hospitals</a></li>

      </ul>
    </nav>
    <button class="logout-btn"><a href="../logout.php">Logout</a></button>
  </aside>
  <main class="main-content">
    <h1>Reports & Analytics</h1>

    <div class="report-grid">

      <div class="report-card">
        <i class="fa-solid fa-user-doctor icon"></i>
        <h3>Total Doctors</h3>
        <p><?php echo $data['total_doctors']; ?></p>
      </div>

      <div class="report-card">
        <i class="fa-solid fa-users icon"></i>
        <h3>Total Patients</h3>
        <p><?php echo $data['total_patients']; ?></p>
      </div>

      <div class="report-card">
        <i class="fa-solid fa-calendar-check icon"></i>
        <h3>Total Appointments</h3>
        <p><?php echo $data['total_appointments']; ?></p>
      </div>

      <div class="report-card">
        <i class="fa-solid fa-vial icon"></i>
        <h3>Sample Tests</h3>
        <p><?php echo $data['total_sample_tests']; ?></p>
      </div>

      <div class="report-card">
        <i class="fa-solid fa-hospital icon"></i>
        <h3>Hospitals Listed</h3>
        <p><?php echo $data['total_hospitals']; ?></p>
      </div>

      <div class="report-card">
        <i class="fa-solid fa-file-medical icon"></i>
        <h3>Prescriptions Given</h3>
        <p><?php echo $data['total_prescriptions']; ?></p>
      </div>

      <div class="report-card">
        <i class="fa-solid fa-file-medical icon"></i>
        <h3>Total Blogs</h3>
        <p><?php echo $data['total_blogs']; ?></p>
      </div>

      <div class="report-card">
        <i class="fa-solid fa-eye icon"></i>
        <h3>Total Blog Views</h3>
        <p><?php echo $data['total_blog_views']; ?></p>
      </div>

      <div class="report-card">
        <i class="fa-solid fa-star icon"></i>
        <h3>Average Blog Rating</h3>
        <p><?php echo $data['avg_blog_rating'] ?? 'N/A'; ?></p>
      </div>

      <div class="report-card">
        <i class="fa-solid fa-star-half-stroke icon"></i>
        <h3>Total Blog Ratings</h3>
        <p><?php echo $data['total_blog_ratings']; ?></p>
      </div>

      <div class="report-card">
        <i class="fa-solid fa-comments icon"></i>
        <h3>Total Hospital Reviews</h3>
        <p><?php echo $data['total_hospital_reviews']; ?></p>
      </div>

      <div class="report-card">
        <i class="fa-solid fa-heart-pulse icon"></i>
        <h3>Average Hospital Rating</h3>
        <p><?php echo $data['avg_hospital_rating'] ?? 'N/A'; ?></p>
      </div>

      <div class="report-card">
        <i class="fa-solid fa-comment-dots icon"></i>
        <h3>Total Chat Messages</h3>
        <p><?php echo $data['total_chat_messages']; ?></p>
      </div>

      <div class="report-card">
        <i class="fa-solid fa-mars icon"></i>
        <h3>Male Patients</h3>
        <p><?php echo $data['male_patients']; ?></p>
      </div>

      <div class="report-card">
        <i class="fa-solid fa-venus icon"></i>
        <h3>Female Patients</h3>
        <p><?php echo $data['female_patients']; ?></p>
      </div>

      <div class="report-card">
        <i class="fa-solid fa-stethoscope icon"></i>
        <h3>Total Specializations</h3>
        <p><?php echo $data['total_specializations']; ?></p>
      </div>

      <div class="report-card">
        <i class="fa-solid fa-user-group icon"></i>
        <h3>Patients with Multiple Appointments</h3>
        <p><?php echo $data['patients_with_multiple_appointments']; ?></p>
      </div>

      <div class="report-card">
        <i class="fa-solid fa-clock icon"></i>
        <h3>Pending Appointments</h3>
        <p><?php echo $data['total_pending_appointments']; ?></p>
      </div>

      <div class="report-card">
        <i class="fa-solid fa-check icon"></i>
        <h3>Prescribed Appointments</h3>
        <p><?php echo $data['total_prescribed_appointments']; ?></p>
      </div>

      <div class="report-card">
        <i class="fa-solid fa-vials icon"></i>
        <h3>Accepted Sample Tests</h3>
        <p><?php echo $data['total_sample_tests_accepted']; ?></p>
      </div>

    </div>
  </main>
</div>
</body>
</html>
