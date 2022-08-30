<?php require_once('./includes/header.php'); ?>
<?php require_once('../config/session.php')?>


<?php
    $users_per_page = 4;
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

<!-- Delete -->
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

<?php
    // Get total registered users
    $query = "SELECT * FROM users";
    $result = mysqli_query($conn, $query);
    $total_users = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<body>
    <?php require_once('./includes/topNav.php'); ?>
    <main class="container">
        <?php require_once('./includes/sideNav.php'); ?>
        <div class="home_" id="users">
            <div class="home_container">
                <div class="list_container __listContainer">
                    <div class="_head">
                        <p>Showing <?php echo count($total_users); ?> users</p>
                        <button class="add_btn" id="open_modal">
                            <a href="./createUser.php">
                                Add User
                            </a>
                        </button>
                    </div>
                    <div class="search">
                        <input type="text" class="search_filter" placeholder="search by name">
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
                                    <a href="users.php?delete=<?php echo $user['_id'] ?>">
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