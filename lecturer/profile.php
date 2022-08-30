<?php require_once('./includes/header.php'); ?>
<?php require_once('../config/session.php'); ?>

<?php 
    
    $selectQuery = "SELECT * FROM users WHERE _id = '$session_id' ";
    $result = mysqli_query($conn, $selectQuery);
    $student = mysqli_fetch_array($result);
    // 
?>

<?php
    $query = "SELECT *, programName, facultyName, classCode FROM users INNER JOIN classroomlecturers ON users._id = classroomlecturers.lecturer_id INNER JOIN classrooms ON classroomlecturers.class_id = classrooms._id INNER JOIN programs ON classrooms.program = programs.programCode INNER JOIN faculties ON classrooms.faculty = faculties.facultyCode WHERE users._id = '$session_id' ";

    $result = mysqli_query($conn, $query);

    $profile = mysqli_fetch_array($result);
    // print_r($profile);
?>

<?php 
    // $userId = $fullname = $email = $password = $role = $gender =  "";

    // Check if form is submit
    if(isset($_POST['submit'])) {
        // echo 'working';
        // Validating
        $userId = filter_input(INPUT_POST, 'userId', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $fullname = filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        
        $gender = $_POST['gender'];
        $role = $_POST['role'];

        // query string
       
        // Update user
        $update = "UPDATE users SET userId = '$userId', fullname = '$fullname', email = '$email', gender = '$gender' where _id = '$session_id' ";

        // mysqli_query($conn, $insert);
        if(mysqli_query($conn, $update)) {
            header('Location: profile.php');
        }
        else {
            echo "Error" . mysqli_error($conn);
        }
        
    }
?>

<?php
    if(isset($_POST['save'])) {
        $allowed_ext = array('png', 'jpg', 'jpeg');

        if(!empty($_FILES['upload']['name'])) {
            $file_name = $_FILES['upload']['name'];
            $file_size = $_FILES['upload']['size'];
            $file_temp = $_FILES['upload']['tmp_name'];

            // Making unique file name to aviod overriding
            $unique_filename = rand(100, 1000).'_'. $file_name;

            $upload_dir = "../uploads/${unique_filename}";

            $file_ext = explode('.', $unique_filename);

            $file_ext = strtolower(end($file_ext));

            // validate file ext
            if(in_array($file_ext, $allowed_ext)) {
                if($file_size <= 3000000)  {
                    move_uploaded_file($file_temp, $upload_dir);
                    // Update
                    $update = "UPDATE users SET photo = '$unique_filename' where _id = '$session_id' ";

                    // mysqli_query($conn, $insert);
                    if(mysqli_query($conn, $update)) {
                        header('Location: profile.php');
                    }
                    else {
                        echo "Error" . mysqli_error($conn);
                    }
                }
                else {
                    $message[] = 'File too large';
                }
            }
            else {
                $message[] = 'Invalid file ext';
            }
        
        } 
        else {
            $message[] = 'Please choose an image';
        }
        
    }
?>

<body>
<?php require_once('./includes/topNav.php'); ?>
    <main class="container">
    <?php require_once('./includes/sideNav.php'); ?>
        <div class="home" id="dashboard">
            <div class="_container">
                <div class="_container_">
                    <?php if(isset($_GET['user'])) : ?>
                        <div class="modal_container">
                            <div class="modal_content">
                                <?php if(isset($message)): ?>
                                    <?php foreach($message as $msg): ?>
                                        <p class="error"><?php echo $msg; ?></p>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="form_group">
                                        <input type="file" name="upload">
                                    </div>
                                    <div class="btns">
                                        <button class="save_btn" name="save">Save</button>
                                        <button class="cancel_btn">
                                            <a href="profile.php">
                                                Cancel
                                            </a>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="profile">
                        <div class="profile_left">
                            <div class="profile_head">
                                <div class="user_photo">
                                <img src="<?php echo (!empty($user['photo'])) ? '../uploads/'.$user['photo'] : '../uploads/NO-IMAGE-AVAILABLE.jpg'; ?>" alt="user-img">
                                    <a href="profile.php?user=<?php echo $session_id; ?>" class="edit_photo">
                                        <i class="fa-solid fa-pencil icon"></i>
                                    </a>
                                </div>
                                <div class="user_content">
                                    <h4><?php echo $profile['fullname']; ?></h4>
                                    <p><?php echo $profile['facultyName']; ?></p>
                                </div>
                            </div>
                            <div class="info_container">
                                <h2>Information</h2>
                                <div class="info_content">
                                    <h4>UserID:</h4>
                                    <p><p><?php echo $profile['userId']; ?></p></p>
                                </div>
                                <div class="info_content">
                                    <h4>Email:</h4>
                                    <p><p><?php echo $profile['email']; ?></p></p>
                                </div>
                                <div class="info_content">
                                    <h4>Gender:</h4>
                                    <p><p><?php echo $profile['gender']; ?></p></p>
                                </div>
                                <div class="info_content">
                                    <h4>Role:</h4>
                                    <p><p><?php echo $profile['role']; ?></p></p>
                                </div>
                            </div>
                        </div>
                        
                    
                        <form action="" method="POST" enctype="multipart/form-data" class="form" >
                            <h2>Edit Personal Info</h2>
                            <div class="form_container">
                                <div class="form_group">
                                    <label for="userId">UserId</label>
                                    <input type="text" name="userId" placeholder="Enter your userId" value="<?php echo $profile['userId']; ?>">
                                </div>
                                <div class="form_group">
                                    <label for="fullname">Fullname</label>
                                    <input type="text" name="fullname" placeholder="Enter your fullname" value="<?php echo $profile['fullname']; ?>">
                                </div>
                                <div class="form_group">
                                    <label for="">Email</label>
                                    <input type="email" name="email" placeholder="Enter your email" value="<?php echo $profile['email']; ?>">
                                </div>
                                <div class="form_group">
                                    <label for="gender">Gender</label>
                                    <select name="gender">
                                    <option value="<?php echo $profile['gender']; ?>">Male</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form_group">
                                <button name="submit" class="update_btn">Update pofile</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php require_once('./includes/footer.php'); ?>