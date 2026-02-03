<?php
// Add an extra size (max)
add_image_size('max', 1980 , 1980);

// Replace the original image with the max size
add_filter('wp_generate_attachment_metadata', 'replace_uploaded_image');
function replace_uploaded_image($image_data) {

    // if there is no max image : return
    if (!isset($image_data['sizes']['max']))
        return $image_data;

    // paths to the uploaded image and the max image
    $upload_dir = wp_upload_dir();
    $uploaded_image_location = $upload_dir['basedir'] . '/' . $image_data['file'];
    $current_subdir = substr($image_data['file'],0, strrpos($image_data['file'],"/"));
    $max_image_location = $upload_dir['basedir'] . '/' . $current_subdir.'/' . $image_data['sizes']['max']['file'];

    $ext = pathinfo($uploaded_image_location, PATHINFO_EXTENSION);

    if($ext != "svg") {
        // delete the uploaded image
        unlink($uploaded_image_location);

        // rename the max image
        rename($max_image_location, $uploaded_image_location);

        // update image metadata and return them
        $image_data['width'] = $image_data['sizes']['max']['width'];
        $image_data['height'] = $image_data['sizes']['max']['height'];
        unset($image_data['sizes']['max']);

        return $image_data;
    }else{
        return $image_data;
    }
}