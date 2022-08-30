<?php require_once('./includes/header.php'); ?>
<?php require_once('../config/session.php')?>

<?php
    $users_per_page = 4;
    // Getting data from users table
    $selectQuery = "SELECT *, class_code FROM users INNER JOIN classroomstudents ON users._id = classroomstudents.student_id WHERE users.role = 'Student' ";
    
    $result = mysqli_query($conn, $selectQuery);

    // number of users
    $num_of_users = mysqli_num_rows($result);
    // number of page 
    $num_of_pages = ceil($num_of_users / $users_per_page);
    // echo $num_of_pages;

    // determine current page
    if(!isset($_GET['page'])) {
        // set page to 1
        $page = 1;
    }
    else {
        // set page to the current page
        $page = $_GET['page'];
    }

    // determine output limit per page
    $start = ($page - 1) * $users_per_page;

    // Getting specify data from users table :: using the LIMIT
    $selectQuery = "SELECT *, class_code FROM users INNER JOIN classroomstudents ON users._id = classroomstudents.student_id WHERE users.role = 'Student' LIMIT $start, $users_per_page";
    
    $result = mysqli_query($conn, $selectQuery);

    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // print_r($users);

?>


<!-- load -->
<?php
    if(isset($_POST['save'])) {
        $get__id = $_GET['student']; 
        $query_ = "SELECT * FROM users WHERE _id = '$get__id'";
        $result_ = mysqli_query($conn, $query_) or die(mysqli_error($conn));
        $student = mysqli_fetch_array($result_);

        $class_id = $_POST['classroom_id'];

        $query_ = "SELECT * FROM classrooms WHERE _id = '$class_id'";
        $result_ = mysqli_query($conn, $query_) or die(mysqli_error($conn));
        $classroom = mysqli_fetch_array($result_);

       
        $class_code = $classroom['classCode'];

        $student_id = $_GET['student'];
        $studentID = $student['userId'];

        // echo $class_id . ''. $class_code .'' .$student_id . '' . $studentID ."<br>";

        $query = "SELECT * FROM classroomstudents WHERE student_id = '$student_id' ";

            // Called the mysqli_query() method
        $result = mysqli_query($conn, $query);
        
        // check if user already exist
        if (mysqli_num_rows($result) > 0){
            $err = "Data Already Exist";
        }
        else {
            $insert = "INSERT INTO classroomstudents (class_id, class_code, student_id, studentID) VALUES('$class_id', '$class_code', '$student_id', '$studentID')";
            $result = mysqli_query($conn, $insert);
        }
        header('Location: students.php');  
        
    }

?>


<!-- Delete user -->
<?php 
    if (isset($_GET['delete'])) {
        $get_id = $_GET['delete'];

        $delete = "DELETE FROM users WHERE _id = '$get_id' ";
        
        // mysqli_query();
        if(mysqli_query($conn, $delete)) {
            header('Location: users.php');
        }
        else {
            echo "Error" . mysqli_error($conn);
        }
    }


?>

<?php
    // Get total registered users
    $query = "SELECT * FROM users WHERE role = 'student' ";
    $result = mysqli_query($conn, $query);
    $total_users = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<body>
    <?php require_once('./includes/topNav.php'); ?>
    <main class="container">
        <?php require_once('./includes/sideNav.php'); ?>
        <div class="home_" id="users">
            <!-- modal -->
            <?php if(isset($_GET['student'])) : ?>
                <div class="modal_container">
                    <div class="modal_content">
                        <h3>Load Classrooms</h3>
                        <form action="" method="POST">
                            <div class="form_group">
                                <select name="classroom_id" id="">
                                    <option value="">Select Classroom</option>
                                    <?php
                                        $query = "SELECT * FROM classrooms";
                                        $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
                                        while($classroom = mysqli_fetch_array($result)){
                                            ?>
                                                <option value="<?php echo $classroom['_id']; ?>"><?php echo $classroom['classCode']; ?></option>
                                            <?php 
                                        } 
                                    ?>
                                </select> 
                            </div>
                            <div class="btns">
                                <button class="save_btn" name="save">Save</button>
                                <button class="cancel_btn">
                                    <a href="students.php">
                                        Cancel
                                    </a>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
            <div class="home_container">
                <div class="list_container __listContainer">
                    <p class="show_total">Showing <?php echo count($total_users); ?> students</p>
                    <div class="_head">
                        <div class="search">
                            <input type="text" class="search_filter" placeholder="search">
                        </div>
                    </div>
                    <div class="list_head_">
                        <h3>_Id</h3>
                        <h3>Photo</h3>
                        <h3>StudentId</h3>
                        <h3>Fullname</h3>
                        <h3>Current Class</h3>
                        <h3>Actions</h3>
                    </div>
                        <!-- loop through users array -->
                        <?php foreach($users as $user): ?>
                            <div class="list_">
                                <p><?php echo $user['_id'];?></p>
                                <img src="<?php echo (!empty($user['photo'])) ? '../uploads/'.$user['photo'] : '../uploads/NO-IMAGE-AVAILABLE.jpg'; ?>" class="user_photo" alt="user-photo" />
                                <p><?php echo $user['userId'];?></p>
                                <p class="name"><?php echo $user['fullname'];?></p>
                                <p class=""><?php echo(!empty($user['class_code'])) ? $user['class_code'] : 'Not Yet' ?></p>
                                <div class="action">
                                    <!-- <i class="fas fa-eye icon"></i> -->
                                    <a href="students.php?student=<?php echo $user['_id'];?>">
                                        <i class="fas fa-spinner icon"></i>
                                        <span class="tooltip">Load Classroom</span>
                                    </a>
                                    <a href="editUser.php?edit=<?php echo $user['_id'];?>">
                                        <i class="fas fa-user-pen icon"></i>
                                        <span class="tooltip">Edit</span>
                                    </a>
                                    <a href="students.php?delete=<?php echo $user['_id'] ?>">
                                        <i class="fas fa-trash-can icon trash"></i>
                                        <span class="tooltip delete">Delete</span>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <!-- pagination -->
                    <div class="pagination">
                        <?php
                            if($page > 1) { ?>
                                <p>
                                    <a href="students.php?page=<?php echo ($page - 1); ?>">
                                        Previous
                                    </a>
                                </p>
                            <?php }
                            else { ?>
                                <p>
                                    <a href="students.php?page=<?php echo $page; ?>">
                                        Previous
                                    </a>
                                </p>
                            <?php }
                        ?>
                        
                            
                        
                        <?php for($page = 1; $page <= $num_of_pages; $page++): ?>
                            <p>
                                <a href="students.php?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                            </p>
                        <?php endfor; ?>

                        <?php if($page = $page) :?>
                            <p>
                                <a href="students.php?page=<?php echo ($page - 1); ?>">
                                    Next
                                </a>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php require_once('./includes/footer.php'); ?>