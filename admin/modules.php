<?php require_once('./includes/header.php'); ?>
<?php require_once('../config/session.php')?>




<!-- Create -->
<?php
    $modcode = $modname = "";
    // check if isset
    if(isset($_POST['submit'])) {
        // check input
        empty($_POST['modcode']) ? $modcErr = "field required" : $modcode = filter_input(INPUT_POST, 'modcode', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        empty($_POST['modname']) ? $modnErr = "field required" : $modname = filter_input(INPUT_POST, 'modname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // Target the faculty table
        $query = "SELECT * FROM modules WHERE moduleName = '$modname' ";

        // Called the mysqli_query() method
        $result = mysqli_query($conn, $query);
        
        // check if user already exist
        if (mysqli_num_rows($result) > 0){
            $err = "Module Already Exist";
        }
        else {
            // Add to users table in database
            $insert = "INSERT INTO modules (moduleCode, moduleName) VALUES('$modcode', '$modname')";

            // mysqli_query($conn, $insert);
            if(mysqli_query($conn, $insert)) {
                header('Location: modules.php');
            }
            else {
                echo "Error" . mysqli_error($conn);
            }
        }
    }
?>

<!-- Display | Pagination -->
<?php
    $modules_per_page = 4;
    // Getting data from users table
    $selectQuery = "SELECT * FROM modules";
    
    $result = mysqli_query($conn, $selectQuery);

    // number of users
    $num_of_modules = mysqli_num_rows($result);
    // number of page 
    $num_of_pages = ceil($num_of_modules / $modules_per_page);
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
    $start = ($page - 1) * $modules_per_page;

    // Getting specify data from modules table :: using the LIMIT
    $selectQuery = "SELECT * FROM modules LIMIT $start, $modules_per_page";
    
    $result = mysqli_query($conn, $selectQuery);

    $modules = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!-- Edit -->
<?php 

    // Check if form is submit
    if(isset($_POST['update'])) {
        $get_id = $_GET['edit'];
        // echo 'working';
        // Validating
        empty($_POST['modcode']) ? $modcErr = "field required" : $modcode = filter_input(INPUT_POST, 'modcode', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        empty($_POST['modname']) ? $modnErr = "field required" : $modname = filter_input(INPUT_POST, 'modname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // query string
       
        // Update faculty
        $update = "UPDATE modules SET moduleCode = '$modcode', moduleName = '$modname' where _id ='$get_id' ";

        // mysqli_query($conn, $insert);
        if(mysqli_query($conn, $update)) {
            header('Location: modules.php');
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

        $delete = "DELETE FROM modules WHERE _id = '$get_id' ";
        
        // mysqli_query();
        if(mysqli_query($conn, $delete)) {
            header('Location: modules.php');
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
                                <h2>Create New Module</h2>
                                <form action="" method="POST" enctype="multipart/form-data" class="form" >
                                    <div class="form_group">
                                        <label for="modcode">Module Code</label>
                                        <input type="text" name="modcode" placeholder="Enter module code">
                                        <?php if(isset($modcErr)): ?>
                                            <p class="error"><?php echo $modcErr; ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form_group">
                                        <label for="modname">Module Name</label>
                                        <input type="text" name="modname" placeholder="Enter module name">
                                        <?php if(isset($modnErr)): ?>
                                            <p class="error"><?php echo $modnErr; ?></p>
                                        <?php endif; ?>
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
                                <h2>Update Module</h2>
                                <form action="" method="POST" enctype="multipart/form-data" class="form" >
                                <?php
                                    $get_id = $_GET['edit'];

                                    $query = "SELECT * FROM modules WHERE _id = '$get_id' ";
                                    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
                                    $module = mysqli_fetch_array($result);
                                ?>
                                    <div class="form_group">
                                        <label for="modcode">Module Code</label>
                                        <input type="text" name="modcode" placeholder="Enter module code" value="<?php echo $module['moduleCode']; ?>">
                                        <?php if(isset($modcErr)): ?>
                                            <p class="error"><?php echo $modcErr; ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form_group">
                                        <label for="modname">Program Name</label>
                                        <input type="text" name="modname" placeholder="Enter module name" value="<?php echo $module['moduleName']; ?>">
                                        <?php if(isset($modnErr)): ?>
                                            <p class="error"><?php echo $modnErr; ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form_group">
                                        <button name="update" class="create_btn">Update</button>
                                    </div>
                                </form>
                            <?php
                        }
                    ?>
                </div>
                <div class="_container">
                    <div class="list_head">
                        <h3>_Id</h3>
                        <h3>Module Code</h3>
                        <h3>Module Name</h3>
                        <h3>Actions</h3>
                    </div>
                    <?php if($modules > 0): ?>
                        <!-- loop through users array -->
                        <?php foreach($modules as $module): ?>
                            <div class="list">
                                <p><?php echo $module['_id'];?></p>
                                <p><?php echo $module['moduleCode'];?></p>
                                <p class="name"><?php echo $module['moduleName'];?></p>
                                <div class="action">
                                    <a href="modules.php?edit=<?php echo $module['_id'];?>">
                                        <i class="fa-solid fa-pen-to-square icon edit"></i>
                                        <span class="tooltip">Edit</span>
                                    </a>
                                    <a href="modules.php?delete=<?php echo $module['_id'] ?>">
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
                                    <a href="modules.php?page=<?php echo ($page - 1); ?>">
                                        Previous
                                    </a>
                                </p>
                            <?php }
                            else { ?>
                                <p>
                                    <a href="modules.php?page=<?php echo $page; ?>">
                                        Previous
                                    </a>
                                </p>
                            <?php }
                        ?>
                        
                            
                        
                        <?php for($page = 1; $page <= $num_of_pages; $page++): ?>
                            <p>
                                <a href="modules.php?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                            </p>
                        <?php endfor; ?>

                        <?php if($page = $page) :?>
                            <p>
                                <a href="modules.php?page=<?php echo ($page - 1); ?>">
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