<?php require_once('./includes/header.php'); ?>
<?php require_once('../config/session.php'); ?>

<?php 
    
    $selectQuery = "SELECT * FROM users WHERE _id = '$session_id' ";
    $result = mysqli_query($conn, $selectQuery);
    $lecturer = mysqli_fetch_array($result);
    // 
?>

<?php
    $get_id = ($_GET['classroom']);
    $split = explode('_', $get_id);
    $class_code = base64_decode($split[0]);
    $module_id = base64_decode($split[1]);

    // Getting lecturer id
    $selectQuery = "SELECT * FROM users WHERE _id = '$session_id' ";
    $result = mysqli_query($conn, $selectQuery);
    $lecturer_id = mysqli_fetch_array($result);
    $lecturer_id = $lecturer_id['_id'];

    $query_class = "SELECT * FROM classrooms WHERE classCode = '$class_code' ";
    $result_ = mysqli_query($conn, $query_class);
    $class_id = mysqli_fetch_array($result_);
    $class_id = $class_id['_id'];

    $queryLesson = "SELECT * FROM classworks WHERE module_id = '$module_id' && class_id = '$class_id' && type = 'Lesson' && lecturer_id = '$lecturer_id' ";

    $queryAssignment = "SELECT * FROM classworks WHERE module_id = '$module_id' && class_id = '$class_id' && type = 'Assignment' && lecturer_id = '$lecturer_id' ";
    

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
                        <div class="add_btn">
                            <button>
                                <a href="create_classwork.php?classroom=<?php echo base64_encode($class_code); ?>_<?php echo base64_encode($module_id); ?>">
                                Add Classwork
                            </a>
                            </button>
                        </div>
                        <div class="classwork">
                            <div class="lessons">
                                <h2>Materials</h2>
                                <div class="lesson_list">
                                    <?php if($dataLesson > 0): ?>
                                        <?php foreach($dataLesson as $lesson): ?>
                                        <div class="list">
                                            <div class="list_content">
                                                <div class="d-f">
                                                    <div class="_icon">
                                                        <i class="fa-regular fa-clone icon"></i>
                                                    </div>
                                                    <div class="d-f_content">
                                                        <p><?php echo $lesson['title']; ?></p>
                                                        <p class="date"><?php echo date('M, D, Y', strtotime($lesson['date_created'])); ?></p>
                                                    </div>
                                                </div>
                                                <div class="action">
                                                    <a href="classwork.php?classroom=<?php echo base64_encode($class_code); ?>_<?php echo base64_encode($module_id); ?>_<?php echo $lesson['classwork_id']; ?>">
                                                        <i class="fas fa-eye icon"></i>
                                                        <span class="tooltip">View</span>
                                                    </a>
                                                    <a href="edit_classwork.php?classroom=<?php echo base64_encode($class_code); ?>_<?php echo base64_encode($module_id); ?>_<?php echo $lesson['classwork_id']; ?>">
                                                        <i class="fa-solid fa-file-pen icon"></i>
                                                        <span class="tooltip">Edit</span>
                                                    </a>
                                                    <a href="classwork.php?delete=<?php echo base64_encode($class_code); ?>_<?php echo base64_encode($module_id); ?>_<?php echo $lesson['classwork_id']; ?>">
                                                        <i class="fas fa-trash-can icon trash"></i>
                                                        <span class="tooltip delete">Delete</span>
                                                    </a>
                                                </div>
                                            </div> 
                                        </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="assignments">
                                <h2>Assignments | Others</h2>
                                <div class="assignment_list">
                                    <?php if($dataAssignment > 0): ?>
                                        <?php foreach($dataAssignment as $assignment): ?>
                                        <div class="list">
                                            <div class="list_content">
                                                <div class="d-f">
                                                        <div class="_icon">
                                                            <i class="fa-regular fa-clone icon"></i>
                                                        </div>
                                                        <div class="d-f_content">
                                                            <p><?php echo $assignment['title']; ?></p>
                                                            <p class="date"><?php echo date('M, D, Y', strtotime($assignment['date_created'])); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="action">
                                                        <a href="assignment.php?classroom=<?php echo base64_encode($class_code); ?>_<?php echo base64_encode($module_id); ?>_<?php echo $assignment['classwork_id']; ?>">
                                                            <i class="fas fa-eye icon"></i>
                                                            <span class="tooltip">View</span>
                                                        </a>
                                                        <a href="edit_classwork.php?classroom=<?php echo base64_encode($class_code); ?>_<?php echo base64_encode($module_id); ?>_<?php echo $assignment['classwork_id']; ?>">
                                                            <i class="fa-solid fa-file-pen icon"></i>
                                                            <span class="tooltip">Edit</span>
                                                        </a>
                                                        <a href="classwork.php?delete=<?php echo base64_encode($class_code); ?>_<?php echo base64_encode($module_id); ?>_<?php echo $assignment['classwork_id']; ?>">
                                                            <i class="fas fa-trash-can icon trash"></i>
                                                            <span class="tooltip delete">Delete</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="./ckeditor/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('editor');
    </script>
<?php require_once('./includes/footer.php'); ?>