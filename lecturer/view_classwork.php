<!-- create classwork -->
<?php
    if(!empty($_GET['material'])) {
        $file = basename($_GET['material']);
        $file_path = "../uploads/${file}";

        if(!empty($file) && file_exists($file_path)) {
            // echo 'yes';
            header("Content-Type: application/pdf");
            header("Content-Disposition: attachment; filename = $file");
            header("Content-Transfer-Encoding: binary");
            header("Accept-Transfer-Encoding: binary");
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
    
            readfile($file_path);
            exit();
        }
        else {
            echo "File not found";
        }
    }
?>
