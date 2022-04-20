<?php

function sanitize($input) {
    global $conn;
    $input = strip_tags($input);
    $input = htmlentities(strip_tags($input), ENT_QUOTES, 'UTF-8');

    if (!get_magic_quotes_gpc()) {
        $input = addslashes(htmlentities(strip_tags($input), ENT_QUOTES, 'UTF-8'));
    } else {
        $input = htmlentities(strip_tags($input), ENT_QUOTES, 'UTF-8');
    }

    return $input;
}

function sanitize_input($input, $exceptionalArray = array()) {
    global $conn;
    unset($input['submit']); //we use 'submit' variable for all of our form
    $allowable_tags = null;
    $input_array = $input;

    //array is not referenced when passed into foreach
    //this is why we create another exact array
    foreach ($input as $key => $value) {
        if (count($exceptionalArray) > 0 && in_array($key, $exceptionalArray)) {
            continue;
        }

        if (!empty($value)) {
            $input_array[$key] = trim($input_array[$key]);
            $input_array[$key] = strip_tags($input_array[$key], $allowable_tags);
            $input_array[$key] = htmlspecialchars($input_array[$key]);
            $input_array[$key] = mysqli_real_escape_string($conn,$input_array[$key]);
            $input_array[$key] = preg_replace("/[[:blank:]]+/"," ",$input_array[$key]);
        }
    }

    return $input_array;
}
