<?php require_once('./includes/header.php'); ?>
<?php require_once('../config/session.php'); ?>

<?php 
    
    $selectQuery = "SELECT * FROM users WHERE _id = '$session_id' ";
    $result = mysqli_query($conn, $selectQuery);
    $lecturer = mysqli_fetch_array($result);
    // 
?>


<?php
    $get_id = ($_GET['classroom']);
    $split = explode('_', $get_id);
    // print_r($split);
    $class_code = base64_decode($split[0]);
    $module_id = base64_decode($split[1]);
    $classwork_id = $split[2];
    // echo $class_code . '<br>' . $module_id . '<br>';

    // targetting first 2 item in the $split array
    $get__id = $split[0].'_'.$split[1];
    // echo $get__id;

    // Getting
    $selectQuery = "SELECT *, fullname FROM classworks INNER JOIN users ON classworks.lecturer_id = users._id WHERE classworks.classwork_id = '$classwork_id'  ";
    $result = mysqli_query($conn, $selectQuery);
    // $lecturer_id = mysqli_fetch_array($result);
    // $lecturer_id = $lecturer_id['_id'];
    $classwork = mysqli_fetch_array($result);
    $lecturer_id = $classwork['_id'];
    // echo $lecturer_id;

    // $view_work = "../uploads/" . $classwork['upload'];
    $upload = $classwork['upload'];
    // echo $upload;
    // print_r($view_work);

    
?>

<!-- upload -->
<?php
    require_once("../vendor/autoload.php");
    use \ConvertApi\ConvertApi;
    ConvertApi::setApiSecret('wy5eKWxYmy3WIUbC');
    
    $location = 'submit_assignment.php' . '?classroom=' . $get_id;

    if(isset($_POST['submit'])) {
        // $allowed_ext = array('pdf', 'docx', 'doc', 'ppt', 'pptx');
        $allowed_ext = 'pdf';
        if(!empty($_FILES['upload']['name'])) {
            $file_name = $_FILES['upload']['name'];
            $file_size = $_FILES['upload']['type'];
            $file_temp = $_FILES['upload']['tmp_name'];

            // Making unique file name to aviod overriding
            $unique_filename = rand(100, 1000).'_'. $file_name;

            $upload_dir = "../uploads/${unique_filename}";

            $file_ext = explode('.', $unique_filename);

            $file_ext = strtolower(end($file_ext));

            // validate file ext
            if($file_ext = $allowed_ext) {
                move_uploaded_file($file_temp, $upload_dir);

                $insert = "INSERT INTO assignments (assignment_id, assignment, module_id, student_id, class_code, lecturer_id) VALUES ('$classwork_id', '$unique_filename', '$module_id', '$session_id', '$class_code', '$lecturer_id')";

                mysqli_query($conn, $insert);

                // Convert file to png
                $converted = ConvertApi::convert('png', [
                        'File' => $upload_dir ,
                    ], 'pdf'
                );
                $image_format = "../image_format";
                $converted->saveFiles($image_format);

                // get content of the converted file
                $contents = $converted->getFile()->getContents();

                // Open file
                $file_open = fopen($image_format, "w");
                // now write on file
                fwrite($file_open, $contents);
                // close file
                fclose($file_open);

                // Checking
                if($converted) {
                    $message[] = 'File converted';
                }
                else {
                    $message[] = 'Failed ';
                }

            }
            else {
                $message[] = 'Invalid file ext';
            }

            
        }
        else {
            $message[] = 'No file available';
        }

        
    }
?>

<?php
    $qry = "SELECT * FROM assignments WHERE assignment_id = '$classwork_id' ";
    $result = mysqli_query($conn, $qry);
    $get_assignment = mysqli_fetch_array($result);

?>

