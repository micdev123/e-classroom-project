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



?>




<body>
    <?php require_once('./includes/topNav.php'); ?>
    <main class="container">
        <?php require_once('./includes/sideNav.php'); ?>
        <div class="home" id="dashboard">
            <div class="_container">
                <div class="_container__">
                    <div class="classworks">
                        <div class="view_work_head">
                            <img src="<?php echo (!empty($lecturer['photo'])) ? '../uploads/'.$lecturer['photo'] : '../uploads/NO-IMAGE-AVAILABLE.jpg'; ?>" alt="lecturer">
                            <h2><?php echo $student['fullname']; ?></h2>
                        </div>

                        <div class="works">
                            <div class="work">
                                <p>Test</p>
                                <p>10 June, 2022</p>
                                <p>34/35</p>
                            </div>
                        </div>
                         
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php require_once('./includes/footer.php'); ?>