<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chatdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $message = $_POST['message'];

    $sql = "INSERT INTO messages (username, message) VALUES ('$username', '$message')";

    if ($conn->query($sql) === TRUE) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT username, message, timestamp FROM messages ORDER BY timestamp DESC";
$result = $conn->query($sql);

$messages = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Room</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .chat-container {
            width: 400px;
            border: 1px solid #ccc;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .chat-box {
            height: 300px;
            overflow-y: scroll;
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        .input-box {
            display: flex;
            padding: 10px;
        }

        input {
            width: calc(100% - 70px);
            padding: 10px;
            margin-right: 10px;
        }

        button {
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-box" id="chat-box">
            <?php foreach ($messages as $message): ?>
                <p><strong><?= htmlspecialchars($message['username']) ?>:</strong> <?= htmlspecialchars($message['message']) ?></p>
            <?php endforeach; ?>
        </div>
        <div class="input-box">
            <form method="post" action="">
                <input type="text" name="username" placeholder="Enter your name" required>
                <input type="text" name="message" placeholder="Type a message" required>
                <button type="submit">Send</button>
            </form>
        </div>
    </div>
    <script>
        function scrollChatBox() {
            var chatBox = document.getElementById('chat-box');
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        window.onload = scrollChatBox;
        window.setInterval(function(){
            location.reload();
        }, 5000);
    </script>
</body>
</html>