<body>
    <?php require_once('./includes/topNav.php'); ?>
    <main class="container">
        <?php require_once('./includes/sideNav.php'); ?>
        <div class="home" id="dashboard">
            <div class="_container">
                <div class="_container__ assignment__">
                    <div class="classworks">
                        <div class="view_container">
                            <div class="view_header">
                                <div class="d-f">
                                    <div class="_icon">
                                        <i class="fa-regular fa-clone icon"></i>
                                    </div>
                                    <div class="d-f_content">
                                        <?php if($classwork > 0): ?>
                                            <h2><?php echo $classwork['title']; ?></h2>
                                            <div>
                                                <p class="lecturer_"><?php echo $classwork['fullname']; ?>::</p>
                                                <p><?php echo date('M, D, Y', strtotime($classwork['date_created'])); ?></p>
                                            </div>
                                            <p class="description"><?php echo $classwork['description']; ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="view_content">
                                <?php if($classwork > 0): ?>
                                    <a href="view_material.php?material=<?php echo $classwork['upload'];?>" class="material">
                                        <div class="content_img">
                                            <div class="the_file_img">
                                                <?php
                                                    $getClasswork = $classwork['upload'];
                                                    // echo $getAssignment;
                                                    $split = explode('.', $getClasswork);
                                                    $upload_name = $split[0].'.png';
                                                    // echo $assignment_name;

                                                    $files_in_image_format = scandir("../image_format");
                                                    // print_r($files_in_image_format);
                                                    for($i = 0; $i < count($files_in_image_format); $i++) {
                                                        // echo $files_in_image_format[$i];
                                                        if($upload_name == $files_in_image_format[$i]) { ?>
                                                            <img src="../image_format/<?php echo $files_in_image_format[$i];?>" class="img" alt="">
                                                        <?php }
                                                    
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="filename"><?php echo $classwork['upload']; ?></p>
                                        </div>
                                    </a>
                                <?php endif; ?>
                            </div>
                            
                        </div>

                    </div>
                    <div class="submit_section">
                        <div class="submit_head">
                            <h2>Your Work</h2>
                            <?php 
                                if($get_assignment > 0) { ?>
                                    <p><?php echo $get_assignment['assignment'] ? 'Submitted' : 'Missing' ?></p>
                                <?php }
                                else { ?>
                                    <p>Missing</p>
                                <?php }
                            ?>
                        </div>
                        <?php if(isset($message)): ?>
                            <?php foreach($message as $msg): ?>
                                <p class="err"><?php echo $msg; ?></p>
                            <?php endforeach;?>
                        <?php endif; ?>
                        <?php if($get_assignment > 0): ?>
                            <div class="display_upload">
                                <a href="view_material.php?material=<?php echo $get_assignment['assignment'] ;?>" class="theFile">
                                    <div class="the_file_img">
                                        <?php
                                            $getAssignment = $get_assignment['assignment'];
                                            // echo $getAssignment;
                                            $split = explode('.', $getAssignment);
                                            $assignment_name = $split[0].'.png';
                                            // echo $assignment_name;

                                            $files_in_image_format = scandir("../image_format");
                                            // print_r($files_in_image_format);
                                            for($i = 0; $i < count($files_in_image_format); $i++) {
                                                // echo $files_in_image_format[$i];
                                                if($assignment_name == $files_in_image_format[$i]) { ?>
                                                    <img src="../image_format/<?php echo $files_in_image_format[$i];?>" class="img" alt="">
                                                <?php }
                                            
                                            }
                                        ?>
                                    </div>
                                    <div class="the_file_content">
                                        <p><?php echo $get_assignment['assignment'] ?></p>
                                    </div>
                                </a>
                                <i class="fa-solid fa-xmark icon"></i>
                            </div>
                        <?php endif; ?>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <label for="upload">
                                <div class="label" class="upload_file">
                                    <i class="fa-solid fa-upload icon"></i>
                                    <p>Upload</p>
                                </div>
                            </label>
                            <input type="file" name="upload" id="upload">
                            <button type="submit" name="submit" class="upload_btn">Submit</button>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php require_once('./includes/footer.php'); ?>