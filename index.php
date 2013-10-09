<?php 
	include 'includes/database_handler.php';
	if(isset($_POST['register'])) {
		if($_POST['username'] && $_POST['email']) {
			$dbObj = new dbhandler();
			$name = $_POST['username'] ;
			$email = $_POST['email'] ;
			$dbObj->create_new_user($name, $email) ;
		}
		else {
			echo "<br /> Please fill in all the details..." ;
			header("refresh:3;url=index.php") ;
		}
	}
?>

<html>
<head>
	<title>Error Terminal</title>
</head>
<body>
	<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
		<input type="text" name="username" placeholder="Enter name"/>
		<input type="text" name="email" placeholder="Enter email"/>
		<input type="submit" value="Register" name="register"/>
	</form>
</body>
</html>