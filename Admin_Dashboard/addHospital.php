<?php
include '../Connection.php';

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone_no = trim($_POST['phone_no'] ?? '');
    $hospital_state = trim($_POST['hospital_state'] ?? '');
    $hospital_city = trim($_POST['hospital_city'] ?? '');
    $hospital_area = trim($_POST['hospital_area'] ?? '');
    $tag_line = trim($_POST['tag_line'] ?? '');
    $latitude = trim($_POST['latitude'] ?? null);
    $longitude = trim($_POST['longitude'] ?? null);
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($name === '' || $username === '' || $password === '') {
        $error = "Please fill all required fields (Name, Username, Password).";
    } else {
        $uploadDir = "../uploads/hospital_images/";
        $defaultImgPath = "uploads/hospital_images/default_image.png";
        $imgData = null;

        if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['img']['tmp_name'];
            $fileType = mime_content_type($fileTmpPath);
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

            if (in_array($fileType, $allowedTypes)) {
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
                $newFileName = uniqid('hospital_') . '.' . $ext;
                $destPath = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $imgData = file_get_contents($destPath);
                    unlink($destPath);
                }
            }
        }

        if (!$imgData) {
            $imgData = file_get_contents("../" . $defaultImgPath);
        }

        $stmt = $conn->prepare("INSERT INTO hospital_list 
          (name, phone_no, img, hospital_state, hospital_city, hospital_area, tag_line, latitude, longitude, email, username, password) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssbssssddsss",
          $name, $phone_no, $null, $hospital_state, $hospital_city, $hospital_area,
          $tag_line, $latitude, $longitude, $email, $username, $password);

        $stmt->send_long_data(2, $imgData);

        if ($stmt->execute()) {
            header("Location: manageHospital.php");
            exit();
        } else {
            $error = "Database error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Add Hospital - নিরাময় Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f4f7fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    .container { max-width: 600px; margin: 40px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    h1 { margin-bottom: 24px; }
    label { font-weight: 600; }
    .btn-group { margin-top: 20px; }
    .error { color: red; margin-bottom: 15px; }
  </style>
</head>
<body>

<div class="container">
  <h1>Add New Hospital</h1>

  <?php if (!empty($error)): ?>
    <div class="error"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" novalidate>
    <div class="mb-3">
      <label for="name" class="form-label">Hospital Name *</label>
      <input type="text" id="name" name="name" class="form-control" required value="<?php echo htmlspecialchars($_POST['name'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label for="phone_no" class="form-label">Phone Number</label>
      <input type="text" id="phone_no" name="phone_no" class="form-control" value="<?php echo htmlspecialchars($_POST['phone_no'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label for="img" class="form-label">Hospital Image (optional)</label>
      <input type="file" id="img" name="img" class="form-control" accept="image/*">
    </div>

    <div class="mb-3">
      <label for="hospital_state" class="form-label">State</label>
      <input type="text" id="hospital_state" name="hospital_state" class="form-control" value="<?php echo htmlspecialchars($_POST['hospital_state'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label for="hospital_city" class="form-label">City</label>
      <input type="text" id="hospital_city" name="hospital_city" class="form-control" value="<?php echo htmlspecialchars($_POST['hospital_city'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label for="hospital_area" class="form-label">Area</label>
      <input type="text" id="hospital_area" name="hospital_area" class="form-control" value="<?php echo htmlspecialchars($_POST['hospital_area'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label for="tag_line" class="form-label">Tag Line</label>
      <input type="text" id="tag_line" name="tag_line" class="form-control" value="<?php echo htmlspecialchars($_POST['tag_line'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label for="latitude" class="form-label">Latitude</label>
      <input type="number" step="any" id="latitude" name="latitude" class="form-control" value="<?php echo htmlspecialchars($_POST['latitude'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label for="longitude" class="form-label">Longitude</label>
      <input type="number" step="any" id="longitude" name="longitude" class="form-control" value="<?php echo htmlspecialchars($_POST['longitude'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($_POST['email'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label for="username" class="form-label">Username *</label>
      <input type="text" id="username" name="username" class="form-control" required value="<?php echo htmlspecialchars($_POST['username'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">Password *</label>
      <input type="text" id="password" name="password" class="form-control" required value="<?php echo htmlspecialchars($_POST['password'] ?? '') ?>">
    </div>

    <div class="btn-group">
      <button type="submit" class="btn btn-primary">Add Hospital</button>
      <a href="manageHospital.php" class="btn btn-secondary">Back</a>
    </div>
  </form>
</div>

</body>
</html>
