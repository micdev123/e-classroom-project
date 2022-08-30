<?php require_once('./includes/header.php'); ?>
<?php require_once('../config/session.php')?>

<?php 
    
    $selectQuery = "SELECT * FROM users WHERE _id = '$session_id' ";
    $result = mysqli_query($conn, $selectQuery);
    $admin = mysqli_fetch_array($result);
    // 
?>

<?php
    $users_per_page = 3;
    // Getting data from users table
    $selectQuery = "SELECT * FROM users";
    
    $result = mysqli_query($conn, $selectQuery);

    // number of users
    $num_of_users = mysqli_num_rows($result);
    // number of page 
    $num_of_pages = ceil($num_of_users / $users_per_page);
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
    $start = ($page - 1) * $users_per_page;

    // Getting specify data from users table :: using the LIMIT
    $selectQuery = "SELECT * FROM users LIMIT $start, $users_per_page";
    
    $result = mysqli_query($conn, $selectQuery);

    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);

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

<!-- Category -->
<?php
    // Get total registered users
    $query = "SELECT * FROM users";
    $result = mysqli_query($conn, $query);
    $total_users = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Get all faculties
    $query = "SELECT * FROM faculties";
    $result = mysqli_query($conn, $query);
    $total_faculties = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Get all programs
    $query = "SELECT * FROM programs";
    $result = mysqli_query($conn, $query);
    $total_programs = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Get all classes
    $query = "SELECT * FROM classrooms";
    $result = mysqli_query($conn, $query);
    $total_classrooms = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Get all assignments
    $query = "SELECT * FROM assignments";
    $result = mysqli_query($conn, $query);
    $total_assignments = mysqli_fetch_all($result, MYSQLI_ASSOC);

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
                        <h4>Welcome <span> Admin</span></h4>
                        <?php if($admin['fullname']): ?>
                            <h1><?php echo $admin['fullname']; ?></h1>
                        <?php endif; ?>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit aperiam veritatis, quas modi deserunt quod ullam fugit? Pariatur cupiditate similique, voluptatum placeat architecto nulla nam quis sit corrupti earum magnam?</p>
                    </div>
                </div>
                <div class="category_container">
                    <div class="content">
                        <?php if($total_users > 0): ?>
                            <h1><?php echo count($total_users); ?></h1>
                        <?php endif; ?>
                        <div>
                            <i class="fas fa-users icon"></i>
                            <p>Users</p>
                        </div>
                    </div>
                
                    <div class="content">
                        <?php if($total_faculties > 0): ?>
                            <h1><?php echo count($total_faculties); ?></h1>
                        <?php endif; ?>
                        <div>
                            <i class="fas fa-building-user icon"></i>
                            <p>Faculties</p>
                        </div>
                    </div>
                    <div class="content">
                        <?php if($total_programs > 0): ?>
                            <h1><?php echo count($total_programs); ?></h1>
                        <?php endif; ?>
                        <div>
                            <i class='bx bxs-card icon'></i>
                            <p>Programs</p>
                        </div>
                    </div>

                    <div class="content">
                        <?php if($total_classrooms > 0): ?>
                            <h1><?php echo count($total_classrooms); ?></h1>
                        <?php endif; ?>
                        <div>
                            <i class="fa-solid fa-users-between-lines icon"></i>
                            <p>Classes</p>
                        </div>
                    </div>

                    <div class="content">
                        <?php if($total_assignments > 0): ?>
                            <h1><?php echo count($total_assignments); ?></h1>
                        <?php endif; ?>
                        <div>
                            <i class="fa-solid fa-file-lines icon"></i>
                            <p>Assignments</p>
                        </div>
                    </div>
                </div>
                <div class="list_container">
                    <div class="_head">
                        <div class="search">
                            <input type="text" class="search_filter" placeholder="search by name">
                        </div>
                    </div>
                    <div class="list_head">
                        <h3>_Id</h3>
                        <h3>UserId</h3>
                        <h3>Fullname</h3>
                        <h3>Role</h3>
                        <h3>Actions</h3>
                    </div>
                    <?php if($users > 0): ?>
                        <!-- loop through users array -->
                        <?php foreach($users as $user): ?>
                            <div class="list">
                                <p><?php echo $user['_id'];?></p>
                                <p><?php echo $user['userId'];?></p>
                                <p class="name"><?php echo $user['fullname'];?></p>
                                <p class="role <?php echo $user['role'];?>"><?php echo $user['role'];?></p>
                                <div class="action">
                                    <a href="editUser.php?edit=<?php echo $user['_id'];?>">
                                        <i class="fas fa-user-pen icon"></i>
                                        <span class="tooltip">Edit</span>
                                    </a>
                                    <a href="index.php?delete=<?php echo $user['_id'] ?>">
                                        <i class="fas fa-trash-can icon trash"></i>
                                        <span class="tooltip delete">Delete</span>
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