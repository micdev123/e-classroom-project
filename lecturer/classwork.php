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

<body>
    <?php require_once('./includes/topNav.php'); ?>
    <main class="container">
    <?php require_once('./includes/sideNav.php'); ?>
        <div class="home" id="dashboard">
            <div class="_container">
                <div class="_container__">
                    <div class="classworks">
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
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="view_content">
                                <?php if($classwork > 0): ?>
                                    <a href="view_classwork.php?material=<?php echo $classwork['upload'];?>" class="material">
                                        <div class="content_img">
                                            <div class="the_file_img">
                                                <?php
                                                    // $getClasswork = $assignment['assignment'];
                                                    // echo $getAssignment;
                                                    $split = explode('.', $upload);
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

                    </div>
                    
                </div>
            </div>
        </div>
    </main>
<?php require_once('./includes/footer.php'); ?>