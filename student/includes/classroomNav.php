<div class="classroom_nav">
    <ul>
        <li>
            <a href="classroom.php?classroom=<?php echo base64_encode($class_code); ?>_<?php echo base64_encode($module_id); ?>">
                Stream
            </a>
        </li>
        <li>
            <a href="classworks.php?classroom=<?php echo base64_encode($class_code); ?>_<?php echo base64_encode($module_id); ?>">
                Classwork
            </a>
        </li>
        <li>
            <a href="people.php?classroom=<?php echo base64_encode($class_code); ?>_<?php echo base64_encode($module_id); ?>">
                People
            </a>
        </li>
        <li><a href="grade.php">Grades</a></li>
    </ul>
</div>