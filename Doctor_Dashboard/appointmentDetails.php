<?php
session_start();
include "../Connection.php";

if (!isset($_SESSION['doctor_id'])) {
    header("Location: ../login.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];

$sql = "SELECT 
          a.appointment_id, a.patient_id, IFNULL(a.status, '') AS status, a.appointment_date,
          p.first_name, p.last_name,
          GROUP_CONCAT(pa.allergy_name SEPARATOR ', ') AS allergies
        FROM appointment_patient a
        JOIN patient_list p ON a.patient_id = p.id
        LEFT JOIN patient_allergies pa ON a.patient_id = pa.patient_id
        WHERE a.doctor_id = $doctor_id
        GROUP BY a.appointment_id
        ORDER BY a.appointment_date DESC";

$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Appointment Details - নিরাময়</title>
  <link rel="stylesheet" href="dashboard.css" />
  <style>
    .prescribed-btn {
      background-color: #4caf50;
      color: white;
      border: none;
      padding: 6px 12px;
      cursor: not-allowed;
      border-radius: 4px;
      font-weight: bold;
      text-align: center;
      display: inline-block;
    }
    .prescribe-btn {
      background-color: #007bff;
      color: white;
      padding: 6px 12px;
      border-radius: 4px;
      text-decoration: none;
      font-weight: bold;
      display: inline-block;
    }
    .prescribe-btn:hover {
      background-color: #0056b3;
    }
    table {
      width: 100%;
      border-collapse: collapse;
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
          <li class="active"><a href="appointmentDetails.php">Appointment Details</a></li>
          <li><a href="precriptionList.php">Prescription List</a></li>
        </ul>
      </nav>
      <button class="logout-btn"><a href="../login.php">Logout</a></button>
    </aside>
    <main class="main-content">
      <header>
        <h1>Appointment Details</h1>
      </header>
      <div class="search-box">
        <input type="text" id="searchInput" class="search-input" onkeyup="searchPatient()" placeholder="Search by patient name...">
      </div>
      <section>
        <table id="appointmentTable">
          <thead>
            <tr>
              <th>Patient Name</th>
              <th>Appointment Date</th>
              <th>Allergies</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
              <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                  <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                  <td><?= htmlspecialchars($row['appointment_date']) ?></td>
                  <td><?= htmlspecialchars($row['allergies'] ?? 'None') ?></td>
                  <td>
                    <?php if ($row['status'] === 'prescribed'): ?>
                      <button disabled class="prescribed-btn">Prescribed ✔️</button>
                    <?php else: ?>
                      <a href="precriptionForm.php?appointment_id=<?= $row['appointment_id'] ?>" class="prescribe-btn">Prescribe</a>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="4">No appointments found.</td></tr>
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
      var table = document.getElementById("appointmentTable");
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
