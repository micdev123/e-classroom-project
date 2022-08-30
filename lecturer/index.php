<?php require_once('./includes/header.php'); ?>
<?php require_once('../config/session.php'); ?>

<?php 
    
    $selectQuery = "SELECT * FROM users WHERE _id = '$session_id' ";
    $result = mysqli_query($conn, $selectQuery);
    $lecturer = mysqli_fetch_array($result);
    // 
?>

<?php
    $data_per_page = 5;
    // class_id
    $query = "SELECT * FROM classroomlecturers WHERE lecturer_id = '$session_id' ";
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $class_id = mysqli_fetch_array($result);

    $classId = $class_id['class_id'];
    // echo $classId;

    
    $selectQuery = "SELECT programCode, classCode, moduleName, module_id FROM classmodules INNER JOIN classroomlecturers ON classmodules.class_id = classroomlecturers.class_id INNER JOIN modules ON classmodules.module_id = modules._id INNER JOIN users ON classroomlecturers.lecturer_id = users._id INNER JOIN programclassrooms ON classmodules.class_id = programclassrooms.class_id INNER JOIN programs ON programclassrooms.program_id = programs._id INNER JOIN classrooms ON classmodules.class_id = classrooms._id WHERE classroomlecturers.lecturer_id = '$session_id' ";
    
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
    $selectQuery = "SELECT programCode, classCode, moduleName, module_id FROM classmodules INNER JOIN classroomlecturers ON classmodules.class_id = classroomlecturers.class_id INNER JOIN modules ON classmodules.module_id = modules._id INNER JOIN users ON classroomlecturers.lecturer_id = users._id INNER JOIN programclassrooms ON classmodules.class_id = programclassrooms.class_id INNER JOIN programs ON programclassrooms.program_id = programs._id INNER JOIN classrooms ON classmodules.class_id = classrooms._id WHERE classroomlecturers.lecturer_id = '$session_id' LIMIT $start, $data_per_page";
    
    $result = mysqli_query($conn, $selectQuery);

    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // print_r($data);

    $_id = 1;

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


<body>
    <?php require_once('./includes/topNav.php'); ?>
    <main class="container">
        <?php require_once('./includes/sideNav.php'); ?>
        <div class="home" id="dashboard">
            <div class="home_container">
                <div class="welcome_container">
                    <div class="admin_img">
                        <img src="./asserts/admin_image.png" alt="admin-img">
                    </div>
                    <div class="content">
                        <h4>Welcome <span> Lecturer</span></h4>
                        <h1><?php echo $lecturer['fullname']; ?></h1>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit aperiam veritatis, quas modi deserunt quod ullam fugit? Pariatur cupiditate similique, voluptatum placeat architecto nulla nam quis sit corrupti earum magnam?</p>
                    </div>
                </div>
                
                <div class="list_container">
                    <div class="classroom_head">
                        <h2>Information</h2>
                        <div class="search">
                            <input type="text" placeholder="Search classroom" class="search_classroom">
                        </div>
                    </div>
                    
                    <div class="list_head">
                        <h3>_Id</h3>
                        <h3>Program Code</h3>
                        <h3>Classroom</h3>
                        <h3>Module Name</h3>
                        <h3>Actions</h3>
                    </div>
                    <?php if(count($data) > 0): ?>
                        <!-- loop through users array -->
                        <?php foreach($data as $item): ?>
                            <div class="list">
                                <p><?php echo $_id++ ;?></p>
                                <p><?php echo $item['programCode'];?></p>
                                <p class="name"><?php echo $item['classCode'];?></p>
                                <p class="name"><?php echo $item['moduleName'];?></p>
                                <div class="action action_center">
                                    <a href="classworks.php?classroom=<?php echo base64_encode($item['classCode']); ?>_<?php echo base64_encode($item['module_id']); ?>">
                                        <i class="fas fa-eye icon"></i>
                                        <span class="tooltip">View</span>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <!-- pagination -->
                    <div class="pagination">
                        <?php
                            if($page > 1) { ?>
                                <p>
                                    <a href="index.php?page=<?php echo ($page - 1); ?>">
                                        Previous
                                    </a>
                                </p>
                            <?php }
                            else { ?>
                                <p>
                                    <a href="index.php?page=<?php echo $page; ?>">
                                        Previous
                                    </a>
                                </p>
                            <?php }
                        ?>
                        
                            
                        
                        <?php for($page = 1; $page <= $num_of_pages; $page++): ?>
                            <p>
                                <a href="index.php?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                            </p>
                        <?php endfor; ?>

                        <?php if($page = $page) :?>
                            <p>
                                <a href="index.php?page=<?php echo ($page - 1); ?>">
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