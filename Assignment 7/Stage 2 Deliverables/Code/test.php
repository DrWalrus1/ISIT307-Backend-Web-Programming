<?php
require_once "dbinteraction.php";

function getPlatforms() {
    $platforms = array();
    if (!empty($_GET)) {
        if (!empty($_GET['platform'])) {
            foreach ($_GET['platform'] as $key => $value) {
                if ($key === $value[0]) {
                    // Add all platforms
                    // break;
                }
                echo $key . " " . $value[0];
            }
        }
    }
    if (!empty($platforms)){
        return $platforms;
    }
}


getPlatforms()
?>