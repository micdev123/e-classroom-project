<?php require_once('./includes/header.php'); ?>
<?php require_once('../config/session.php')?>




<!-- Create -->
<?php
    $news = "";
    // check if isset
    if(isset($_POST['submit'])) {
        // check input
        $news = filter_input(INPUT_POST, 'news', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // Add to users table in database
        $insert = "INSERT INTO announcements (announcement) VALUES('$news')";

        // mysqli_query($conn, $insert);
        if(mysqli_query($conn, $insert)) {
            header('Location: announcements.php');
        }
        else {
            echo "Error" . mysqli_error($conn);
        }
        
    }
?>

<!-- Display | Pagination -->
<?php
    $announcements_per_page = 4;
    // Getting data from users table
    $selectQuery = "SELECT * FROM announcements";
    
    $result = mysqli_query($conn, $selectQuery);

    // number of users
    $num_of_announcements = mysqli_num_rows($result);
    // number of page 
    $num_of_pages = ceil($num_of_announcements / $announcements_per_page);
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
    $start = ($page - 1) * $announcements_per_page;

    // Getting specify data from modules table :: using the LIMIT
    $selectQuery = "SELECT * FROM announcements LIMIT $start, $announcements_per_page";
    
    $result = mysqli_query($conn, $selectQuery);

    $announcements = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!-- Edit -->
<?php 

    // Check if form is submit
    if(isset($_POST['update'])) {
        $get_id = $_GET['edit'];
        // echo 'working';
        // Validating
        $news = filter_input(INPUT_POST, 'news', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // query string
       
        // Update faculty
        $update = "UPDATE announcements SET announcement = '$news' where _id ='$get_id' ";

        // mysqli_query($conn, $insert);
        if(mysqli_query($conn, $update)) {
            header('Location: announcements.php');
        }
        else {
            echo "Error" . mysqli_error($conn);
        }
        
    }
?>


<!-- Delete-->
<?php 
    if (isset($_GET['delete'])) {
        $get_id = $_GET['delete'];

        $delete = "DELETE FROM announcements WHERE _id = '$get_id' ";
        
        // mysqli_query();
        if(mysqli_query($conn, $delete)) {
            header('Location: announcements.php');
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
        <div class="home_" id="faculties">
            <div class="container__">
                <div class="create">
                    <?php
                        // Create form
                        if(!isset($_GET['edit'])) {
                            ?>
                                <h2>Create New Announcement</h2>
                                <form action="" method="POST" enctype="multipart/form-data" class="form" >
                                    <div class="form_group">
                                        <label for="modcode">Announcement</label>
                                        <input type="text" name="news" placeholder="Enter new announcement" required>
                                    </div>
                                    
                                    <div class="form_group">
                                        <button name="submit" class="create_btn">Create</button>
                                    </div>
                                </form>
                            <?php
                        }
                        else {
                            // Edit form
                            ?>
                                <h2>Update Announcement</h2>
                                <form action="" method="POST" enctype="multipart/form-data" class="form" >
                                <?php
                                    $get_id = $_GET['edit'];

                                    $query = "SELECT * FROM announcements WHERE _id = '$get_id' ";
                                    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
                                    $announcement = mysqli_fetch_array($result);
                                ?>
                                    <div class="form_group">
                                        <label for="modcode">Announcement</label>
                                        <input type="text" name="news" placeholder="Enter announcement" value="<?php echo $announcement['announcement']; ?>" required>
                                    </div>
                                    
                                    <div class="form_group">
                                        <button name="update" class="create_btn">Update</button>
                                    </div>
                                </form>
                            <?php
                        }
                    ?>
                </div>
                <div class="_container _announcements">
                    <div class="list_head">
                        <h3>_Id</h3>
                        <h3>Announcements</h3>
                        <h3>Actions</h3>
                    </div>
                    <?php if($announcements > 0): ?>
                    <!-- loop through users array -->
                        <?php foreach($announcements as $announcement): ?>
                            <div class="list">
                                <p><?php echo $announcement['_id'];?></p>
                                <p class="name"><?php echo $announcement['announcement'];?></p>
                                <div class="action">
                                    <a href="announcements.php?edit=<?php echo $announcement['_id'];?>">
                                        <i class="fa-solid fa-pen-to-square icon edit"></i>
                                        <span class="tooltip">Edit</span>
                                    </a>
                                    <a href="announcements.php?delete=<?php echo $announcement['_id'] ?>">
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
                                    <a href="announcements.php?page=<?php echo ($page - 1); ?>">
                                        Previous
                                    </a>
                                </p>
                            <?php }
                            else { ?>
                                <p>
                                    <a href="announcements.php?page=<?php echo $page; ?>">
                                        Previous
                                    </a>
                                </p>
                            <?php }
                        ?>
                        
                            
                        
                        <?php for($page = 1; $page <= $num_of_pages; $page++): ?>
                            <p>
                                <a href="announcements.php?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                            </p>
                        <?php endfor; ?>

                        <?php if($page = $page) :?>
                            <p>
                                <a href="announcements.php?page=<?php echo ($page - 1); ?>">
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