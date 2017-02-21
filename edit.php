<?php
	session_start();
	if (array_key_exists("user", $_SESSION)) {
		$currlogin=$_SESSION['user'];
	}
	include "key.ini";
	mysql_select_db('zootopiadb') or die("Could not select database!");
	mysql_set_charset("utf8");
	$change=0;
	$update="";
	if(isset($_POST['change']))
	{
		$oldpassword=strip_tags(trim($_POST['oldpassword']));
		$newpassword=strip_tags(trim($_POST['newpassword']));
		$repeatpassword=strip_tags(trim($_POST['repeatpassword']));
		if(empty($newpassword)||empty($oldpassword)||empty($repeatpassword)) $change=2; else
		{
			$res=mysql_query("SELECT * FROM users WHERE login='$currlogin'");
			$row=mysql_fetch_array($res);
			if($row['password']!=$oldpassword) $change=3; else
			if($newpassword!=$repeatpassword) $change=4; else
			if(!preg_match("/.{8,}/",$newpassword))$change=5; else 
			if(preg_match("/[^0-9a-zA-Z_.-]/",$newpassword))$change=6; else 
			{
				mysql_query("UPDATE users SET password='$newpassword' WHERE login='$currlogin'");
				$change=1;
			}
		}
	}
	if(isset($_POST['update']))
	{
		$username=strip_tags(trim($_POST['username']));
		$email=strip_tags(trim($_POST['email']));
		$phone_number=strip_tags(trim($_POST['phone_number']));
		$locality=strip_tags(trim($_POST['locality']));
			mysql_query("UPDATE users SET locality='$locality' WHERE login='$currlogin'");	
			$update="<p>Местоположение измененено</p>";
			if(!empty($username)){
				if(preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/",$username)) $update=$update."<p>Некорректное имя</p>";
				else {
					mysql_query("UPDATE users SET username='$username' WHERE login='$currlogin'");
					$update=$update."<p>Имя изменено</p>";
				}
			}
			if(!empty($email)){
				if (!preg_match("/[0-9a-zA-Z_.-]+@[a-zA-Z]+\.[a-zA-Z]+/",$email) || preg_match("/[^0-9a-zA-Z_.@-]/",$email)) $update=$update."Некорректный e-mail</p>";
				else {
					mysql_query("UPDATE users SET email='$email' WHERE login='$currlogin'");
					$update=$update."<p>E-mail изменен</p>";
				}
			}
			if(!empty($phone_number)){
				if (preg_match("/[^0-9]/",$phone_number) || !preg_match("/.{10}/",$phone_number)) $update=$update."<p>Некорректный номер</p>";
				else {
					mysql_query("UPDATE users SET phone_number='$phone_number' WHERE login='$currlogin'");
					$update=$update."<p>Номер изменен</p>";
				}
			}					
	}else
	if(!isset($_POST['edit'])) die("Access denied!");
	mysql_close();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Изменение профиля</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="style/style.css?0" type="text/css">
		<link rel="stylesheet" href="style/authorization.css?0" type="text/css">
		<link rel="stylesheet" href="style/edit.css?0" type="text/css">
		<link rel="shortcut icon" href="images/paw.ico" type="image/x-icon">
		<meta name="Keywords" content="питомцы, домашние животные, питомцы купить, питомцы продать, домашние животные купить, домашние животные продать, коты, котята, кошки, щенки, собаки, попугаи, рыбки">
	</head>
	<body>
		<div id="header">
			<a href="index.php" id="logo"></a>
			
			<div id="wrapper">
				<?php 
					if(isset($currlogin)) echo '<a href="user.php" id="account">'.$currlogin.'</a>';
					else echo '<a href="registration.php" id="account">Вход / Регистрация</a>';
				?>
				<a href="<?php if(!isset($currlogin)) echo 'registration.php'; else echo 'add.php';?>" id="add">НОВОЕ ОБЪЯВЛЕНИЕ</a>
			</div>
		</div>
		<div id="body">
		<form method="post" action="edit.php" id="login">
			Смена пароля:
			<p>Старый пароль:<br> <input type="password" maxlength="20" name="oldpassword"></p>
			<p>Новый пароль:<br> <input type="password" maxlength="20" name="newpassword"></p>
			<p>Повторите пароль:<br> <input type="password" maxlength="20" name="repeatpassword"></p>
			<input type="submit" name="change" value="Поменять пароль">
			<br>
			<br>
				<?php
					if($change==1) echo '<div id="win">Действие выполнено успешно!</div>'; 
					else if($change==2)echo '<div id="fail">Не все поля заполнены!</div>';
					else if($change==3)echo '<div id="fail">Неверный пароль!</div>';
					else if($change==4)echo '<div id="fail">Пароли не совпадают!</div>';
					else if($change==5)echo '<div id="fail">Пароль должен содержать как минимум 8 символов!</div>';
					else if($change==6)echo '<div id="fail">Пароль содержит недопустимые символы!</div>';
					$change=0;
				?>
		</form>
		<form method="post" action="edit.php" id="registration">
			Ваши персональные данные:
			<p>Новое имя:<br> <input type="text" maxlength="20" name="username"></p>
			<p>Новая почта:<br> <input type="text" maxlength="40" name="email" placeholder="example@gmail.com" ></p> 
			<p>Новый номер телефона:<br> <input type="text" maxlength="10" name="phone_number" placeholder="(050)1234567"></p>
			<p>Новый регион:<br>
			<select name="locality" size="1">
				<option value="Винницкая область">Винницкая область
				<option value="Волынская область">Волынская область
				<option value="Днепропетровская область">Днепропетровская область
				<option value="Житомирская область">Житомирская область
				<option value="Закарпатская область">Закарпатская область
				<option value="Запорожская область">Запорожская область
				<option value="Ивано-Франковская область">Ивано-Франковская область
				<option value="Киевская область">Киевская область
				<option value="Кировоградская область">Кировоградская область
				<option value="Львовская область">Львовская область
				<option value="Николаевская область">Николаевская область
				<option value="Одесская область">Одесская область
				<option value="Полтавская область">Полтавская область
				<option value="Ровенская область">Ровенская область
				<option value="Сумская область">Сумская область
				<option value="Тернопольская область">Тернопольская область
				<option value="Харьковская область">Харьковская область
				<option value="Херсонская область">Херсонская область
				<option value="Хмельницкая область">Хмельницкая область
				<option value="Черкасская область">Черкасская область
				<option value="Черниговская область">Черниговская область
				<option value="Черновицкая область">Черновицкая область
			</select></p>
			<input type="submit" name="update" value="Сохранить изменения">	
		<br>
		<br>
		<?php
			if(!empty($update)) echo '<div id="message">'.$update.'</div>';
			$update=0;
		?>
		</form>			
		</div>
		<div id="footer">
			2016 All rights recieved
		</div>
	</body>
</html>