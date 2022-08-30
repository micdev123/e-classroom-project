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
    // echo $module_id;

    // Getting lecturer id
    $query_students = "SELECT fullname, classCode, photo FROM classroomstudents INNER JOIN classmodules ON classroomstudents.class_id = classmodules.class_id INNER JOIN users ON classroomstudents.student_id = users._id INNER JOIN classrooms ON classmodules.class_id = classrooms._id WHERE classmodules.module_id = '$module_id' && classrooms.classCode = '$class_code' "; 
    $resultStudents = mysqli_query($conn, $query_students);
    $students = mysqli_fetch_all($resultStudents, MYSQLI_ASSOC);
    // print_r($students) . '<br>';
    $num_of_students = count($students);

    $query_lecturers = "SELECT fullname, classCode, photo FROM classroomlecturers INNER JOIN classmodules ON classroomlecturers.class_id = classmodules.class_id INNER JOIN users ON classroomlecturers.lecturer_id = users._id INNER JOIN classrooms ON classmodules.class_id = classrooms._id WHERE classmodules.module_id = '$module_id' && classrooms.classCode = '$class_code' ";
    $resultLecturers = mysqli_query($conn, $query_lecturers);
    $lecturers = mysqli_fetch_all($resultLecturers, MYSQLI_ASSOC);
    // print_r($lecturers);

?>


<body>
    <?php require_once('./includes/topNav.php'); ?>
    <main class="container">
        <?php require_once('./includes/sideNav.php'); ?>
        <div class="home" id="dashboard">
            <div class="_container">
                <div class="_container_">
                    <?php require_once('./includes/classroomNav.php'); ?>
                    <div class="people">
                        <div class="lecturers">
                            <h2>Lecturers</h2>
                            <div class="lecturer_list">
                                <?php foreach($lecturers as $lecturer): ?>
                                    <div class="list">
                                        <img src="<?php echo (!empty($lecturer['photo'])) ? '../uploads/'.$lecturer['photo'] : '../uploads/NO-IMAGE-AVAILABLE.jpg'; ?>" alt="lecturer">
                                        <p><?php echo $lecturer['fullname']; ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="students">
                            <?php if($students > 0): ?>
                                <div class="student_head">
                                    <h2>Classmates</h2>
                                    <p><?php echo $num_of_students; ?> students</p>
                                </div>
                                <div class="student_list">
                                    <?php foreach($students as $student): ?>
                                        <div class="list">
                                            <img src="<?php echo (!empty($student['photo'])) ? '../uploads/'.$student['photo'] : '../uploads/NO-IMAGE-AVAILABLE.jpg'; ?>" alt="student">
                                            <p><?php echo $student['fullname']; ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php require_once('./includes/footer.php'); ?>