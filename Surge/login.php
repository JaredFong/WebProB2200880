<?php
session_start();

if (isset($_POST['btn_login'])) {
    $username = trim($_POST['txt_username']);
    $password = trim($_POST['txt_password']);

    if ($username != '' && $password != '') {
        include 'dbconnect.php';

        if ($dbc->connect_error) {
            die("Connection failed: " . $dbc->connect_error);
        }

        $stmt = $dbc->prepare("SELECT * FROM user WHERE username = ? AND password = ?");
        
        if ($stmt === false) {
            die("Prepare failed: " . $dbc->error);
        }

        $stmt->bind_param('ss', $username, $password);
        
        if ($stmt->execute() === false) {
            die("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            $_SESSION['id'] = $row['id'];
            $_SESSION['user_role'] = $row['role'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_email'] = $row['username'];

            if ($_SESSION['user_role'] == 'Admin') {
                echo "<script>alert('Login Successful, Welcome!!'); window.location.href='admin/index.php';</script>";
            } elseif ($_SESSION['user_role'] == 'User') {
                echo "<script>alert('Login Successful, Welcome!!'); window.location.href='index.php';</script>";
            }
        } else {
            echo "<script>alert('Please check your Username and Password!!'); window.location.href='login.php';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Please enter your Username and Password!!'); window.location.href='login.php';</script>";
    }

    $dbc->close();
}
?>


<?php

if (isset($_POST['btn_signup'])) {

    $name = trim($_POST['txt_name']);
    $email = trim($_POST['txt_email']);
    $password = trim($_POST['txt_password']);
    $confirmpassword = trim($_POST['txt_confirmpassword']);

    $target_dir = "img/profile/";
    $filename = basename($_FILES["images"]["name"]);
    $target_file = $target_dir . $filename;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($password == $confirmpassword) {
        include 'dbconnect.php';

        $check = getimagesize($_FILES["images"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            

            $stmt = $dbc->prepare("INSERT INTO user (username, password, name, profile_image, role) VALUES (?, ?, ?, ?, 'User')");
            $stmt->bind_param('ssss', $email, $password, $name, $filename);
            $stmt->execute();

            if (move_uploaded_file($_FILES["images"]["tmp_name"], $target_file)) {
                echo "<script>alert('User registration successfully saved'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Sorry, there was an error uploading your file'); window.location.href='login.php';</script>";
            }
        } else {
            echo "<script>alert('Sorry, your file was not uploaded.'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('Password and Confirm Password do not match!!'); window.location.href='login.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="css/style3.css">
    <title>Login & Registration Form</title>
    <style>
        .modal {
            display: none;
            position: fixed;
            background: transparent;
            border: 2px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            padding-top: 10px;
            margin: 10px 0px;
        }

        .modal-content {
            max-width: 900px;
            width: 100%;
            height: 300px;
            overflow: auto;
            background: transparent;
            margin: 2% auto;
            padding: 10px 10px 10px 10px;
            width: 85%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            padding-right: 20px;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="cursor"></div>
    <button class="home-button">Return to Home</button>
    <div class="container">
        <div class="forms">
            <div class="form login">
                <span class="title">Login</span>
                <form action="" method="post">
                    <div class="input-field">
                        <input type="email" name="txt_username" placeholder="Enter your email" required>
                        <i class="uil uil-envelope icon"></i>
                    </div>
                    <div class="input-field">
                        <input type="password" name="txt_password" class="password" placeholder="Enter your password" required>
                        <i class="uil uil-lock icon"></i>
                        <i class="uil uil-eye-slash showHidePw"></i>
                    </div>
                    <div class="checkbox-text">
                        <div class="checkbox-content">
                            <input type="checkbox" id="logCheck">
                            <label for="logCheck" class="text">Remember me</label>
                        </div>
                    </div>
                    <div class="input-field button">
                        <input type="submit" name="btn_login" value="Login">
                    </div>
                </form>
                <div class="login-signup">
                    <span class="text">Not a member?
                        <a href="#" class="text signup-link">Sign up Now</a>
                    </span>
                </div>
            </div>
            <div class="form signup">
                <span class="title">Registration</span>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="input-field">
                        <input type="text" name="txt_name" placeholder="Enter your name" required>
                        <i class="uil uil-user"></i>
                    </div>
                    <div class="input-field">
                        <input type="email" name="txt_email" placeholder="Enter your email" required>
                        <i class="uil uil-envelope icon"></i>
                    </div>
                    <div class="input-field">
                        <input type="password" name="txt_password" class="password" placeholder="Create a password" required>
                        <i class="uil uil-lock icon"></i>
                    </div>
                    <div class="input-field">
                        <input type="password" name="txt_confirmpassword" class="password" placeholder="Confirm a password" required>
                        <i class="uil uil-lock icon"></i>
                        <i class="uil uil-eye-slash showHidePw"></i>
                    </div>
                    <div class="input-field">
                        <input type="file" class="form-control-file" id="images" name="images" accept="image/*" required>
                        <i class="uil uil-user-square icon"></i>
                    </div>
                    <div class="checkbox-text">
                        <div class="checkbox-content">
                            <a id="termCon1" href="#" style="text-decoration:none">Please read terms of service before Signup</a>
                        </div>
                    </div>

                    <div class="input-field button">
                        <input type="submit" name="btn_signup" value="Signup" id="signupButton"  style="display:none;" >
                    </div>
                </form>
                <div class="login-signup">
                    <span class="text">Already a member?
                        <a href="#" class="text login-link">Login Now</a>
                    </span>
                </div>
            </div>
        </div>
    </div>
    

    <?php include 'modal_TC.php' ?>

    <script src="js/jsscript.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modal = document.getElementById("termsModal");
            var termLink = document.getElementById("termCon1");
            var termChbx = document.getElementById("termCon");
            var signupBtn = document.querySelector("input[name='btn_signup']");

            termLink.addEventListener('click', function(event) {
                event.preventDefault();
                modal.style.display = "block";
            });

            document.querySelector(".close").addEventListener('click', function() {
                modal.style.display = "none";
                termChbx.checked = false;
                //updateSignupButton();
            });

            termChbx.addEventListener('change', updateSignupButton);

            function updateSignupButton() {
                signupBtn.style.display = termChbx.checked ? "block" : "none";
            }

            updateSignupButton();
        });

    </script>

</body>
</html>
