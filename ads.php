<?php
	session_start();
	if (array_key_exists("user", $_SESSION)) {
		$currlogin=$_SESSION['user'];
	}
	include "key.ini";
	mysql_select_db('zootopiadb') or die("Could not select database!");
	mysql_set_charset("utf8");
	if (!isset($_GET['id'])) die("Access denied!");
	$id=$_GET['id'];
	$result = mysql_query(" SELECT * FROM ads WHERE id='$id'");
	if(!$row = mysql_fetch_array($result)) die("Access denied!");
	$author=$row['author'];
	$result1 = mysql_query(" SELECT * FROM users WHERE login='$author'");
	$author =mysql_fetch_array($result1);
	unset($author['login']);
	unset($author['password']);
	mysql_close();

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Объявление</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="style/style.css?0" type="text/css">
		<link rel="stylesheet" href="style/style_ads.css?0" type="text/css">
		<link rel="shortcut icon" href="images/paw.ico" type="image/x-icon">
		<meta name="Keywords" content="питомцы, домашние животные, питомцы купить, питомцы продать, домашние животные купить, домашние животные продать, коты, котята, кошки, щенки, собаки, попугаи, рыбки">
	</head>
	<body>
		<div id="header">
			<a href="index.php" id="logo"></a>
			
			<div id="wrapper">
				<?php 
					if(isset($currlogin)) echo '<a href="user.php" id="account">'.$currlogin.'</a>';
					else echo '<a href="registration.php" id="account">Войти в профиль</a>';
				?>
				<a href="<?php if(!isset($currlogin)) echo 'registration.php'; else echo 'add.php';?>" id="add">НОВОЕ ОБЪЯВЛЕНИЕ</a>
			</div>
		</div>
		<div id="body">
			<div id="menu">
			<?php
				echo '<b><p style="font-size:30px;padding:0 15px;color: black;">'.$row['price'].' грн</p></b>';
				if($row['male']=='none') echo '<p>Пол не указан</p>'; else
				if($row['male']=='male') echo '<p><b>Пол:</b> Мальчик</p>'; else
				if($row['male']=='female') echo '<p><b>Пол:</b> Девочка</p>';
				if($row['category']=='all')echo '<p>Категория не указана</p>'; else
				if($row['category']=='cats')
				echo '<p><b>Категория: </b>Кошки</p>'; else
					if($row['category']=='dogs')
				echo '<p><b>Категория: </b>Собаки</p>';else
					if($row['category']=='rabbits')
				echo '<p><b>Категория: </b>Кролики</p>';
				if($row['age']!=-1)
				echo '<p><b>Возраст (лет):</b> '.$row['age'].'</p>';
				else echo '<p>Возраст не указан</p>';
				echo '<p><b>Добавлено:</b></p>';
				echo '<p>'.$row['date'].' в '.$row['time'].'</p>';
			?>
			</div>
			<div id="main">
				<?php
			
					echo '<h2>'.$row['name'].'</h2>';
					echo '<br>';
					echo '<img src="'.$row['photo'].'">';
					echo '<br>';
					echo '<br>';
					echo '<p>'.$row['description'].'</p>';

				?>
			</div>
			<div id="contacts">
				<?php		
					$str=$author['phone_number'];
					$str = substr_replace($str, "(", 0, 0);
					$str = substr_replace($str, ") ", 4, 0);
					$str = substr_replace($str, " ", 9, 0);
					$str = substr_replace($str, " ", 12, 0);
					$str = substr_replace($str, "+38 ", 0, 0);
					echo '<p><b>Продавец:</b></p><p> '.$author['username'].'</p>';
					echo '<p><b>Почта:</b></p><p> '.$author['email'].'</p>';
					echo '<p><b>Телефон:</b></p><p> '.$str.'</p>';

				?>
			</div>
		</div>
		
		<div id="footer">
			2016 All rights recieved
		</div>
	</body>
</html>