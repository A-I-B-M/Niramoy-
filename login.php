<?php
include "Connection.php";

// ========================
// HOSPITAL LOGIN LOGIC START
// ========================
if (isset($_POST['adsub'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username1']);
    $password = mysqli_real_escape_string($conn, $_POST['password2']);

    $query = "SELECT * FROM hospital_list WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        session_start();
        $_SESSION['logged_hospital'] = $username;

        echo "<script>
            alert('Hospital login successful');
            window.location.href = 'Hospital_DashBord/dashboard.php';
        </script>";
        exit();
    } else {
        echo "<script>alert('Invalid hospital credentials');</script>";
    }
}


// ========================
// PATIENT REGISTRATION LOGIC START
// ========================
if (isset($_POST['patsub1'])) {
    $fname     = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname     = mysqli_real_escape_string($conn, $_POST['lname']);
    $email     = mysqli_real_escape_string($conn, $_POST['email']);
    $phone     = mysqli_real_escape_string($conn, $_POST['contact']);
    $password  = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
    $gender    = mysqli_real_escape_string($conn, $_POST['gender']);

    if ($password !== $cpassword) {
        echo "<script>alert('Passwords do not match'); window.history.back();</script>";
        exit();
    }

    $checkEmailQuery = "SELECT * FROM patient_list WHERE email='$email'";
    $checkResult = mysqli_query($conn, $checkEmailQuery);
    if (mysqli_num_rows($checkResult) > 0) {
        echo "<script>alert('Email already registered!'); window.history.back();</script>";
        exit();
    }

    $query = "INSERT INTO patient_list (first_name, last_name, email, phone_no, password, gender)
              VALUES ('$fname', '$lname', '$email', '$phone', '$password', '$gender')";

    if (mysqli_query($conn, $query)) {
        echo "<script>
            alert('Patient registered successfully!');
            window.location.href = 'patientLogin.php';
        </script>";
    } else {
        echo "<script>
            alert('Error registering patient: " . mysqli_error($conn) . "');
        </script>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>নিরাময়</title>

    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png" />
    <link rel="stylesheet" type="text/css" href="login.css">
    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

    <style>
        .form-control {
            border-radius: 0.75rem;
        }
    </style>

    <script>
        var check = function() {
            if (document.getElementById('password').value == document.getElementById('cpassword').value) {
                document.getElementById('message').style.color = '#5dd05d';
                document.getElementById('message').innerHTML = 'Matched';
            } else {
                document.getElementById('message').style.color = '#f55252';
                document.getElementById('message').innerHTML = 'Not Matching';
            }
        }

        function alphaOnly(event) {
            var key = event.keyCode;
            return ((key >= 65 && key <= 90) || key == 8 || key == 32);
        };

        function checklen() {
            var pass1 = document.getElementById("password");
            if (pass1.value.length < 6) {
                alert("Password must be at least 6 characters long. Try again!");
                return false;
            }
        }
    </script>
</head>

<body style="background-color: #00c6ff;position:relative;">

    <!-- ====================== NAVBAR START ====================== -->
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
                        <a class="nav-link" href="index.php">
                            <h6 style="text-decoration: none;
    color: #000000aa;
    font-weight: 900;
    font-family: 'Lucida Sans';font-size : 16px">Home</h6>
                        </a>
                    </li>
                    <li class="nav-item" style="margin-right: 40px;">
                        <a class="nav-link" href="hospitals.php">
                            <h6 style="text-decoration: none;
    color: #000000aa;
    font-weight: 900;
    font-family: 'Lucida Sans';font-size : 16px">Hospitals</h6>
                        </a>
                    </li>
                    <li class="nav-item" style="margin-right: 40px;">
                        <a class="nav-link" href="Blogs/blog.php">
                            <h6 style="text-decoration: none;
    color: #000000aa;
    font-weight: 900;
    font-family: 'Lucida Sans';font-size : 16px">Blog</h6>
                        </a>
                    </li>
                    <li class="nav-item" style="margin-right: 40px;">
                        <a class="nav-link" href="Sample_Collection/sampleCollection.php">
                            <h6 style="text-decoration: none;
    color: #000000aa;
    font-weight: 900;
    font-family: 'Lucida Sans';font-size : 16px">Sample Collection</h6>
                        </a>
                    </li>
                    <li class="nav-item" style="margin-right: 40px;">
                        <a class="nav-link" href="login.php">
                            <h6 style="text-decoration: none;
    color: #000000aa;
    font-weight: 900;
    font-family: 'Lucida Sans';font-size : 16px">Login</h6>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ====================== MAIN CONTENT START ====================== -->
    <div class="container register" style="font-family: 'IBM Plex Sans', sans-serif;background-color: #00c6ff;position:relative;">
        <div class="row" style="margin-right: 80px;">
            <div class="col-md-3 register-left" style="margin-top: 20%;right: 5%">
                <h3>Welcome to নিরাময়</h3>
            </div>

            <div class="col-md-9 register-right" style="margin-top: 40px;left: 80px;">
                <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist" style="width: 80%;">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home">Patient</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile">Doctor</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#hospital">Hospital</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#admin">Admin</a>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">

                    <!-- ====================== PATIENT REGISTER START ====================== -->
                    <div class="tab-pane fade show active" id="home">
                        <h3 class="register-heading">Register as Patient</h3>
                        <form method="post" action="login.php">
                            <div class="row register-form">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="First Name *" name="fname" onkeydown="return alphaOnly(event);" required />
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control" placeholder="Your Email *" name="email" />
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" placeholder="Password *" id="password" name="password" onkeyup='check();' required />
                                    </div>
                                    <div class="form-group">
                                        <div class="maxl">
                                            <label class="radio inline">
                                                <input type="radio" name="gender" value="Male" checked> <span> Male </span>
                                            </label>
                                            <label class="radio inline">
                                                <input type="radio" name="gender" value="Female"> <span>Female </span>
                                            </label>
                                        </div>
                                        <a href="patientLogin.php">Already have an account?</a>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Last Name *" name="lname" onkeydown="return alphaOnly(event);" required />
                                    </div>
                                    <div class="form-group">
                                        <input type="tel" minlength="10" maxlength="10" name="contact" class="form-control" placeholder="Your Phone *" />
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" id="cpassword" placeholder="Confirm Password *" name="cpassword" onkeyup='check();' required />
                                        <span id='message'></span>
                                    </div>
                                    <input type="submit" class="btnRegister" name="patsub1" onclick="return checklen();" value="Register" />
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- ====================== DOCTOR LOGIN START ====================== -->
                    <div class="tab-pane fade" id="profile">
                        <h3 class="register-heading">Login as Doctor</h3>
                        <form method="post" action="doctorLogin.php">
                            <div class="row register-form">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="tel" class="form-control" placeholder="Phone Number *" name="username3" pattern="[0-9]{10,15}" title="Enter valid phone number" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="password" class="form-control" placeholder="Password *" name="password3" required />
                                    </div>
                                    <input type="submit" class="btnRegister" name="docsub1" value="Login" />
                                </div>
                            </div>
                        </form>
                    </div>


                    <!-- ====================== HOSPITAL LOGIN START ====================== -->
                    <div class="tab-pane fade" id="hospital">
                        <h3 class="register-heading">Login as Hospital</h3>
                        <form method="POST" action="hospitalLogin.php">
                            <div class="row register-form">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="User Name *" name="username1" onkeydown="return alphaOnly(event);" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="password" class="form-control" placeholder="Password *" name="password2" required />
                                    </div>
                                    <input type="submit" class="btnRegister" name="adsub" value="Login" />
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- ====================== ADMIN LOGIN START ====================== -->
                    <div class="tab-pane fade" id="admin">
                        <h3 class="register-heading">Login as Admin</h3>
                        <form method="post" action="adminLogin.php">
                            <div class="row register-form">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Username *" name="username" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="password" class="form-control" placeholder="Password *" name="password" required />
                                    </div>
                                    <input type="submit" class="btnRegister" name="adminsub" value="Login" />
                                </div>
                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</body>

</html>