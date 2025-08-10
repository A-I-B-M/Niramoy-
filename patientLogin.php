<?php
session_start();
include "Connection.php";

if (isset($_POST['patsub'])) {
    $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $password = trim(mysqli_real_escape_string($conn, $_POST['password2']));

    $query = "SELECT * FROM patient_list WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        echo "<script>alert('Database error.'); window.history.back();</script>";
        exit();
    }
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        if (!isset($row['id'])) {
            echo "<script>alert('ID not found in result!'); window.history.back();</script>";
            exit();
        }

        $_SESSION['patient_logged_in'] = true;
        $_SESSION['patient_name'] = $row['first_name'] . " " . $row['last_name'];
        $_SESSION['patient_email'] = $email;
        $_SESSION['patient_id'] = $row['id']; 

        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('Invalid email or password!'); window.history.back();</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Patient Login - নিরাময়</title>

    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="patientLogin.css">

    <style type="text/css">
        #inputbtn:hover { cursor:pointer; }
        .card {
            background: #f8f9fa;
            border-radius: 5%;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav" style="background-color: white;">
    <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="#" style="margin-top: 10px;margin-left:-65px;font-family: 'IBM Plex Sans', sans-serif;">
            <h4 style="text-decoration: none;
    color: #00c6ff;
    font-weight: 900;
    font-family: 'Lucida Sans';font-size : 30px"><i class="fa fa-user-plus" aria-hidden="true"></i>&nbsp নিরাময়</h4>
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item" style="margin-right: 40px;">
                    <a class="nav-link" href="index.php"><h6 style="text-decoration: none;
    color: #000000aa;
    font-weight: 900;
    font-family: 'Lucida Sans';font-size : 16px">Home</h6></a>
                </li>
                <li class="nav-item" style="margin-right: 40px;">
                    <a class="nav-link" href="hospitals.php"><h6 style="text-decoration: none;
    color: #000000aa;
    font-weight: 900;
    font-family: 'Lucida Sans';font-size : 16px">Hospitals</h6></a>
                </li>
                <li class="nav-item" style="margin-right: 40px;">
                    <a class="nav-link" href="Blogs/blog.php"><h6 style="text-decoration: none;
    color: #000000aa;
    font-weight: 900;
    font-family: 'Lucida Sans';font-size : 16px">Blog</h6></a>
                </li>
                <li class="nav-item" style="margin-right: 40px;">
                    <a class="nav-link" href="Sample_Collection/sampleCollection.php"><h6 style="text-decoration: none;
    color: #000000aa;
    font-weight: 900;
    font-family: 'Lucida Sans';font-size : 16px">Sample Collection</h6></a>
                </li>
                <li class="nav-item" style="margin-right: 40px;">
                    <a class="nav-link" href="login.php"><h6 style="text-decoration: none;
    color: #000000aa;
    font-weight: 900;
    font-family: 'Lucida Sans';font-size : 16px">Login</h6></a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid" style="margin-top:60px;margin-bottom:60px;color:#34495E;">
    <div class="row">

        <div class="col-md-7" style="padding-left: 180px;">
            <div style="-webkit-animation: mover 2s infinite alternate; animation: mover 1s infinite alternate;">
            </div>

            <div class="col-md-3 register-left" style="margin-top: 40%">
                <h3>Welcome to নিরাময়</h3>
            </div>
        </div>

        <div class="col-md-4" style="margin-top: 5%; right: 8%">
            <div class="card" style="font-family: 'IBM Plex Sans', sans-serif;">
                <div class="card-body">
                    <center>
                        <i class="fa fa-hospital-o fa-3x" aria-hidden="true" style="color:#0062cc"></i><br>
                        <h3 style="margin-top: 10%">Patient Login</h3><br>

                        
                        <form class="form-group" method="POST" action="">
                            <div class="row" style="margin-top: 10%">
                                <div class="col-md-4"><label>Email-ID: </label></div>
                                <div class="col-md-8"><input type="text" name="email" class="form-control" placeholder="Enter email ID" required/></div><br><br>
                                <div class="col-md-4" style="margin-top: 8%"><label>Password: </label></div>
                                <div class="col-md-8" style="margin-top: 8%"><input type="password" class="form-control" name="password2" placeholder="Enter password" required/></div><br><br><br>
                            </div>
                            <div class="row">
                                <div class="col-md-4" style="padding-left: 160px;margin-top: 10%">
                                    <center><input type="submit" id="inputbtn" name="patsub" value="Login" class="btn btn-primary"></center>
                                </div>
                            </div>
                        </form>

                    </center>
                </div>
            </div>
        </div>

    </div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" crossorigin="anonymous"></script>

</body>
</html>
