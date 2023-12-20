<?php
function checkFields($fields) {
    foreach ($fields as $key => $field) {
        if (!isset($field) || empty($field)) {
            throw new Exception("Field '$key' is empty or not defined.");
        }
    }
}
?>