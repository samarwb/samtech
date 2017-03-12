<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function get_user_current_details($details_type) {
    $app_key = '9a7c177a-744a-4d7c-b29b-dc94f01e9d55';
    $my_ip = '122.166.231.241';
    //$my_ip = '122.166.145.60';
    if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'] != '127.0.0.1')
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '127.0.0.1')
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']) && $_SERVER['HTTP_X_FORWARDED'] != '127.0.0.1')
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']) && $_SERVER['HTTP_FORWARDED_FOR'] != '127.0.0.1')
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']) && $_SERVER['HTTP_FORWARDED'] != '127.0.0.1')
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '127.0.0.1')
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    //echo "<script>console.log( 'Debug Objects: " . $ipaddress . "' );</script>";
    // $details = json_decode(file_get_contents("https://ipfind.co?ip=".$my_ip));
    $details = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$my_ip"));
    if ($details_type == 'location') {
        $return = $details;
    }
    return $return;
}

function file_image_path($type, $file_name, $file_type, $file_size, $file_temp_path) {

    switch ($type) {
        case 'product' :
            $directory_path = FILE_DIRECTORY . PRODUCT_IMAGE_DIRECTORY;
            break;

        default:
            break;
    }

    $uploadOk = 1;
    $file_upload_info = array();
    $target_file = $directory_path . basename($file_name);

    // Check if file already exists

    if (file_exists($target_file)) {
        $file_upload_info['sucess'][] = $file_name;
        $file_upload_info['error'][] = "Sorry, file already exists.";
        $uploadOk = 0;
    }
    // Check file size
    if ($file_size > 5000000) {
        $file_upload_info['error'][] = "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if ($file_type != "jpg" && $file_type != "png" && $file_type != "jpeg" && $file_type != "gif") {
        $file_upload_info['error'][] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $file_upload_info['error'][] = "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (!is_dir($directory_path)) {
            mkdir($directory_path, 0777, TRUE);
        }
        $file_result = move_uploaded_file($file_temp_path, $target_file);
        if ($file_result == true) {
            $file_upload_info['sucess'][] = $file_name;
        } else {
            $file_upload_info['error'][] = "Sorry, there was an error uploading your file.";
        }
    }
    return $file_upload_info;
}
