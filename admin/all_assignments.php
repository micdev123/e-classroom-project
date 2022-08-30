<?php require_once('./includes/header.php'); ?>
<?php require_once('../config/session.php')?>


<?php
    $assignments_per_page = 4;
    // Getting data from users table
    $selectQuery = "SELECT * FROM assignments INNER JOIN modules ON assignments.module_id = modules._id INNER JOIN users ON assignments.student_id = users._id ";
    
    $result = mysqli_query($conn, $selectQuery);

    // number of users
    $num_of_assignments = mysqli_num_rows($result);
    // number of page 
    $num_of_pages = ceil($num_of_assignments / $assignments_per_page);
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
    $start = ($page - 1) * $assignments_per_page;

    // Getting specify data from users table :: using the LIMIT
    $selectQuery = "SELECT * FROM assignments INNER JOIN modules ON assignments.module_id = modules._id INNER JOIN users ON assignments.student_id = users._id LIMIT $start, $assignments_per_page";
    
    $result = mysqli_query($conn, $selectQuery);
    $assignments = mysqli_fetch_all($result, MYSQLI_ASSOC);
   
    // print_r($assignments);

?>


<?php
    // Get total submitted assignments
    $query = "SELECT * FROM assignments";
    $result = mysqli_query($conn, $query);
    $total_assignments = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>



<body>
    <?php require_once('./includes/topNav.php'); ?>
    <main class="container">
        <?php require_once('./includes/sideNav.php'); ?>
        <div class="home_" id="users">
            <div class="home_container">
                <div class="list_container assignments_">
                    <div class="_head">
                        <?php if($total_assignments > 0): ?>
                            <p>Showing <?php echo count($total_assignments); ?> assignments</p>
                        <?php endif; ?>
                    </div>
                    <div class="search">
                        <input type="text" class="search_filter" placeholder="search by name">
                    </div>
                    
                    <div class="list_head">
                        <h3>Student Id</h3>
                        <h3>Student Names</h3>
                        <h3>Classrooms</h3>
                        <h3>Modules</h3>
                        <h3>Assignments</h3>
                        <h3>Status</h3>
                    </div>
                    <?php if($assignments > 0): ?>
                    <!-- loop through users array -->
                    <?php foreach($assignments as $assignment): ?>
                        <div class="list">
                            <p><?php echo $assignment['userId'];?></p>
                            <p><?php echo $assignment['fullname'];?></p>
                            <p class="name"><?php echo $assignment['class_code'];?></p>
                            <p><?php echo $assignment['moduleName'];?></p>
                            <p><?php echo $assignment['assignment'];?></p>
                            <p><?php echo $assignment['status'] ? $assignment['status'] : 'Unmarked'; ?></p>
                        </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    <!-- pagination -->
                    <div class="pagination">
                        <?php
                            if($page > 1) { ?>
                                <p>
                                    <a href="users.php?page=<?php echo ($page - 1); ?>">
                                        Previous
                                    </a>
                                </p>
                            <?php }
                            else { ?>
                                <p>
                                    <a href="users.php?page=<?php echo $page; ?>">
                                        Previous
                                    </a>
                                </p>
                            <?php }
                        ?>
                        
                            
                        
                        <?php for($page = 1; $page <= $num_of_pages; $page++): ?>
                            <p>
                                <a href="users.php?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                            </p>
                        <?php endfor; ?>

                        <?php if($page = $page) :?>
                            <p>
                                <a href="users.php?page=<?php echo ($page - 1); ?>">
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