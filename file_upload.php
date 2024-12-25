<?php
// file_upload.php

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['fileToUpload'])) {
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "<div class='success'>The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.</div>";
    } 
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $file_to_delete = "uploads/" . basename($_POST['delete']);
    if (file_exists($file_to_delete)) {
        unlink($file_to_delete);
        echo "<div class='success'>File " . htmlspecialchars(basename($_POST['delete'])) . " has been deleted.</div>";
    } else {
        echo "<div class='error'>Error: File not found.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload File</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 50px auto;
            width: 50%;
            text-align: center;
        }
        h2 {
            color: #333;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        input[type="file"] {
            margin: 20px 0;
        }
        input[type="submit"], .delete-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        .delete-btn {
            background-color: #FF6347;
        }
        input[type="submit"]:hover, .delete-btn:hover {
            background-color: #45a049;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            background-color: #fff;
            margin: 10px 0;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        a {
            text-decoration: none;
            color: #007BFF;
        }
        a:hover {
            text-decoration: underline;
        }
        .success {
            color: green;
            margin: 20px 0;
        }
        .error {
            color: red;
            margin: 20px 0;
        }
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            width: 300px;
        }
        .modal button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .modal button.cancel {
            background-color: #FF6347;
        }
    </style>
</head>
<body>
    <h2>Upload a File</h2>
    <form action="file_upload.php" method="post" enctype="multipart/form-data">
        Select file to upload:<br>
        <input type="file" name="fileToUpload" id="fileToUpload"><br>
        <input type="submit" value="Upload File" name="submit">
    </form>

    <h2>Manage Files</h2>
    <ul>
        <?php
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $files = scandir($target_dir);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                echo "<li><a href='uploads/$file' download>$file</a>
                <form action='file_upload.php' method='post' style='display:inline;' class='delete-form'>
                    <input type='hidden' name='delete' value='$file'>
                    <button type='button' class='delete-btn' onclick='confirmDelete(event, \"$file\")'>Delete</button>
                </form></li>";
            }
        }
        ?>
    </ul>

    <!-- Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h3>Are you sure you want to delete this file?</h3>
            <button id="confirmDeleteBtn" class="modal-btn">Yes, Delete</button>
            <button id="cancelDeleteBtn" class="cancel">Cancel</button>
        </div>
    </div>

    <script>
        function confirmDelete(event, fileName) {
            event.preventDefault(); // Prevent the form from submitting immediately

            // Show the modal
            var modal = document.getElementById('deleteModal');
            modal.style.display = 'flex';

            // Handle confirm delete
            var confirmButton = document.getElementById('confirmDeleteBtn');
            confirmButton.onclick = function() {
                var form = event.target.closest('form');
                var input = form.querySelector('input[name="delete"]');
                input.value = fileName; // Set the correct file name for deletion
                form.submit(); // Submit the form to delete the file
            };

            // Handle cancel
            var cancelButton = document.getElementById('cancelDeleteBtn');
            cancelButton.onclick = function() {
                modal.style.display = 'none'; // Hide the modal
            };
        }

        // Close modal if clicked outside
        window.onclick = function(event) {
            var modal = document.getElementById('deleteModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
