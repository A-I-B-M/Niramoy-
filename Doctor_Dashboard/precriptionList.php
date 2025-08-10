<?php
session_start();
include "../Connection.php";

if (!isset($_SESSION['doctor_id'])) {
    header("Location: ../login.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];
$sql = "SELECT p.*, CONCAT(pl.first_name, ' ', pl.last_name) AS patient_name
        FROM prescription_list p
        JOIN patient_list pl ON p.patient_id = pl.id
        WHERE p.doctor_id = $doctor_id
        ORDER BY p.prescription_id DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Doctor Dashboard - নিরাময়</title>
  <link rel="stylesheet" href="dashboard.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      padding: 10px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }
    .search-box {
      margin: 20px 0;
      width: 100%;
    }
    .search-input {
      width: 100%;
      padding: 10px;
      font-size: 16px;
      border: 2px solid #007bff;
      border-radius: 6px;
    }
  </style>
</head>
<body>

  <div class="container">
    <aside class="sidebar">
      <div class="logo">🩺 নিরাময়</div>
      <nav>
        <ul>
          <li><a href="dashboard.php">Dashboard</a></li>
          <li><a href="appointmentDetails.php">Appointment Details</a></li>
          <li class="active"><a href="precriptionList.php">Prescription List</a></li>
        </ul>
      </nav>
      <button class="logout-btn"><a href="../login.php">Logout</a></button>
    </aside>
    <main class="main-content">
      <header>
        <h1>Prescription List</h1>
      </header>
      <div class="search-box">
        <input type="text" id="searchInput" class="search-input" onkeyup="searchPatient()" placeholder="Search by patient name...">
      </div>

      <section class="prescription-table">
        <table id="prescriptionTable">
          <thead>
            <tr>
              <th>Patient Name</th>
              <th>Appointment Date</th>
              <th>Disease</th>
              <th>Allergy</th>
              <th>Prescribe</th>
            </tr>
          </thead>
          <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
              <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                  <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                  <td><?php echo htmlspecialchars($row['dates']); ?></td>
                  <td><?php echo htmlspecialchars($row['disease']); ?></td>
                  <td><?php echo htmlspecialchars($row['allergies']); ?></td>
                  <td><?php echo htmlspecialchars($row['prescription']); ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="5">No prescriptions found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </section>
    </main>

  </div>
  <script>
    function searchPatient() {
      var input = document.getElementById("searchInput");
      var filter = input.value.toLowerCase();
      var table = document.getElementById("prescriptionTable");
      var tr = table.getElementsByTagName("tr");

      for (var i = 1; i < tr.length; i++) {
        var td = tr[i].getElementsByTagName("td")[0];
        if (td) {
          var name = td.textContent || td.innerText;
          tr[i].style.display = name.toLowerCase().indexOf(filter) > -1 ? "" : "none";
        }
      }
    }
  </script>

</body>
</html>
