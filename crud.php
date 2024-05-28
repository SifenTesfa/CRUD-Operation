<?php

$server = "localhost";
$username = "root";
$password = "";
$database = "crud1";

// Create connection
$con = mysqli_connect($server, $username, $password);

// Check connection


// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $database";



// Select the database
if (mysqli_select_db($con, $database)) {
    echo "Database selected successfully.<br>";
} else {
    echo "Error selecting database: " . mysqli_error($con);
}

// Create table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL ,
    password VARCHAR(30) NOT NULL ,
    date DATE NOT NULL DEFAULT CURRENT_DATE
)";

if (mysqli_query($con, $sql)) {
    echo "Table 'users' created successfully<br>";
} else {
    echo "Error creating table: " . mysqli_error($con);
}

// Create (Insert)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["create"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

    if (mysqli_query($con, $sql)) {
        echo "New user created successfully<br>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}

// Read (Select)
$sql = "SELECT * FROM users";
$result = mysqli_query($con, $sql);

// Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $id = $_POST["id"];
    $new_name = $_POST["name"];
    $new_email = $_POST["email"];
    $new_password = $_POST["password"];

    $sql = "UPDATE users SET name = '$new_name', email = '$new_email', password = '$new_password' WHERE id = $id";

    if (mysqli_query($con, $sql)) {
        echo "User updated successfully<br>";
    } else {
        echo "Error updating user: " . mysqli_error($con);
    }
}

// Delete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $id = $_POST["id"];

    $sql = "DELETE FROM users WHERE id = $id";

    if (mysqli_query($con, $sql)) {
        echo "User deleted successfully<br>";
    } else {
        echo "Error deleting user: " . mysqli_error($con);
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Operations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            display: flex;
            flex-direction: column;
            padding-left: 100px;
            padding-right: 260px;
            min-height: 100vh;
        }
        h1, h2 {
            color: #333;
            
        }
        table {
            width: 100%;
            border-collapse: collapse;
            
        }
        
        .cr{
            text-align: center;
            
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        input[type=text], input[type=email], input[type=password] {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type=submit] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1 class="cr">CRUD Operations</h1>

    <h2>Create User</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Name: <input type="text" name="name"><br>
        Email: <input type="email" name="email"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" name="create" value="Create">
    </form>

    <h2>Users</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Password</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td>" . $row["password"] . "</td>";
                echo "<td>" . $row["date"] . "</td>";
                echo "<td> 
                    <form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>
                    <input type='hidden' name='id' value='" . $row["id"] . "'>
                    <input type='text' name='name' value='" . $row["name"] . "'>
                    <input type='email' name='email' value='" . $row["email"] . "'>
                    <input type='password' name='password' value='" . $row["password"] . "'>
                    <input type='submit' name='update' value='Update'>
                    <input type='submit' name='delete' value='Delete' style='background-color:red;'>
                </form>
                </td>";
                 echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No users found</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
mysqli_close($con);
?>