<?php
    session_start();
    include('config/database.php');
    if(isset($_POST['signIn'])){
        $userId = $_POST['userId'];
        $password = md5($_POST['password']);

        $sql ="SELECT * FROM users where userId ='$userId' AND password = '$password' ";
        $query= mysqli_query($conn, $sql);
        $count = mysqli_num_rows($query);
        if($count > 0)
        {
            while ($user = mysqli_fetch_assoc($query)) {
                if ($user['role'] == 'Admin') {
                    $_SESSION['alogin'] = $user['_id'];
                    $_SESSION['arole'] = $user['userId'];
                    echo "<script type='text/javascript'> document.location = 'admin/index.php'; </script>";
                }
                elseif ($user['role'] == 'Lecturer') {
                    $_SESSION['alogin'] = $user['_id'];
                    $_SESSION['arole'] = $user['userId'];
                    echo "<script type='text/javascript'> document.location = 'lecturer/index.php'; </script>";
                }
                else {
                    $_SESSION['alogin'] = $user['_id'];
                    $_SESSION['arole'] = $user['userId'];
                    echo "<script type='text/javascript'> document.location = 'student/index.php'; </script>";
                }
            }

        } 
        else{
        
        echo "<script>alert('Invalid Details');</script>";

        }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Classroom</title>
    <!----===== Boxicons CSS ===== -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <main class="main">
        <div class="login_img">
            <img src="./login_img.jpg" alt="">
        </div>
        <div class="login_container">
            <div class="head">
                <div class="div">
                    <i class="fas fa-laptop icon"></i>
                    <h2>E-Classroom</h2>
                </div>
                <p>Login to your account</p>
            </div>
            <form action="" method="POST" class="form">
                <div class="form_group">
                    <label for="email">UserId</label>
                    <input type="text" name="userId" id="userId" placeholder="Enter your userId" required>
                </div>
                <div class="form_group pass">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <i class="fas fa-eye  fa-eye-slash icon toggle"></i>
                </div>
                <div class="form_group">
                <button type="submit" name="signIn" class="login_btn">Login</button>
                </div>
            </form>
            <footer class="footer">
                <p>Copyright &copy; Mic__Dev :: Limkokwing University SL</p>
            </footer>
        </div>
    </main>
   

    <!-- script -->
    <script src="script.js"></script>
</body>
</html>
