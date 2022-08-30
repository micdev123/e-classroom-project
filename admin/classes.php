<?php require_once('./includes/header.php'); ?>
<?php require_once('../config/session.php'); ?>


<!-- Create -->
<?php
    // check if isset
    if(isset($_POST['submit'])) {
        // check input
        $faculty = filter_input(INPUT_POST, 'faculty', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $program = filter_input(INPUT_POST, 'program', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $semester = filter_input(INPUT_POST, 'semester', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $section = filter_input(INPUT_POST, 'section', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $classcode = $program . $section;

        // Target the faculty table
        $query = "SELECT * FROM classrooms WHERE classCode = '$classcode' ";

        // Called the mysqli_query() method
        $result = mysqli_query($conn, $query);
        
        // check if user already exist
        if (mysqli_num_rows($result) > 0){
            $err = "Classroom Already Exist";
        }
        else {
            // Add to users table in database
            $insert = "INSERT INTO classrooms (classCode, program, section, semester, faculty) VALUES('$classcode', '$program', '$section', '$semester', '$faculty')";

            // mysqli_query($conn, $insert);
            if(mysqli_query($conn, $insert)) {
                header('Location: classes.php');
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
        $module_ids = $_POST['loadmodule'];

        foreach($module_ids as $key => $value) {
            $class_id = $_GET['loadModules'];
            $class_code = $_POST['classCode'][$key];
            $module_id = $_POST['loadmodule'][$key];
            $module_code = $_POST['moduleCode'][$key];

            // echo $class_id . '' . $class_code . '' .$module_id . '' . $module_code ."<br>";
            $query = "SELECT * FROM classmodules WHERE class_id = '$class_id' && module_id = '$module_id' ";

                // Called the mysqli_query() method
            $result = mysqli_query($conn, $query);
            
            // check if user already exist
            if (mysqli_num_rows($result) > 0){
                $err = "Data Already Exist";
            }
            else {
            
                $insert = "INSERT INTO classmodules (class_id, class_code, module_id, module_code) VALUES('$class_id', '$class_code', '$module_id', '$module_code')";
                $result = mysqli_query($conn, $insert);
            }
            header('Location: programs.php');
        }
    }

?>


<!-- Display | Pagination -->
<?php
    $classrooms_per_page = 4;
    // Getting data from users table
    $selectQuery = "SELECT * FROM classrooms";
    
    $result = mysqli_query($conn, $selectQuery);

    // number of users
    $num_of_classrooms = mysqli_num_rows($result);
    // number of page 
    $num_of_pages = ceil($num_of_classrooms / $classrooms_per_page);
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
    $start = ($page - 1) * $classrooms_per_page;

    // Getting specify data from users table :: using the LIMIT
    $selectQuery = "SELECT * FROM classrooms LIMIT $start, $classrooms_per_page";
    
    $result = mysqli_query($conn, $selectQuery);

    $classrooms = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!-- Edit | Update -->
<?php 
    // Check if form is submit
    if(isset($_POST['update'])) {
        $get_id = $_GET['edit'];

        $faculty = filter_input(INPUT_POST, 'faculty', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $program = filter_input(INPUT_POST, 'program', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $semester = filter_input(INPUT_POST, 'semester', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $section = filter_input(INPUT_POST, 'section', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $classcode = $program . $section;
        // Update faculty
        $update = "UPDATE classrooms SET classCode = '$classcode', program = '$program', section = '$section', semester = '$semester', faculty = '$faculty'  where _id ='$get_id' ";

        // mysqli_query($conn, $insert);
        if(mysqli_query($conn, $update)) {
            header('Location: classes.php');
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

        $delete = "DELETE FROM classrooms WHERE _id = '$get_id' ";
        
        // mysqli_query();
        if(mysqli_query($conn, $delete)) {
            header('Location: classes.php');
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
                <?php if(isset($_GET['loadModules'])) : ?>
                    <div class="modal_container">
                        <div class="modal_content">
                            <h3>Load Modules</h3>
                            <form action="" method="POST">
                                <div class="select-field">
                                    <input type="text" placeholder="Choose modules" id="" class="input-selector">
                                    <i class='bx bxs-chevron-down select-arrow icon' ></i>
                                </div>
                                <!---------List of checkboxes and options----------->
                                <div class="modal-list">
                                    <?php
                                        $get__id = $_GET['loadModules']; 
                                        $query = "SELECT * FROM modules";
                                        $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

                                        $query_ = "SELECT * FROM classrooms WHERE _id = '$get__id'";
                                        $result_ = mysqli_query($conn, $query_) or die(mysqli_error($conn));
                                        $classroom = mysqli_fetch_array($result_);

                                        while($module = mysqli_fetch_array($result)){
                                            ?>
                                                <label for="<?php echo $module['_id']; ?>" class="task">
                                                    <input type="checkbox" name="loadmodule[]" value="<?php echo $module['_id']; ?>" class="checkbox" id="<?php echo $module['_id']; ?>">
                                                    <input type="hidden" name="moduleCode[]" value="<?php echo $module['moduleCode']; ?>" />

                                                    <input type="hidden" name="classCode[]" value="<?php echo $classroom['classCode'];?>"/>

                                                    <span class="span"><?php echo $module['moduleName']; ?></span>
                                                </label>
                                            <?php 
                                        } 
                                    ?>  
                                </div>
                                <div class="btns">
                                    <button class="save_btn" name="save">Save</button>
                                    <button class="cancel_btn">
                                        <a href="classes.php">
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
                                <h2>Create New Classroom</h2>
                                <form action="" method="POST" enctype="multipart/form-data" class="form" >
                                    <div class="form_group">
                                        <label for="faculty">Faculties</label>
                                        <select name="faculty" id="">
                                            <option value="">Select Faculty</option>
                                            <?php
                                                $query = "SELECT * FROM faculties";
                                                $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
                                                while($faculty = mysqli_fetch_array($result)){
                                                    ?>
                                                        <option value="<?php echo $faculty['facultyCode']; ?>"><?php echo $faculty['facultyCode']; ?></option>
                                                    <?php 
                                                } 
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form_group">
                                        <label for="program">Programs</label>
                                        <select name="program" id="">
                                            <option value="">Select Program</option>
                                            <?php
                                                $query = "SELECT * FROM programs";
                                                $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
                                                while($program = mysqli_fetch_array($result)){
                                                    ?>
                                                        <option value="<?php echo $program['programCode']; ?>"><?php echo $program['programCode']; ?></option>
                                                    <?php 
                                                } 
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form_group">
                                        <label for="semester">Semesters</label>
                                        <select name="semester" id="">
                                            <option value="">Select Semester</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                        </select>
                                    </div>
                            
                                    <div class="form_group">
                                        <label for="class_sec">Section</label>
                                        <input type="text" name="section" placeholder="Enter class section" required>
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
                                <h2>Update Classroom</h2>
                                <form action="" method="POST" enctype="multipart/form-data" class="form" >
                                <?php
                                    $get_id = $_GET['edit'];
                                    $query = "SELECT * FROM classrooms WHERE _id = '$get_id' ";
                                    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
                                    $classroom = mysqli_fetch_array($result);
                                ?>
                                    <div class="form_group">
                                        <label for="faculty">Faculties</label>
                                        <select name="faculty" id="">
                                            <option value="<?php echo $classroom['faculty']; ?>">
                                                <?php echo $classroom['faculty']; ?>
                                            </option>
                                            <?php
                                                $query = "SELECT * FROM faculties";
                                                $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
                                                while($faculty = mysqli_fetch_array($result)){
                                                    ?>
                                                        <option value="<?php echo $faculty['facultyCode']; ?>"><?php echo $faculty['facultyCode']; ?></option>
                                                    <?php 
                                                } 
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form_group">
                                        <label for="program">Programs</label>
                                        <select name="program" id="">
                                            <option value="<?php echo $classroom['program']; ?>">
                                                <?php echo $classroom['program']; ?>
                                            </option>
                                            <?php
                                                $query = "SELECT * FROM programs";
                                                $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
                                                while($program = mysqli_fetch_array($result)){
                                                    ?>
                                                        <option value="<?php echo $program['programCode']; ?>"><?php echo $program['programCode']; ?></option>
                                                    <?php 
                                                } 
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form_group">
                                        <label for="semester">Semesters</label>
                                        <select name="semester" id="">
                                            <option value="<?php echo $classroom['semester']; ?>">
                                                <?php echo $classroom['semester']; ?>
                                            </option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                        </select>
                                    </div>
                                    <div class="form_group">
                                        <label for="class_sec">Section</label>
                                        <input type="text" name="section" placeholder="Enter class section" value="<?php echo $classroom['section']; ?>" required>
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
                        <h3>Classroom</h3>
                        <h3>Faculty</h3>
                        <h3>Actions</h3>
                    </div>
                    <?php if($classrooms > 0): ?>
                        <!-- loop through users array -->
                        <?php foreach($classrooms as $classroom): ?>
                            <div class="list">
                                <p><?php echo $classroom['_id'];?></p>
                                <p class="name"><?php echo $classroom['classCode'];?></p>
                                <p><?php echo $classroom['faculty'];?></p>
                                <div class="action">
                                    <a href="classes.php?loadModules=<?php echo $classroom['_id'];?>">
                                        <i class="fas fa-spinner icon"></i>
                                        <span class="tooltip">Load Modules</span>
                                    </a>
                                    
                                    <a href="classes.php?edit=<?php echo $classroom['_id'];?>">
                                        <i class="fa-solid fa-pen-to-square icon edit"></i>
                                        <span class="tooltip">Edit</span>
                                    </a>
                                    <a href="classes.php?delete=<?php echo $classroom['_id'] ?>">
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
                                    <a href="classes.php?page=<?php echo ($page - 1); ?>">
                                        Previous
                                    </a>
                                </p>
                            <?php }
                            else { ?>
                                <p>
                                    <a href="classes.php?page=<?php echo $page; ?>">
                                        Previous
                                    </a>
                                </p>
                            <?php }
                        ?>
                        
                            
                        
                        <?php for($page = 1; $page <= $num_of_pages; $page++): ?>
                            <p>
                                <a href="classes.php?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                            </p>
                        <?php endfor; ?>

                        <?php if($page = $page) :?>
                            <p>
                                <a href="classes.php?page=<?php echo ($page - 1); ?>">
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