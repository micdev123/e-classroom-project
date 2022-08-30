<?php require_once('./includes/header.php'); ?>
<?php require_once('../config/session.php'); ?>

<?php 
    
    $selectQuery = "SELECT * FROM users WHERE _id = '$session_id' ";
    $result = mysqli_query($conn, $selectQuery);
    $lecturer = mysqli_fetch_array($result);
    // 
?>

<?php
    $get_id = $_GET['classroom'];
    $split = explode('_', $get_id);
    $class_code = base64_decode($split[0]);
    $module_id = base64_decode($split[1]);

    // echo $class_code . '<br>' . $module_id;
    $selectQuery = "SELECT classCode, moduleName, programName FROM classmodules INNER JOIN modules ON classmodules.module_id = modules._id INNER JOIN programclassrooms ON classmodules.class_id = programclassrooms.class_id INNER JOIN programs ON programclassrooms.program_id = programs._id INNER JOIN classrooms ON classmodules.class_id = classrooms._id WHERE modules._id = '$module_id' && classrooms.classCode = '$class_code' ";
    
    
    $result = mysqli_query($conn, $selectQuery);

    $data = mysqli_fetch_array($result);

    // print_r($data);

    // $_id = 1;

?>

<?php
    $get_id = ($_GET['classroom']);
    $split = explode('_', $get_id);
    $class_code = base64_decode($split[0]);
    $module_id = base64_decode($split[1]);

    $query_class = "SELECT * FROM classrooms WHERE classCode = '$class_code' ";
    $result_ = mysqli_query($conn, $query_class);
    $class_id = mysqli_fetch_array($result_);
    $class_id = $class_id['_id'];

    $query = "SELECT *, fullname FROM classworks INNER JOIN users ON classworks.lecturer_id = users._id WHERE module_id = '$module_id' && class_id = '$class_id' ";

    $result = mysqli_query($conn, $query);

    $classworks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    // print_r($classworks);
?>


<body>
    <?php require_once('./includes/topNav.php'); ?>
    <main class="container">
        <?php require_once('./includes/sideNav.php'); ?>
        <div class="home" id="dashboard">
            <div class="_container">
                <div class="_container_">
                    <?php require_once('./includes/classroomNav.php'); ?>
                    <div class="classroom_content">
                        <div class="classroom_header">
                            <div class="classroom_img">
                                <img src="./upload/theme1.jpg" alt="theme">
                            </div>
                            <?php if($data > 0): ?>
                                <div class="classroon_header_content">
                                    <h2><?php echo $data['moduleName']; ?></h2>
                                    <p><?php echo $data['programName']; ?></p>
                                    <p><?php echo $data['classCode']; ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="streams_container">
                            <?php if($classworks > 0): ?>
                                <?php foreach($classworks as $classwork): ?>
                                    <?php
                                        if($classwork['type'] == 'Lesson') { ?>
                                            <a href="classwork.php?classroom=<?php echo base64_encode($class_code); ?>_<?php echo base64_encode($module_id); ?>_<?php echo $classwork['classwork_id']; ?>" class="stream">
                                                <div class="stream_icon">
                                                    <i class="fa-solid fa-file icon "></i>
                                                </div>
                                                <div class="stream_content">
                                                    <p><?php echo $classwork['fullname']; ?> <span>posted a new <?php echo $classwork['type'] == 'Lesson' ? 'material' : 'assignment'; ?> : <?php echo $classwork['title']; ?></span></p>
                                                    <p class="date"><?php echo date('M, D, Y', strtotime($classwork['date_created'])); ?></p>
                                                </div>
                                            </a>
                                    <?php }
                                    else { ?>
                                            <a href="submit_assignment.php?classroom=<?php echo base64_encode($class_code); ?>_<?php echo base64_encode($module_id); ?>_<?php echo $classwork['classwork_id']; ?>" class="stream">
                                                <div class="stream_icon">
                                                    <i class="fa-solid fa-file icon "></i>
                                                </div>
                                                <div class="stream_content">
                                                    <p><?php echo $classwork['fullname']; ?> <span>posted a new <?php echo $classwork['type'] == 'Lesson' ? 'material' : 'assignment'; ?> : <?php echo $classwork['title']; ?></span></p>
                                                    <p class="date"><?php echo date('M, D, Y', strtotime($classwork['date_created'])); ?></p>
                                                </div>
                                            </a>
                                    <?php }
                                    ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php require_once('./includes/footer.php'); ?>