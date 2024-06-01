<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = $_POST['file'];
    $done = filter_var($_POST['done'], FILTER_VALIDATE_BOOLEAN);
    $doneStatusFile = 'done_status.json';

    $doneStatus = file_exists($doneStatusFile) ? json_decode(file_get_contents($doneStatusFile), true) : [];

    if ($done) {
        $doneStatus[$file] = true;
    } else {
        unset($doneStatus[$file]);
    }

    if (file_put_contents($doneStatusFile, json_encode($doneStatus))) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Could not update the done status.']);
    }
}
?>
