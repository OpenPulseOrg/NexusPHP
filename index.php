<!DOCTYPE html>
<html>
<head>
    <title>Server Configuration Error</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f5f5f5; /* Light gray background for the whole page */
            font-family: Arial, sans-serif; /* Change the font family to Arial or any other preferred font */
        }
        .error-container {
            background-color: #fdd9b5; /* Light peach background color */
            color: #8b4513; /* Saddle Brown text color */
            padding: 20px;
            border: 2px solid #ffa07a; /* Light Salmon border color */
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            max-width: 500px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <span style="font-size: 24px;">⚠️ Warning:</span><br>
        <?php
            $errorMsg = "This server is incorrectly set up. It is not redirecting the web routes correctly and is pointing to the root index.php, not the public index.php.";
            $errorMsg .= " Please ensure that your .htaccess file is loading correctly and that it is currently pointing all traffic to public/index.php.";
            $errorMsg .= " If you need help, please reach out for support.";
            echo $errorMsg;
        ?>
    </div>
</body>
</html>
