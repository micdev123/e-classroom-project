<?php require_once('./includes/header.php'); ?>
<?php require_once('../config/session.php')?>



<!-- Create -->
<?php
    $fcode = $fname = "";
    // check if isset
    if(isset($_POST['submit'])) {
        // check input
        empty($_POST['fcode']) ? $fcErr = "field required" : $fcode = filter_input(INPUT_POST, 'fcode', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        empty($_POST['fname']) ? $fnErr = "field required" : $fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // Target the faculty table
        $query = "SELECT * FROM faculties WHERE facultyName = '$fname' ";

        // Called the mysqli_query() method
        $result = mysqli_query($conn, $query);
        
        // check if user already exist
        if (mysqli_num_rows($result) > 0){
            $err = "Faculty Already Exist";
        }
        else {
            // Add to users table in database
            $insert = "INSERT INTO faculties (facultyCode, facultyName, photo) VALUES('$fcode', '$fname', 'NO-IMAGE-AVAILABLE.jpg')";

            // mysqli_query($conn, $insert);
            if(mysqli_query($conn, $insert)) {
                header('Location: faculties.php');
            }
            else {
                echo "Error" . mysqli_error($conn);
            }
        }
    }
?>


<!-- load -->
<?php
    if(isset($_POST['save'])) {
        // $data_values = array();
        $program_ids = $_POST['programId'];

        foreach($program_ids as $key => $value) {
            $faculty_id = $_GET['faculty'];
            $faculty_code = $_POST['facultyCode'][$key];
            $program_id = $_POST['programId'][$key];
            $program_code = $_POST['programCode'][$key];
            
            // echo $faculty_id . '' . $faculty_code . '' .$program_id . '' . $program_code ."<br>";

            $query = "SELECT * FROM facultyprograms WHERE faculty_id = '$faculty_id' &&  program_id = '$program_id' ";

                // Called the mysqli_query() method
            $result = mysqli_query($conn, $query);
            
            // check if user already exist
            if (mysqli_num_rows($result) > 0){
                $err = "Data Already Exist";
            }
            else {
                $insert = "INSERT INTO facultyprograms (faculty_id, faculty_code, program_id, program_code) VALUES('$faculty_id', '$faculty_code', '$program_id', '$program_code')";
                $result = mysqli_query($conn, $insert);
            }
            header('Location: faculties.php');
        }
    }

?>



<!-- Display | Pagination -->
<?php
    $faculties_per_page = 4;
    // Getting data from users table
    $selectQuery = "SELECT * FROM faculties";
    
    $result = mysqli_query($conn, $selectQuery);

    // number of users
    $num_of_faculties = mysqli_num_rows($result);
    // number of page 
    $num_of_pages = ceil($num_of_faculties / $faculties_per_page);
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
    $start = ($page - 1) * $faculties_per_page;

    // Getting specify data from users table :: using the LIMIT
    $selectQuery = "SELECT * FROM faculties LIMIT $start, $faculties_per_page";
    
    $result = mysqli_query($conn, $selectQuery);

    $faculties = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!-- Edit faculty -->
<?php 

    // Check if form is submit
    if(isset($_POST['update'])) {
        $get_id = $_GET['edit'];

        // echo 'working';
        // Validating
        empty($_POST['fcode']) ? $fcErr = "field required" : $fcode = filter_input(INPUT_POST, 'fcode', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        empty($_POST['fname']) ? $fnErr = "field required" : $fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // query string
       
        // Update faculty
        $update = "UPDATE faculties SET facultyCode = '$fcode', facultyName = '$fname' where _id ='$get_id' ";

        // mysqli_query($conn, $insert);
        if(mysqli_query($conn, $update)) {
            header('Location: faculties.php');
        }
        else {
            echo "Error" . mysqli_error($conn);
        }
        
    }
?>


<!-- Delete faculty -->
<?php 
    if (isset($_GET['delete'])) {
        $get_id = $_GET['delete'];

        $delete = "DELETE FROM faculties WHERE _id = '$get_id' ";
        
        // mysqli_query();
        if(mysqli_query($conn, $delete)) {
            header('Location: faculties.php');
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
                <!-- modal -->
                <?php if(isset($_GET['faculty'])) : ?>
                    <div class="modal_container">
                        <div class="modal_content">
                            <h3>Load Programs</h3>
                            <form action="" method="POST">
                                <div class="select-field">
                                    <input type="text" placeholder="Choose programs" id="" class="input-selector">
                                    <i class='bx bxs-chevron-down select-arrow icon' ></i>
                                </div>
                                <!---------List of checkboxes and options----------->
                                <div class="modal-list">
                                    <?php
                                        $get__id = $_GET['faculty']; 

                                        $query = "SELECT * FROM programs";
                                        $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

                                        $query_ = "SELECT * FROM faculties WHERE _id = '$get__id'";
                                        $result_ = mysqli_query($conn, $query_) or die(mysqli_error($conn));
                                        $faculty = mysqli_fetch_array($result_);

                                        while($program = mysqli_fetch_array($result)){
                                            ?>
                                                <label for="<?php echo $program['_id']; ?>" class="task">
                                                    <input type="checkbox" name="programId[]" value="<?php echo $program['_id']; ?>" class="checkbox" >

                                                    <input type="hidden" name="programCode[]" value="<?php echo $program['programCode']; ?>" />

                                                    <input type="hidden" name="facultyCode[]" value="<?php echo $faculty['facultyCode'];?>"/>

                                                    <span class="span"><?php echo $program['programCode']; ?></span>
                                                </label>
                                            <?php 
                                        } 
                                    ?>  
                                </div>
                                <div class="btns">
                                    <button class="save_btn" name="save">Save</button>
                                    <button class="cancel_btn">
                                        <a href="faculties.php">
                                            Cancel
                                        </a>
                                   </button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="create">
                    <?php
                        // Create faculty form
                        if(!isset($_GET['edit'])) {
                            ?>
                                <h2>Create New Faculty</h2>
                                <form action="" method="POST" enctype="multipart/form-data" class="form" >
                                    <div class="form_group">
                                        <label for="fcode">Faculty Code</label>
                                        <input type="text" name="fcode" placeholder="Enter faculty code">
                                        <?php if(isset($fcErr)): ?>
                                            <p class="error"><?php echo $fcErr; ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form_group">
                                        <label for="fname">Faculty Name</label>
                                        <input type="text" name="fname" placeholder="Enter faculty name">
                                        <?php if(isset($fnErr)): ?>
                                            <p class="error"><?php echo $fnErr; ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form_group">
                                        <button name="submit" class="create_btn">Create</button>
                                    </div>
                                </form>
                            <?php
                        }
                        else {
                            // Edit Faculty form
                            ?>
                                <h2>Update Faculty</h2>
                                <form action="" method="POST" enctype="multipart/form-data" class="form" >
                                <?php
                                    $get_id = $_GET['edit'];

                                    $query = "SELECT * FROM faculties WHERE _id = '$get_id' ";
                                    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
                                    $faculty = mysqli_fetch_array($result);
                                ?>
                                    <div class="form_group">
                                        <label for="fcode">Faculty Code</label>
                                        <input type="text" name="fcode" placeholder="Enter faculty code" value="<?php echo $faculty['facultyCode']; ?>">
                                        <?php if(isset($fcErr)): ?>
                                            <p class="error"><?php echo $fcErr; ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form_group">
                                        <label for="fname">Faculty Name</label>
                                        <input type="text" name="fname" placeholder="Enter faculty name" value="<?php echo $faculty['facultyName']; ?>">
                                        <?php if(isset($fnErr)): ?>
                                            <p class="error"><?php echo $fnErr; ?></p>
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
                    <div class="search">
                        <input type="text" class="search_filter" placeholder="search classroom">
                    </div>
                    <div class="list_head">
                        <h3>_Id</h3>
                        <h3>Faculty Code</h3>
                        <h3>Faculty Name</h3>
                        <h3>Actions</h3>
                    </div>
                    <?php if($faculties > 0): ?>
                        <!-- loop through users array -->
                        <?php foreach($faculties as $faculty): ?>
                            <div class="list">
                                <p><?php echo $faculty['_id'];?></p>
                                <p class="name"><?php echo $faculty['facultyCode'];?></p>
                                <p><?php echo $faculty['facultyName'];?></p>
                                <div class="action">
                                    <a href="faculties.php?faculty=<?php echo $faculty['_id'];?>">
                                        <i class="fas fa-spinner icon"></i>
                                        <span class="tooltip">Load Programs</span>
                                    </a>
                                    
                                    <a href="faculties.php?edit=<?php echo $faculty['_id'];?>">
                                        <i class="fa-solid fa-pen-to-square icon edit"></i>
                                        <span class="tooltip">Edit</span>
                                    </a>
                                    <a href="faculties.php?delete=<?php echo $faculty['_id'] ?>">
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
                                    <a href="faculties.php?page=<?php echo ($page - 1); ?>">
                                        Previous
                                    </a>
                                </p>
                            <?php }
                            else { ?>
                                <p>
                                    <a href="faculties.php?page=<?php echo $page; ?>">
                                        Previous
                                    </a>
                                </p>
                            <?php }
                        ?>
                        
                            
                        
                        <?php for($page = 1; $page <= $num_of_pages; $page++): ?>
                            <p>
                                <a href="faculties.php?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                            </p>
                        <?php endfor; ?>

                        <?php if($page = $page) :?>
                            <p>
                                <a href="faculties.php?page=<?php echo ($page - 1); ?>">
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