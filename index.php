<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload PDF</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            display: block;
        }
        input[type="text"],
        select,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        #progress {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Upload PDF</h2>
        <form id="uploadForm" enctype="multipart/form-data">
            <label for="vehicle_type">Vehicle Type:</label>
            <select name="vehicle_type" id="vehicle_type">
                <option value="2WH">2WH</option>
                <option value="3WH">3WH</option>
                <option value="3WC">3WC</option>
            </select><br><br>
            <label for="hub_name">Hub Name:</label>
            <input type="text" name="hub_name" id="hub_name"><br><br>
            <label for="from_date">From:</label>
            <input type="text" name="from_date" id="from_date"><br><br>
            <label for="to_date">To:</label>
            <input type="text" name="to_date" id="to_date"><br><br>
            <label for="file">Select PDF file(s) (Max 100MB):</label>
            <input type="file" name="file[]" id="file" multiple accept=".pdf" maxFileSize="104857600"><br><br>
            <div id="progress"></div>
            <input type="checkbox" name="late" id="late">
            <label for="late">Is this DTR for late submission?</label><br><br>
            <input type="submit" value="Upload PDF" name="submit">
        </form>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(function() {
            $("#uploadForm").on('submit', function(event) {
                event.preventDefault();

                // Create FormData object
                var formData = new FormData($(this)[0]);

                $.ajax({
                    url: 'upload.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener('progress', function(e) {
                            if (e.lengthComputable) {
                                var percent = Math.round((e.loaded / e.total) * 100);
                                $('#progress').text('Upload progress: ' + percent + '%');
                            }
                        }, false);
                        return xhr;
                    },
                    success: function(response) {
                        // Handle success response
                        $('#progress').text(response);
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>
