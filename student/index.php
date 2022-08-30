<?php require_once('./includes/header.php'); ?>
<?php require_once('../config/session.php'); ?>

<?php 
    
    $selectQuery = "SELECT * FROM users WHERE _id = '$session_id' ";
    $result = mysqli_query($conn, $selectQuery);
    $student = mysqli_fetch_array($result);
    // 
?>

<?php
    $data_per_page = 5;
    // class_id
    $query = "SELECT * FROM classroomstudents WHERE student_id = '$session_id' ";
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $class_id = mysqli_fetch_array($result);

    $classId = $class_id['class_id'];
    // echo $classId;

    
    $selectQuery = "SELECT programName, classCode, moduleName, module_id, photo, fullname FROM classmodules INNER JOIN classroomstudents ON classmodules.class_id = classroomstudents.class_id INNER JOIN modules ON classmodules.module_id = modules._id INNER JOIN programclassrooms ON classmodules.class_id = programclassrooms.class_id INNER JOIN programs ON programclassrooms.program_id = programs._id INNER JOIN classrooms ON classmodules.class_id = classrooms._id INNER JOIN classroomlecturers ON classrooms._id = classroomlecturers.class_id INNER JOIN users ON classroomlecturers.lecturer_id = users._id  WHERE classroomstudents.student_id = '$session_id' ";
    
    $result = mysqli_query($conn, $selectQuery);

    // number of users
    $num_of_data = mysqli_num_rows($result);
    // number of page 
    $num_of_pages = ceil($num_of_data / $data_per_page);
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
    $start = ($page - 1) * $data_per_page;

    // Getting specify data from users table :: using the LIMIT
    $selectQuery = "SELECT programName, classCode, moduleName, module_id, photo, fullname FROM classmodules INNER JOIN classroomstudents ON classmodules.class_id = classroomstudents.class_id INNER JOIN modules ON classmodules.module_id = modules._id INNER JOIN programclassrooms ON classmodules.class_id = programclassrooms.class_id INNER JOIN programs ON programclassrooms.program_id = programs._id INNER JOIN classrooms ON classmodules.class_id = classrooms._id INNER JOIN classroomlecturers ON classrooms._id = classroomlecturers.class_id INNER JOIN users ON classroomlecturers.lecturer_id = users._id  WHERE classroomstudents.student_id = '$session_id' LIMIT $start, $data_per_page";
    
    $result = mysqli_query($conn, $selectQuery);

    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // print_r($data);

    // $_id = 1;

?>


<body>
    <?php require_once('./includes/topNav.php'); ?>
    <main class="container">
        <?php require_once('./includes/sideNav.php'); ?>
        <div class="home" id="dashboard">
            <div class="_container">
                <h2>My Classrooms</h2>
                <div class="search">
                    <input type="text" placeholder="Search classroom" class="search_classroom">
                </div>
                <div class="classroom_container">
                    <?php if($data > 0): ?>
                        <?php foreach($data as $item): ?>
                            <div class="classroom">
                                <a href="classroom.php?classroom=<?php echo base64_encode($item['classCode']); ?>_<?php echo base64_encode($item['module_id']); ?>">
                                    <div class="class_img">
                                        <img src="./upload/theme1.jpg" alt="theme">
                                    </div>
                                    <div class="class_content">
                                        <h2 class="classroom_name"><?php echo $item['moduleName']; ?></h2>
                                        <p class="program"><?php echo $item['programName']; ?></p>
                                        <p><?php echo $item['classCode']; ?></p>
                                        <div class="id">
                                            <p><?php echo $item['fullname']; ?></p>
                                            <img src="<?php echo (!empty($item['photo'])) ? '../uploads/'.$item['photo'] : '../uploads/NO-IMAGE-AVAILABLE.jpg'; ?>" alt="user">
                                            
                                        </div>
                                    </div>
                                </a>
                                <!-- <div class="class_footer">
                                    <i class="fa-solid fa-folder-closed icon"></i>
                                </div> -->
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <!-- pagination -->
                    <div class="pagination">
                        <?php
                            if($page > 1) { ?>
                                <p>
                                    <a href="classrooms.php?page=<?php echo ($page - 1); ?>">
                                        Previous
                                    </a>
                                </p>
                            <?php }
                            else { ?>
                                <p>
                                    <a href="classrooms.php?page=<?php echo $page; ?>">
                                        Previous
                                    </a>
                                </p>
                            <?php }
                        ?>
                        
                            
                        
                        <?php for($page = 1; $page <= $num_of_pages; $page++): ?>
                            <p>
                                <a href="classrooms.php?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                            </p>
                        <?php endfor; ?>

                        <?php if($page = $page) :?>
                            <p>
                                <a href="classrooms.php?page=<?php echo ($page - 1); ?>">
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