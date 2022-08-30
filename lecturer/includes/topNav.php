<header class="header">
        <div class="head">
            <div class="logo">
                <i class="fas fa-laptop icon"></i>
                <h1 class="text">E-Classroom</h1>
            </div>
            <div>
                <div class="user_">
                    <?php
                        $query = "SELECT * FROM users WHERE _id = '$session_id' ";
                        $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
                        $user = mysqli_fetch_array($result);
                    ?>
                    <img src="<?php echo (!empty($user['photo'])) ? '../uploads/'.$user['photo'] : '../uploads/NO-IMAGE-AVAILABLE.jpg'; ?>" alt="user-img">
                    <p><?php echo $user['fullname']; ?></p>
                    <i class='bx bxs-chevron-down arrow icon' ></i>
                    <div class="drop_down">
                        <li class="tab">
                            <i class='bx bx-user icon'></i>
                            <a href="./profile.php">Profile</a>
                        </li>
                        <li class="tab">
                            <i class="fas fa-right-from-bracket icon"></i>
                            <a href="../logout.php">Logout</a>
                        </li>
                    </div>
                </div>
                
            </div>
        </div>
    </header>