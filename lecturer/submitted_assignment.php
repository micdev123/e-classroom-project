<?php require_once('./includes/header.php'); ?>
<?php require_once('../config/session.php'); ?>

<?php 
    
    $selectQuery = "SELECT * FROM users WHERE _id = '$session_id' ";
    $result = mysqli_query($conn, $selectQuery);
    $lecturer = mysqli_fetch_array($result);
    // 
?>

<!-- Getting all submitted assignments -->
<?php
    $get_id = ($_GET['student']);
    $split = explode('_', $get_id);
    // print_r($split);
    $class_code = base64_decode($split[0]);
    $module_id = base64_decode($split[1]);
    $classwork_id = $split[2];
    $assignment_id = $split[3];
    // echo $class_code . '<br>' . $module_id . '<br>';

    // targetting first 2 item in the $split array
    $get__id = $split[0].'_'.$split[1];

    $query = "SELECT * FROM assignments INNER JOIN users ON assignments.student_id = users._id INNER JOIN classworks ON assignments.assignment_id = classworks.classwork_id WHERE assignment_id = '$classwork_id' && assignments.module_id = '$module_id' && class_code = '$class_code' && assignments.lecturer_id = '$session_id' ";
    $assignment_result = mysqli_query($conn, $query);
    $assignment = mysqli_fetch_array($assignment_result);
    
    // print_r($assignment);
?>

<!-- Update assignment status -->
<?php
    if(isset($_POST['submit'])) {
        $location = 'submitted_assignment.php' . '?student=' . $get_id;
        if(isset($_POST['confirm'])) {
            $checked = 'Marked';
        }
        else {
            $checked = 'Not Yet';
        }
        
        $update = "UPDATE assignments SET status = '$checked' WHERE assignment_id = '$classwork_id' && module_id = '$module_id' ";

        if(mysqli_query($conn, $update)) {
            header('Location: ' . $location);
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
            <div class="_container">
                <div class="_container__">
                    <div class="classworks_">
                        <div class="view_container">
                            <div class="view_header">
                                <div class="d-f">
                                    <div class="_icon">
                                        <i class="fa-solid fa-file-lines icon"></i>
                                    </div>
                                    <?php if($assignment > 0):?>
                                        <div class="student_">
                                            <div>
                                                <h2><?php echo $assignment['fullname']; ?></h2>
                                                <div class="check">
                                                    <form action="" method="POST">
                                                        <label for=""><?php echo $assignment['status']; ?></label>
                                                        <?php 
                                                            if($assignment['status'] == "Marked") { ?>
                                                                <input type="checkbox" name="confirm" class="checkbox_" checked>
                                                            <?php }
                                                            else { ?>
                                                                <input type="checkbox" name="confirm" class="checkbox_">
                                                            <?php } 
                                                        ?>
                                                        <input type="submit" name="submit" class="submit" value="update">
                                                    </form>
                                                </div>
                                            </div>
                                            <p><?php echo $assignment['title']; ?></p>
                                            <p><?php echo date('M, D, Y', strtotime($assignment['date_created'])); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="view_content">
                                <?php if($assignment > 0): ?>
                                    <a href="view_classwork.php?material=<?php echo $assignment['assignment'];?>" class="material">
                                        <div class="content_img">
                                            <div class="the_file_img">
                                                <?php
                                                    $getClasswork = $assignment['assignment'];
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
                                            <p class="filename"><?php echo $assignment['assignment']; ?></p>
                                        </div>
                                    </a>
                                <?php endif; ?>
                            </div>
                            
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
<?php require_once('./includes/footer.php'); ?>