<?php
// Check if file(s) were uploaded
if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
    $targetDir = "uploads/"; // Default directory for regular submissions
    $lateFolder = "late_folder/"; // Directory for late submissions
    $allowedTypes = array('pdf'); // Specify allowed file types

    // Check if "late submission" checkbox is checked
    $isLateSubmission = isset($_POST['late']) && $_POST['late'] == 'on';

    // Set target directory based on submission type
    $targetDir = $isLateSubmission ? $lateFolder : $targetDir;

    // Get metadata from form
    $vehicleType = $_POST['vehicle_type'];
    $hubName = $_POST['hub_name'];
    $fromDate = $_POST['from_date'];
    $toDate = $_POST['to_date'];

    // Read existing metadata from JSON file
    $metadataFile = 'file_metadata.json';
    $metadata = file_exists($metadataFile) ? json_decode(file_get_contents($metadataFile), true) : [];

    // Loop through each file uploaded
    foreach ($_FILES['file']['name'] as $key => $val) {
        $fileName = basename($_FILES['file']['name'][$key]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Check if file type is allowed
        if (in_array($fileType, $allowedTypes)) {
            // Check if file with the same name already exists
            $version = 1;
            $originalFileName = pathinfo($fileName, PATHINFO_FILENAME);
            $originalFileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            while (file_exists($targetFilePath)) {
                $fileName = $originalFileName . ' V' . $version . '.' . $originalFileExtension;
                $targetFilePath = $targetDir . $fileName;
                $version++;
            }
            // Upload file to server
            if (move_uploaded_file($_FILES['file']['tmp_name'][$key], $targetFilePath)) {
                // File uploaded successfully
                echo "File " . $fileName . " uploaded successfully.";

                // Save metadata
                $metadata[$fileName] = [
                    'vehicle_type' => $vehicleType,
                    'hub_name' => $hubName,
                    'from_date' => $fromDate,
                    'to_date' => $toDate,
                    'upload_date' => date("M d, Y h:i:s A"),
                    'late' => $isLateSubmission
                ];
            } else {
                // Failed to upload file
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            // File type not allowed
            echo "Sorry, only PDF files are allowed to be uploaded.";
        }
    }

    // Write metadata to JSON file
    file_put_contents($metadataFile, json_encode($metadata, JSON_PRETTY_PRINT));
} else {
    // No file uploaded
    echo "Please select at least one file to upload.";
}
?>
