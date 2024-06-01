<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2, h3 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .download-btn, .delete-btn {
            padding: 8px 12px;
            border: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .delete-btn {
            background-color: #dc3545;
        }
        .download-btn:hover {
            background-color: #0056b3;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
        .done-checkbox {
            margin: 0;
        }
        .done {
            background-color: #d4edda;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Panel</h2>

        <!-- Search Input -->
        <input type="text" id="searchInput" placeholder="Search...">

        <!-- Regular Uploads Table -->
        <h3>Uploaded Files</h3>
        <table id="uploadsTable" class="display">
            <thead>
                <tr>
                    <th>File Name</th>
                    <th>Vehicle Type</th>
                    <th>Hub Name</th>
                    <th>From Date</th>
                    <th>To Date</th>
                    <th>Upload Date</th>
                    <th></th>
                    <th>Done</th>
                    <th>Download</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $uploadDirectory = "uploads/";
                $fileMetadataFile = 'file_metadata.json';
                $doneStatusFile = 'done_status.json';

                $fileMetadata = file_exists($fileMetadataFile) ? json_decode(file_get_contents($fileMetadataFile), true) : [];
                $doneStatus = file_exists($doneStatusFile) ? json_decode(file_get_contents($doneStatusFile), true) : [];

                $uploadFiles = glob($uploadDirectory . "*");

                foreach ($uploadFiles as $file) {
                    if (is_file($file)) {
                        $fileName = basename($file);
                        $metadata = isset($fileMetadata[$fileName]) ? $fileMetadata[$fileName] : [];
                        $vehicleType = $metadata['vehicle_type'] ?? '';
                        $hubName = $metadata['hub_name'] ?? '';
                        $fromDate = $metadata['from_date'] ?? '';
                        $toDate = $metadata['to_date'] ?? '';
                        $uploadDate = $metadata['upload_date'] ?? date("M d, Y h:i:s A", filemtime($file));
                        

                        $isDone = isset($doneStatus[$fileName]) ? $doneStatus[$fileName] : false;
                        $checked = $isDone ? "checked" : "";

                        echo "<tr class='data-row " . ($isDone ? "done" : "") . "'>
                                <td>$fileName</td>
                                <td>$vehicleType</td>
                                <td>$hubName</td>
                                <td>$fromDate</td>
                                <td>$toDate</td>
                                <td>$uploadDate</td>
                                <td><!-- Button for actions --></td>
                                <td><input type='checkbox' class='done-checkbox' data-file='$fileName' data-directory='$uploadDirectory' $checked></td>
                                <td><a href='$uploadDirectory$fileName' class='download-btn'>Download</a></td>
                                <td><button class='delete-btn' data-file='$fileName' data-directory='$uploadDirectory'>Delete</button></td>
                            </tr>";
                    }
                }
                ?>
            </tbody>
        </table>

        <!-- Late Uploads Table -->
        <h3>Late Uploaded Files</h3>
        <table id="lateUploadsTable" class="display">
            <thead>
                <tr>
                    <th>File Name</th>
                    <th>Vehicle Type</th>
                    <th>Hub Name</th>
                    <th>From Date</th>
                    <th>To Date</th>
                    <th>Upload Date</th>
                    <th></th>
                    <th>Done</th>
                    <th>Download</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $lateFolder = "late_folder/";
                $lateUploadFiles = glob($lateFolder . "*");

                foreach ($lateUploadFiles as $file) {
                    if (is_file($file)) {
                        $fileName = basename($file);
                        $metadata = isset($fileMetadata[$fileName]) ? $fileMetadata[$fileName] : [];
                        $vehicleType = $metadata['vehicle_type'] ?? '';
                        $hubName = $metadata['hub_name'] ?? '';
                        $fromDate = $metadata['from_date'] ?? '';
                        $toDate = $metadata['to_date'] ?? '';
                        $uploadDate = $metadata['upload_date'] ?? date("M d, Y h:i:s A", filemtime($file));


                        $isDone = isset($doneStatus[$fileName]) ? $doneStatus[$fileName] : false;
                        $checked = $isDone ? "checked" : "";

                        echo "<tr class='data-row " . ($isDone ? "done" : "") . "'>
                                <td>$fileName</td>
                                <td>$vehicleType</td>
                                <td>$hubName</td>
                                <td>$fromDate</td>
                                <td>$toDate</td>
                                <td>$uploadDate</td>
                                <td><!-- Button for actions --></td>
                                <td><input type='checkbox' class='done-checkbox' data-file='$fileName' data-directory='$lateFolder' $checked></td>
                                <td><a href='$lateFolder$fileName' class='download-btn'>Download</a></td>
                                <td><button class='delete-btn' data-file='$fileName' data-directory='$lateFolder'>Delete</button></td>
                            </tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready( function () {
            $('#uploadsTable').DataTable();
            $('#lateUploadsTable').DataTable();
        } );
    </script>
</body>
</html>
