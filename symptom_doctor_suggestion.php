<?php
session_start();
include "Connection.php";

$symptomsInput = [];
$results = [];
$selectedCity = $_GET['city'] ?? '';

$cityQuery = mysqli_query($conn, "SELECT DISTINCT hospital_city FROM hospital_list WHERE hospital_city IS NOT NULL");
if (!$cityQuery) {
    die("City Query failed: " . mysqli_error($conn));
}
if (isset($_GET['symptom'])) {
    $symptomsInput = array_filter(array_map('trim', explode(',', $_GET['symptom'])));
    if (!empty($symptomsInput)) {
        $likes = array_map(fn($s) => "symptom LIKE '%" . mysqli_real_escape_string($conn, $s) . "%'", $symptomsInput);
        $where = implode(' OR ', $likes);

        $cityFilter = $selectedCity ? "AND h.hospital_city = '" . mysqli_real_escape_string($conn, $selectedCity) . "'" : "";
        
        $q = "
            SELECT 
              d.id AS doctor_id,
              d.name AS doctor_name,
              d.specialization,
              d.experience_years AS experience_years,
              h.id AS hospital_id,
              h.name AS hospital_name,
              h.hospital_city,
              COUNT(DISTINCT hr.review_id) AS hospital_review_count,
              COUNT(DISTINCT dr.review_id) AS doctor_review_count,
              COUNT(DISTINCT sm.symptom) AS matching_symptom_count,
              GROUP_CONCAT(DISTINCT sm.symptom ORDER BY sm.symptom SEPARATOR ', ') AS matched_symptoms
            FROM doctor_list d
            JOIN hospital_list h ON d.hospital_id = h.id
            JOIN symptom_category sm ON d.specialization = sm.specialization
            LEFT JOIN hospital_review hr ON h.id = hr.hospital_id
            LEFT JOIN doctor_review dr ON d.id = dr.doctor_id
            WHERE ($where) $cityFilter
            GROUP BY d.id, h.id
            ORDER BY
              matching_symptom_count DESC,
              doctor_review_count DESC,
              hospital_review_count DESC,
              d.experience_years DESC,
              d.name ASC
            LIMIT 6
        ";

        $rs = mysqli_query($conn, $q);
        if (!$rs) {
            echo "<pre>SQL query failed:\n$q\nError: " . mysqli_error($conn) . "</pre>";
            die();
        }
        while ($row = mysqli_fetch_assoc($rs)) $results[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Find Doctor by Symptoms</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
  <style>
    body { font-family:'Poppins',sans-serif; background:#f5f9ff; color:#103466; margin:0; padding:1rem; }
    .container { max-width:1200px; margin:2rem auto; background:#fff; padding:2rem; border-radius:12px; box-shadow:0 6px 20px rgba(47,128,237,0.15); }
    h1,h2 { color:#2F80ED; font-weight:600; text-align:center; }
    form { text-align:center; margin-bottom:2rem; }
    .symptom-label { display:inline-flex; align-items:center; margin:0.3rem; padding:0.5rem 1rem; background:#e6f0ff; color:#2F80ED; border-radius:20px; cursor:pointer; transition:0.3s; }
    .symptom-label:hover, .symptom-label.checked { background:#2F80ED; color:#fff; }
    input.symptom-checkbox { display:none; }
    input[type="text"], select {
      width:250px; padding:0.8rem 1rem; border:2px solid #2F80ED; border-radius:30px; font-size:1rem; outline:none;
      transition:border-color 0.3s; margin-top: 1rem;
    }
    input[type="text"]:focus, select:focus { border-color:#1459d1; }
    button.search-btn {
      background:#2F80ED; color:#fff; padding:0.8rem 2rem; margin-left:1rem; border:none; border-radius:30px;
      font-size:1rem; font-weight:600; cursor:pointer; transition:background 0.3s; margin-top: 1rem;
    }
    button.search-btn:hover { background:#1459d1; }
    .card-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 1.5rem;
      justify-content: center;
    }
    .result-card {
      background:#eaf2ff; border-radius:12px; padding:1.5rem;
      box-shadow:0 4px 12px rgba(47,128,237,0.1); transition:box-shadow 0.3s;
      width: calc(33.33% - 1rem);
      display: flex;
      flex-direction: column;
    }
    .result-card:hover { box-shadow:0 6px 20px rgba(47,128,237,0.3); }
    .result-card h3 { margin:0 0 0.5rem; }
    .result-card p { margin:0.2rem 0; font-size:0.95rem; flex-grow: 0; }
    .result-card .matched-symptoms {
      font-style: italic;
      color: #00509e;
      margin-top: 0.4rem;
      flex-grow: 1;
    }
    .details-link {
      margin-top: auto; 
      display:inline-block; 
      background:#2F80ED; 
      color:#fff; 
      padding:0.5rem 1.5rem;
      border-radius:30px; 
      text-decoration:none; 
      font-weight:600; 
      transition:background 0.3s;
      align-self: flex-start;
    }
    .details-link:hover { background:#1459d1; }
    .noresults { text-align:center; color:#777; font-style:italic; margin-top:2rem; }
    .back-btn {
      margin-bottom: 1rem;
      background: #2F80ED;
      color: white;
      padding: 0.5rem 1.5rem;
      border: none;
      border-radius: 30px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s;
    }
    .back-btn:hover {
      background: #1459d1;
    }
    .back-btn a {
      color: white;
      text-decoration: none;
      display: inline-block;
    }
    @media(max-width: 992px) {
      .result-card { width: calc(50% - 1rem); }
    }
    @media(max-width: 600px) {
      .result-card { width: 100%; }
    }
  </style>
</head>
<body>
  <div class="container">

    <button class="back-btn"><a href="index.php">← Back</a></button>

    <h1>Find Doctor by Symptoms</h1>
    <form method="get">
      <div>
        <?php
          $rows = mysqli_query($conn, "SELECT symptom FROM symptom_category");
          $list = [];
          while ($r = mysqli_fetch_assoc($rows)) {
            foreach (preg_split('/,\s*/', $r['symptom']) as $s) {
              $s = trim($s);
              if ($s && !in_array($s, $list)) $list[] = $s;
            }
          }
          $list = array_slice($list, 0, 15);
          foreach ($list as $s) {
            $slug = preg_replace('/[^a-z0-9]/i','_', $s);
            $checked = in_array($s, $symptomsInput) ? 'checked' : '';
            echo "<label class='symptom-label" . ($checked?" checked":"") . "'>" .
                   "<input type='checkbox' class='symptom-checkbox' $checked value='".htmlspecialchars($s)."'/> $s</label>";
          }
        ?>
      </div>
      <input type="text" name="symptom" placeholder="Or type symptoms, comma‑separated"
             value="<?= isset($_GET['symptom'])?htmlspecialchars($_GET['symptom']):'' ?>">
      <br />
      
      <select name="city" aria-label="Select City">
        <option value="">All Cities</option>
        <?php while($row = mysqli_fetch_assoc($cityQuery)): ?>
          <option value="<?= htmlspecialchars($row['hospital_city']) ?>" <?= $selectedCity == $row['hospital_city'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($row['hospital_city']) ?>
          </option>
        <?php endwhile; ?>
      </select>
      <button type="submit" class="search-btn">Find Doctor</button>
    </form>

    <?php if (isset($_GET['symptom'])): ?>
      <?php if (count($results)): ?>
        <h2>Suggested Doctors</h2>
        <div class="card-grid">
        <?php foreach ($results as $doc): ?>
          <div class="result-card">
            <h3><?= htmlspecialchars($doc['doctor_name']) ?></h3>
            <p><strong>Specialization:</strong> <?= htmlspecialchars($doc['specialization']) ?></p>
            <p><strong>Hospital:</strong> <?= htmlspecialchars($doc['hospital_name']) ?> (<?= htmlspecialchars($doc['hospital_city']) ?>)</p>
            <p><strong>Symptoms Matched:</strong> <?= $doc['matching_symptom_count'] ?></p>
            <p class="matched-symptoms">Matched: <?= htmlspecialchars($doc['matched_symptoms']) ?></p>
            <p><strong>Doctor Reviews:</strong> <?= $doc['doctor_review_count'] ?></p>
            <p><strong>Experience:</strong> <?= $doc['experience_years'] ?> years</p>
            <a href="patient_hospital/dashboard.php?id=<?= $doc['hospital_id'] ?>" class="details-link">View Hospital</a>
          </div>
        <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="noresults">No doctors found matching those symptoms.</p>
      <?php endif; ?>
    <?php endif; ?>
  </div>

  <script>
    document.querySelectorAll('.symptom-checkbox').forEach(chk => {
      chk.addEventListener('change', () => {
        chk.closest('label').classList.toggle('checked', chk.checked);
        const vals = Array.from(document.querySelectorAll('.symptom-checkbox:checked')).map(c=>c.value);
        document.querySelector('input[name="symptom"]').value = vals.join(', ');
      });
    });
  </script>
</body>
</html>
