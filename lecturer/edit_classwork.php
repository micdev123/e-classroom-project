<?php require_once('./includes/header.php'); ?>
<?php require_once('../config/session.php'); ?>

<?php 
    
    $selectQuery = "SELECT * FROM users WHERE _id = '$session_id' ";
    $result = mysqli_query($conn, $selectQuery);
    $lecturer = mysqli_fetch_array($result);
    // 
?>

<!-- create classwork -->
<?php
    $get_id = ($_GET['classroom']);
    $split = explode('_', $get_id);
    // print_r($split);
    $class_code = base64_decode($split[0]);
    $module_id = base64_decode($split[1]);
    $lesson_id = $split[2];
    // echo $class_code . '<br>' . $module_id . '<br>';

    // targetting first 2 item in the $split array
    $get__id = $split[0].'_'.$split[1];
    // echo $get__id;

    // Getting lecturer id
    $selectQuery = "SELECT * FROM users WHERE _id = '$session_id' ";
    $result = mysqli_query($conn, $selectQuery);
    $lecturer_id = mysqli_fetch_array($result);
    $lecturer_id = $lecturer_id['_id'];

    $query_class = "SELECT * FROM classrooms WHERE classCode = '$class_code' ";
    $result_ = mysqli_query($conn, $query_class);
    $class_id = mysqli_fetch_array($result_);
    $class_id = $class_id['_id'];

    $location = 'classworks.php' . '?classroom=' . $get__id;

    // echo $class_code . '<br>' . $module_id;
    if(isset($_POST['save'])) {
        $allowed_ext = array('pdf', 'docx', 'doc', 'ppt', 'pptx');

        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $classwork_type = $_POST['type'];

        $initial_upload = $_POST['initial_upload'];
        echo $initial_upload;

        if(!empty($_FILES['upload']['name'])) {
            $file_name = $_FILES['upload']['name'];
            $file_size = $_FILES['upload']['size'];
            $file_temp = $_FILES['upload']['tmp_name'];

            // Making unique file name to aviod overriding
            $unique_filename = rand(100, 1000).'_'. $file_name;

            $upload_dir = "../uploads/${unique_filename}";

            $file_ext = explode('.', $unique_filename);

            $file_ext = strtolower(end($file_ext));

            // validate file ext
            if(in_array($file_ext, $allowed_ext)) {
                if($file_size <= 12000000)  {
                    move_uploaded_file($file_temp, $upload_dir);
                }
                else {
                    $message[] = 'File too large';
                }
            }
            else {
                $message[] = 'Invalid file ext';
            }

            // Update
            $update = "UPDATE classworks SET title = '$title', description = '$description', type = '$classwork_type', class_id = '$class_id', upload = '$unique_filename' where classwork_id = '$lesson_id' ";

            // mysqli_query($conn, $insert);
            if(mysqli_query($conn, $update)) {
                header('Location: ' . $location);
            }
            else {
                echo "Error" . mysqli_error($conn);
            }
        
        } 
        else {
            // Update
            $update = "UPDATE classworks SET title = '$title', description = '$description', type = '$classwork_type', class_id = '$class_id', upload = '$initial_upload' where classwork_id = '$lesson_id' ";

            // mysqli_query($conn, $insert);
            if(mysqli_query($conn, $update)) {
                header('Location: ' . $location);
            }
            else {
                echo "Error" . mysqli_error($conn);
            }
        }
        
    }

?>

<body>
    <?php require_once('./includes/topNav.php'); ?>
    <main class="container">
        <?php require_once('./includes/sideNav.php'); ?>
        <div class="home" id="dashboard">
            <div class="_container">
                <div class="_container__">
                    <div class="classworks">
                        <?php if(isset($message)): ?>
                            <?php foreach($message as $msg): ?>
                                <p class="error"><?php echo $msg; ?></p>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <div class="classwork_form">
                            <h2>Update Classwork</h2>
                            <form action="" method="POST" enctype="multipart/form-data" class="form">
                                <?php
                                    $get_id = ($_GET['classroom']);
                                    $split = explode('_', $get_id);
                                    $lesson_id = $split[2];

                                    $query = "SELECT * FROM classworks WHERE classwork_id = '$lesson_id' ";
                                    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
                                    $lesson = mysqli_fetch_array($result);
                                ?>
                                <div class="form_group">
                                    <label for="">Title</label>
                                    <input type="text" name="title" value="<?php echo $lesson['title']; ?>" placeholder="Enter classwork title" required>
                                </div>
                                <div class="form_group textarea">
                                    <label for="">Description</label>
                                    <textarea name="description" id="editor" required>
                                        <?php echo $lesson['description']; ?>
                                    </textarea>
                                </div>

                                <div class="form_group">
                                    <select name="type" id="" required>
                                        <option value="<?php echo $lesson['type']; ?>">
                                            <?php echo $lesson['type']; ?>
                                        </option>
                                        <option value="Lesson">Lessons</option>
                                        <option value="Assignment">Assignments | Others</option>
                                    </select>
                                </div>

                                <div class="form_group">
                                    <label for="">Upload classwork</label>
                                    <input type="file" name="upload" class="upload">
                                    <input type="text" name="initial_upload" value="<?php echo $lesson['upload']; ?>" hidden>
                                </div>
                                <div class="btns">
                                    <button name="save" class="save_btn">Save</button>
                                    <button name="cancel" class="cancel_btn">Cancel</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="./ckeditor/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('editor');
    </script>
<?php require_once('./includes/footer.php'); ?>