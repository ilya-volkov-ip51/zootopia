<?php
	session_start();
	if (array_key_exists("user", $_SESSION)) {
		$currlogin=$_SESSION['user'];
	} else die("Access denied!");
	include "key.ini";
	mysql_select_db('zootopiadb') or die("Could not select database!");
	mysql_set_charset("utf8");
	$result = mysql_query(" SELECT * FROM users WHERE login='$currlogin'");
	$user =mysql_fetch_array($result);
	if(isset($_POST['del'])) {
		$todelete=$_POST['del'];
		mysql_query("DELETE FROM ads WHERE id='$todelete'");
		$ads_id=$user['ads_id'];
		$todelete=",".$todelete;
		$ads_id=str_replace($todelete,"",$ads_id);
		mysql_query("UPDATE users SET ads_id = '$ads_id' WHERE  login='$currlogin'");
		}
	unset($user['password']);
	$ads_array=explode(",",$user['ads_id'],2);
	if(isset($ads_array[1]))
	{
		$ads=trim($ads_array[1]);
		$ads = mysql_query(" SELECT * FROM ads WHERE id IN ($ads);");
	}
	mysql_close();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Личный кабинет</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="style/style.css?0" type="text/css">
		<link rel="stylesheet" href="style/user.css?0" type="text/css">
		<link rel="shortcut icon" href="images/paw.ico" type="image/x-icon">
		<meta name="Keywords" content="питомцы, домашние животные, питомцы купить, питомцы продать, домашние животные купить, домашние животные продать, коты, котята, кошки, щенки, собаки, попугаи, рыбки">
		<script type="text/javascript" src="scripts.js"></script>
		</head>
	<body>
		<div id="header">
			<a href="index.php" id="logo"></a>
			
			<div id="wrapper">
				<?php 
					if(isset($currlogin)) echo '<a href="#" id="account">'.$currlogin.'</a>';
					else echo '<a href="registration.php" id="account">Вход / Регистрация</a>';
				?>
				<a href="<?php if(!isset($currlogin)) echo 'registration.php'; else echo 'add.php';?>" id="add">НОВОЕ ОБЪЯВЛЕНИЕ</a>
			</div>
		</div>
		<div id="body">
		
		<div id="user">
		<h2>Ваш профиль</h2>
		<?php 
			echo "<b>Вы вошли как: </b>".$user['login']."<br>";
			echo "<b>Ваше имя: </b>".$user['username']."<br>";
			echo "<b>E-mail: </b>".$user['email']."<br>";
				$str=$user['phone_number'];
				$str = substr_replace($str, "(", 0, 0);
				$str = substr_replace($str, ") ", 4, 0);
				$str = substr_replace($str, " ", 9, 0);
				$str = substr_replace($str, " ", 12, 0);
				$str = substr_replace($str, "+38 ", 0, 0);
			echo "<b>Телефон: </b>".$str."<br>";
			echo "<b>Ваш регион: </b>".$user['locality']."<br>";
			
		?>
		<form method="post" action="index.php" id="unlog">
			<input type="submit" name="out" value="Выйти">
		</form>
		<form method="post" action="edit.php" id="edit">
			<input type="submit" name="edit" value="Изменить">
		</form>
		</div>
		<div id="data">
		<h2>Ваши объявления</h2>
		<?php
		if(isset($ads))
		while($row = mysql_fetch_array($ads)){
			echo '<div class="ads">';
			echo '<a href="ads.php?id='.$row['id'].'">';
			echo '<img src="'.$row['photo'].'">';
			echo '<h2>'.$row['name'].'</h2>';
			echo '<b>'.$row['price'].' грн</b>';
			echo '<div class="text">';
			echo '<p>'.$user['locality'].' '.$row['date'].' '.$row['time'].'</p>';
			echo '<form method="post" action="user.php" id="delete" onsubmit="deleteAd(this);return false;">';
			echo '<input type="hidden" name="del" value="'.$row['id'].'">';
			echo '<button type="submit">Удалить</button>';
			echo '</form>';
			echo '</div>';
			echo '</a>';
			echo '</div>';}
		?>
		</div>

		</div>
		<div id="footer">
			2016 All rights recieved
		</div>
		<a href="#" class="topbutton"></a>
	</body>
</html>