<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = $_POST['file'];
    $directory = $_POST['directory'];
    $filePath = $directory . $file;

    $response = array();

    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
            $response['error'] = 'Could not delete the file.';
        }
    } else {
        $response['success'] = false;
        $response['error'] = 'File does not exist.';
    }

    echo json_encode($response);
}
?>
