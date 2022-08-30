<?php require_once('./includes/header.php'); ?>
<?php require_once('../config/session.php'); ?>

<?php 
    
    $selectQuery = "SELECT * FROM users WHERE _id = '$session_id' ";
    $result = mysqli_query($conn, $selectQuery);
    $lecturer = mysqli_fetch_array($result);
    // 
?>

<!-- create classwork -->
<?php
    $get_id = ($_GET['classroom']);
    $split = explode('_', $get_id);
    // print_r($split);
    $class_code = base64_decode($split[0]);
    $module_id = base64_decode($split[1]);
    $classwork_id = $split[2];
    // echo $class_code . '<br>' . $module_id . '<br>';

    // targetting first 2 item in the $split array
    $get__id = $split[0].'_'.$split[1];
    // echo $get__id;

    // Getting
    $selectQuery = "SELECT *, fullname FROM classworks INNER JOIN users ON classworks.lecturer_id = users._id WHERE classworks.classwork_id = '$classwork_id' && users._id = '$session_id'  ";
    $result = mysqli_query($conn, $selectQuery);
    // $lecturer_id = mysqli_fetch_array($result);
    // $lecturer_id = $lecturer_id['_id'];
    $classwork = mysqli_fetch_array($result);
    // $lecturer_id = $lecturer_id['_id'];

    // $view_work = "../uploads/" . $classwork['upload'];
    $upload = $classwork['upload'];
    // echo $upload;
    // print_r($view_work);

    
?>

<!-- Getting all submitted assignments -->
<?php
    $query = "SELECT * FROM assignments INNER JOIN users ON assignments.student_id = users._id WHERE assignment_id = '$classwork_id' && module_id = '$module_id' && class_code = '$class_code' && lecturer_id = '$session_id' ";
    $assignment_result = mysqli_query($conn, $query);
    $assignments = mysqli_fetch_all($assignment_result, MYSQLI_ASSOC);
    
    // print_r($assignments);
?>

<body>
    <?php require_once('./includes/topNav.php'); ?>
    <main class="container">
    <?php require_once('./includes/sideNav.php'); ?>
        <div class="home" id="dashboard">
            <div class="_container">
                <div class="_container__">
                    <div class="classworks_">
                        <div class="view_container">
                            <div class="view_header">
                                <div class="d-f">
                                    <div class="_icon">
                                        <i class="fa-regular fa-clone icon"></i>
                                    </div>
                                    <div class="d-f_content">
                                        <?php if($classwork > 0): ?>
                                            <h2><?php echo $classwork['title']; ?></h2>
                                            <div>
                                                <p class="lecturer_"><?php echo $classwork['fullname']; ?>::</p>
                                                <p><?php echo date('M, D, Y', strtotime($classwork['date_created'])); ?></p>
                                            </div>
                                            <p class="description"><?php echo $classwork['description']; ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="view_content">
                                <?php if($classwork > 0):?>
                                    <a href="view_classwork.php?material=<?php echo $classwork['upload'];?>" class="material">
                                        <div class="content_img">
                                            <div class="the_file_img">
                                                <?php
                                                    $getClasswork = $classwork['upload'];
                                                    // echo $getAssignment;
                                                    $split = explode('.', $getClasswork);
                                                    $upload_name = $split[0].'.png';
                                                    // echo $assignment_name;

                                                    $files_in_image_format = scandir("../image_format");
                                                    // print_r($files_in_image_format);
                                                    for($i = 0; $i < count($files_in_image_format); $i++) {
                                                        // echo $files_in_image_format[$i];
                                                        if($upload_name == $files_in_image_format[$i]) { ?>
                                                            <img src="../image_format/<?php echo $files_in_image_format[$i];?>" class="img" alt="">
                                                        <?php }
                                                    
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="filename"><?php echo $classwork['upload']; ?></p>
                                        </div>
                                    </a>
                                <?php endif; ?>
                            </div>
                            
                        </div>

                        <div class="submitted_assignments">
                            <div class="heading">
                                <h2>Submitted Assignments</h2>
                                <p><?php echo count($assignments) > 0 ? count($assignments) : 0 ?> Assignments</p>
                            </div>
                            <div class="assignments_container">
                                <?php foreach($assignments as $assignment): ?>
                                    <div>
                                        <a href="submitted_assignment.php?student=<?php echo base64_encode($class_code); ?>_<?php echo base64_encode($module_id); ?>_<?php echo $assignment['assignment_id']; ?>_<?php echo $assignment['_id']; ?>" class="assignment">
                                            <p><?php echo $assignment['fullname']; ?></p>
                                            <?php 
                                                if($assignment['status'] == "Marked") { ?>
                                                    <p class="marked"><?php echo $assignment['status']; ?> </p>
                                                <?php }
                                                else { ?>
                                                    <p class="unmarked"><?php echo $assignment['status']; ?> </p>
                                                <?php }
                                            ?>
                                            
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php require_once('./includes/footer.php'); ?>