<?php 

session_start();

	include("connection.php");
	include("functions.php");


	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$user_name = $_POST['user_name'];
		$password = $_POST['password'];

		if(!empty($user_name) && !empty($password) && !is_numeric($user_name))
		{

			$query = "select * from users where userName = '$user_name' limit 1";
			$result = mysqli_query($con, $query);

			if($result)
			{
				if($result && mysqli_num_rows($result) > 0)
				{

					$user_data = mysqli_fetch_assoc($result);
					
					if($user_data['password'] === $password)
					{

						$_SESSION['user_id'] = $user_data['userID'];
						header("Location: index.php");
						die;
					}
				}
			}
		}else
		{
			echo '<script>alert("Wrong username or Password!")</script>';
			header("Refresh:0");
		}
	}

?>
<?php include '../Project/pages/login.html'; ?>
<style>
<?php include '../Project/index.css'; ?>
</style>