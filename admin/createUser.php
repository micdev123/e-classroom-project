<?php require_once('./includes/header.php'); ?>
<?php require_once('../config/session.php')?>


<?php 
    $userId = $fullname = $email = $password = $role = $gender =  "";

    // Check if form is submit
    if(isset($_POST['submit'])) {
        // echo 'working';
        // Validating
        empty($_POST['userId']) ? $idErr = "field required" : $userId = filter_input(INPUT_POST, 'userId', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        empty($_POST['fullname']) ? $nameErr = "field required" : $fullname = filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        empty($_POST['email']) ? $emailErr = "field required" : $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

        empty($_POST['password']) ? $passErr = "field required" : $password = md5(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        
        $gender = $_POST['gender'];
        $role = $_POST['role'];

        // query string
        $query = "SELECT * FROM users WHERE email = '$email' ";
        // Called the mysqli_query() method
        $result = mysqli_query($conn, $query);
        
        // check if user already exist
        if (mysqli_num_rows($result) > 0){
            $err = "User Already Exist";
        }
        else {
            // Add to users table in database
            $insert = "INSERT INTO users (userId, fullname, email, password, gender, role, photo) VALUES('$userId', '$fullname', '$email', '$password', '$gender', '$role', 'NO-IMAGE-AVAILABLE.jpg')";

            // mysqli_query($conn, $insert);
            if(mysqli_query($conn, $insert)) {
                header('Location: users.php');
            }
            else {
                echo "Error" . mysqli_error($conn);
            }
        }

    }
?>

<body>
    <?php require_once('./includes/topNav.php'); ?>
    <main class="container">
        <?php require_once('./includes/sideNav.php'); ?>
        <div class="home" id="users">
            <div class="home_container">
                <?php if(isset($err)): ?>
                    <p class="error"><?php echo $err; ?></p>
                <?php endif; ?>
                
                <div class="list_container">
                    <button class="back_btn" id="open_modal">
                        <a href="./users.php">
                            Back
                        </a>
                    </button>
                    <div class="create_">
                        <form action="" method="POST" enctype="multipart/form-data" class="form" >
                            <div class="form_container">
                                <div>
                                    <div class="form_group">
                                        <label for="userId">UserId</label>
                                        <input type="text" name="userId" placeholder="Enter userId">
                                        <?php if(isset($idErr)): ?>
                                            <p class="error"><?php echo $idErr; ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form_group">
                                        <label for="fullname">Fullname</label>
                                        <input type="text" name="fullname" placeholder="Enter fullname">
                                        <?php if(isset($nameErr)): ?>
                                            <p class="error"><?php echo $nameErr; ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form_group">
                                        <label for="">Email</label>
                                        <input type="email" name="email" placeholder="Enter email">
                                        <?php if(isset($emailErr)): ?>
                                            <p class="error"><?php echo $emailErr; ?></p>
                                        <?php endif; ?>
                                    </div>
                                    
                                </div>
                                <div>
                                    <div class="form_group">
                                        <label for="password">Password</label>
                                        <input type="password" name="password" placeholder="Enter password">
                                        <?php if(isset($passErr)): ?>
                                            <p class="error"><?php echo $passErr; ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form_group">
                                        <label for="gender">Gender</label>
                                        <select name="gender">
                                            <option value="">Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="form_group">
                                        <label for="status">Role</label>
                                        <select name="role" id="">
                                            <option value="">Select Role</option>
                                            <option value="Student">Student</option>
                                            <option value="Lecturer">Lecturer</option>
                                            <option value="Admin">Admin</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form_group">
                                <button name="submit" class="create_btn">Create new user</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php require_once('./includes/footer.php'); ?>
