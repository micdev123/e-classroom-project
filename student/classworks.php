<?php require_once('./includes/header.php'); ?>
<?php require_once('../config/session.php'); ?>

<?php 
    
    $selectQuery = "SELECT * FROM users WHERE _id = '$session_id' ";
    $result = mysqli_query($conn, $selectQuery);
    $student = mysqli_fetch_array($result);
    // 
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

    $queryLesson = "SELECT * FROM classworks WHERE module_id = '$module_id' && class_id = '$class_id' && type = 'Lesson'  ";

    $queryAssignment = "SELECT * FROM classworks WHERE module_id = '$module_id' && class_id = '$class_id' && type = 'Assignment' ";
    

    $resultLesson = mysqli_query($conn, $queryLesson);

    $resultAssignment = mysqli_query($conn, $queryAssignment);

    $dataLesson = mysqli_fetch_all($resultLesson, MYSQLI_ASSOC);

    $dataAssignment = mysqli_fetch_all($resultAssignment, MYSQLI_ASSOC);

    // print_r($dataLesson);

    // print_r($dataAssignment);

    // $_id = 1;

?>


<!-- Delete -->
<?php 
    if (isset($_GET['delete'])) {
        $get_id = ($_GET['delete']);
        $split = explode('_', $get_id);
        $_id = $split[2];

        $get__id = $split[0].'_'.$split[1];

        $location = 'classwork.php' . '?classroom=' . $get__id;

        $delete = "DELETE FROM classworks WHERE _id = '$_id' ";
        
        // mysqli_query();
        if(mysqli_query($conn, $delete)) {
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
                    <div class="classroom_nav">
                        <?php require_once('./includes/classroomNav.php'); ?>
                    </div>
                    <div class="classworks">
                        <a href="view_your_work.php?classroom=<?php echo base64_encode($class_code); ?>_<?php echo base64_encode($module_id); ?>" class="view_work">
                            <i class="fa-solid fa-map icon"></i>
                            <p>View your work</p>
                        </a>
                        <div class="classwork">
                            <div class="lessons">
                                <h2>Materials</h2>
                                <?php if($dataLesson > 0): ?>
                                    <div class="lesson_list">
                                        <?php foreach($dataLesson as $lesson): ?>
                                        <div class="list">
                                            <a href="classwork.php?classroom=<?php echo base64_encode($class_code); ?>_<?php echo base64_encode($module_id); ?>_<?php echo $lesson['classwork_id']; ?>" class="list_content">
                                                <div class="d-f">
                                                    <div class="d-f_content">
                                                        <div class="_icon">
                                                            <i class="fa-regular fa-clone icon"></i>
                                                        </div>
                                                        <p><?php echo $lesson['title']; ?></p>
                                                    </div>
                                                    <p class="date"><?php echo date('M, D, Y', strtotime($lesson['date_created'])); ?></p>
                                                </div>
                                            </a> 
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="assignments">
                                <h2>Assignments | Others</h2>
                                <?php if($dataAssignment > 0): ?>
                                    <div class="assignment_list">
                                        <?php foreach($dataAssignment as $assignment): ?>
                                        <div class="list">
                                            <a href="submit_assignment.php?classroom=<?php echo base64_encode($class_code); ?>_<?php echo base64_encode($module_id); ?>_<?php echo $assignment['classwork_id']; ?>" class="list_content">
                                                <div class="d-f">
                                                    <div class="d-f_content">
                                                        <div class="_icon">
                                                            <i class="fa-regular fa-clone icon"></i>
                                                        </div>
                                                        <p><?php echo $assignment['title']; ?></p>
                                                    </div>
                                                    <p class="date"><?php echo date('M, D, Y', strtotime($assignment['date_created'])); ?></p>
                                                </div>
                                            </a>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php require_once('./includes/footer.php'); ?>