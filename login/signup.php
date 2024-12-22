<?php 
session_start();

include("connection.php");
include("functions.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_name = $_POST['user_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    $location = $_POST['location'];
    
    $_SESSION['location'] = $location;


    if (!empty($user_name) && !empty($password) && $confirm == $password && ($location == 'Alexandria' or $location == 'Cairo' or $location == 'Giza')) {
        $check_query = "SELECT * FROM users WHERE userName = '$user_name'";
        $check_result = mysqli_query($con, $check_query);

        if ($check_result && mysqli_num_rows($check_result) == 0) {
            $insert_query = "INSERT INTO users (userName, email, password, location, balance) VALUES ('$user_name','$email','$password', '$location', 0)";
            $insert_result = mysqli_query($con, $insert_query);

            if ($insert_result) {
                $userID_query = "SELECT userID FROM users WHERE userName = '$user_name'";
                $userID_result = mysqli_query($con, $userID_query);

                if ($userID_result && mysqli_num_rows($userID_result) > 0) {
                    $userData = mysqli_fetch_assoc($userID_result);
                    $userID = $userData['userID'];

                    $cart_query = "INSERT INTO cart (userID) VALUES ('$userID')";
                    $cart_result = mysqli_query($con, $cart_query);

                    if ($cart_result) {
                        header("Location: login.php");
                        exit;
                    } else {
                        echo '<script>alert("Error creating cart: ' . mysqli_error($con) . '")</script>';
                    }
                } else {
                    echo '<script>alert("Error retrieving user ID")</script>';
                }
            } else {
                echo '<script>alert("Error creating user: ' . mysqli_error($con) . '")</script>';
            }
        } else {
            echo '<script>alert("Username already exists!")</script>';
        }
    } else {
        echo '<script>alert("Please enter valid information!")</script>';
    }
}
?>
<?php include '../Project/pages/signup.html'; ?>
<style>
<?php include '../Project/index.css'; ?>
</style>
