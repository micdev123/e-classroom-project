<?php require_once('./includes/header.php'); ?>
<?php require_once('../config/session.php')?>


<!-- Create -->
<?php
    $pcode = $pname = "";
    // check if isset
    if(isset($_POST['submit'])) {
        // check input
        empty($_POST['pcode']) ? $pcErr = "field required" : $pcode = filter_input(INPUT_POST, 'pcode', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        empty($_POST['pname']) ? $pnErr = "field required" : $pname = filter_input(INPUT_POST, 'pname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // Target the programs table
        $query = "SELECT * FROM programs WHERE programName = '$pname' ";

        // Called the mysqli_query() method
        $result = mysqli_query($conn, $query);
        
        // check if user already exist
        if (mysqli_num_rows($result) > 0){
            $err = "Program Already Exist";
        }
        else {
            // Add to users table in database
            $insert = "INSERT INTO programs (programCode, programName) VALUES('$pcode', '$pname')";

            // mysqli_query($conn, $insert);
            if(mysqli_query($conn, $insert)) {
                header('Location: programs.php');
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
        $classroom_ids = $_POST['classroomId'];

        foreach($classroom_ids as $key => $value) {
            $program_id = $_GET['programsload'];
            $program_code = $_POST['programCode'][$key];
            $class_id = $_POST['classroomId'][$key];
            $class_code = $_POST['classCode'][$key];
            
            // echo $class_id . '' . $class_code . '' .$program_id . '' . $program_code ."<br>";

            $query = "SELECT * FROM programclassrooms WHERE program_id = '$program_id' &&  class_id = '$class_id' ";

                // Called the mysqli_query() method
            $result = mysqli_query($conn, $query);
            
            // check if user already exist
            if (mysqli_num_rows($result) > 0){
                $err = "Data Already Exist";
            }
            else {
            
                $insert = "INSERT INTO programclassrooms (program_id, program_code, class_id, class_code) VALUES('$program_id', '$program_code', '$class_id', '$class_code')";
                $result = mysqli_query($conn, $insert);
            }
            header('Location: programs.php');
        }
    }

?>



<!-- Display | Pagination -->
<?php
    $programs_per_page = 4;
    // Getting data from users table
    $selectQuery = "SELECT * FROM programs";
    
    $result = mysqli_query($conn, $selectQuery);

    // number of users
    $num_of_programs = mysqli_num_rows($result);
    // number of page 
    $num_of_pages = ceil($num_of_programs / $programs_per_page);
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
    $start = ($page - 1) * $programs_per_page;

    // Getting specify data from users table :: using the LIMIT
    $selectQuery = "SELECT * FROM programs LIMIT $start, $programs_per_page";
    
    $result = mysqli_query($conn, $selectQuery);

    $programs = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!-- Edit -->
<?php 

    // Check if form is submit
    if(isset($_POST['update'])) {
        $get_id = $_GET['edit'];
        // echo 'working';
        // Validating
        empty($_POST['pcode']) ? $pcErr = "field required" : $pcode = filter_input(INPUT_POST, 'pcode', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        empty($_POST['pname']) ? $pnErr = "field required" : $pname = filter_input(INPUT_POST, 'pname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // query string
       
        // Update faculty
        $update = "UPDATE programs SET programCode = '$pcode', programName = '$pname' where _id ='$get_id' ";

        // mysqli_query($conn, $insert);
        if(mysqli_query($conn, $update)) {
            header('Location: programs.php');
        }
        else {
            echo "Error" . mysqli_error($conn);
        }
        
    }
?>


<!-- Delete -->
<?php 
    if (isset($_GET['delete'])) {
        $get_id = $_GET['delete'];

        $delete = "DELETE FROM programs WHERE _id = '$get_id' ";
        
        // mysqli_query();
        if(mysqli_query($conn, $delete)) {
            header('Location: programs.php');
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
        <div class="home_" id="programs">
            <div class="container__">
                <!-- modal -->
                <?php if(isset($_GET['programsload'])) : ?>
                    <div class="modal_container">
                        <div class="modal_content">
                            <h3>Load Classrooms</h3>
                            <form action="" method="POST">
                                <div class="select-field">
                                    <input type="text" placeholder="Choose classrooms" id="" class="input-selector">
                                    <i class='bx bxs-chevron-down select-arrow icon' ></i>
                                </div>
                                <!---------List of checkboxes and options----------->
                                <div class="modal-list">
                                    <?php
                                        $get__id = $_GET['programsload']; 
                                        $query = "SELECT * FROM classrooms";
                                        $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

                                        $query_ = "SELECT * FROM programs WHERE _id = '$get__id'";
                                        $result_ = mysqli_query($conn, $query_) or die(mysqli_error($conn));
                                        $program = mysqli_fetch_array($result_);

                                        while($classroom = mysqli_fetch_array($result)){
                                            ?>
                                                <label for="<?php echo $classroom['_id']; ?>" class="task">
                                                    <input type="checkbox" name="classroomId[]" value="<?php echo $classroom['_id']; ?>" class="checkbox" id="<?php echo $classroom['_id']; ?>">
                                                    <input type="hidden" name="classCode[]" value="<?php echo $classroom['classCode']; ?>" />

                                                    <input type="hidden" name="programCode[]" value="<?php echo $program['programCode'];?>"/>

                                                    <span class="span"><?php echo $classroom['classCode']; ?></span>
                                                </label>
                                            <?php 
                                        } 
                                    ?>  
                                </div>
                                <div class="btns">
                                    <button class="save_btn" name="save">Save</button>
                                    <button class="cancel_btn">
                                        <a href="programs.php">
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
                        // Create program form
                        if(!isset($_GET['edit'])) {
                            ?>
                                <h2>Create New Program</h2>
                                <form action="" method="POST" enctype="multipart/form-data" class="form" >
                                    <div class="form_group">
                                        <label for="pcode">Program Code</label>
                                        <input type="text" name="pcode" placeholder="Enter program code">
                                        <?php if(isset($pcErr)): ?>
                                            <p class="error"><?php echo $pcErr; ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form_group">
                                        <label for="pname">Program Name</label>
                                        <input type="text" name="pname" placeholder="Enter program name">
                                        <?php if(isset($pnErr)): ?>
                                            <p class="error"><?php echo $pnErr; ?></p>
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
                                <h2>Update Program</h2>
                                <form action="" method="POST" enctype="multipart/form-data" class="form" >
                                <?php
                                    $get_id = $_GET['edit'];

                                    $query = "SELECT * FROM programs WHERE _id = '$get_id' ";
                                    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
                                    $program = mysqli_fetch_array($result);
                                ?>
                                    <div class="form_group">
                                        <label for="pcode">Program Code</label>
                                        <input type="text" name="pcode" placeholder="Enter program code" value="<?php echo $program['programCode']; ?>">
                                        <?php if(isset($pcErr)): ?>
                                            <p class="error"><?php echo $pcErr; ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form_group">
                                        <label for="pname">Program Name</label>
                                        <input type="text" name="pname" placeholder="Enter program name" value="<?php echo $program['programName']; ?>">
                                        <?php if(isset($pnErr)): ?>
                                            <p class="error"><?php echo $pnErr; ?></p>
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
                        <h3>Program Code</h3>
                        <h3>Program Name</h3>
                        <h3>Actions</h3>
                    </div>
                    <?php if($programs > 0): ?>
                        <!-- loop through users array -->
                        <?php foreach($programs as $program): ?>
                            <div class="list">
                                <p><?php echo $program['_id'];?></p>
                                <p class="name"><?php echo $program['programCode'];?></p>
                                <p><?php echo $program['programName'];?></p>
                                <div class="action">
                                    <a href="programs.php?programsload=<?php echo $program['_id'];?>">
                                        <i class="fas fa-spinner icon"></i>
                                        <span class="tooltip">Load Classrooms</span>
                                    </a>
                                    
                                    <a href="programs.php?edit=<?php echo $program['_id'];?>">
                                        <i class="fa-solid fa-pen-to-square icon edit"></i>
                                        <span class="tooltip">Edit</span>
                                    </a>
                                    <a href="programs.php?delete=<?php echo $program['_id'] ?>">
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
                                    <a href="programs.php?page=<?php echo ($page - 1); ?>">
                                        Previous
                                    </a>
                                </p>
                            <?php }
                            else { ?>
                                <p>
                                    <a href="programs.php?page=<?php echo $page; ?>">
                                        Previous
                                    </a>
                                </p>
                            <?php }
                        ?>
                        
                            
                        
                        <?php for($page = 1; $page <= $num_of_pages; $page++): ?>
                            <p>
                                <a href="programs.php?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                            </p>
                        <?php endfor; ?>

                        <?php if($page = $page) :?>
                            <p>
                                <a href="programs.php?page=<?php echo ($page - 1); ?>">
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