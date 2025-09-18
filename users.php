<?php
$host = "localhost";
$user = "root";         
$pass = "0000";             
$db   = "pro_db";  

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT name, email FROM users ORDER BY name ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h3>Registered Users</h3>";
    echo "<ol>";

    while ($row = $result->fetch_assoc()) {
        echo "<li>" . htmlspecialchars($row['name']) . " (" . htmlspecialchars($row['email']) . ")</li>";
    }

    echo "</ol>";
} else {
    echo "No users found.";
}

$conn->close();
?>
