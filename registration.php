<?php
	$registered=0;
	$entered=0;
	session_start();
	if (array_key_exists("user", $_SESSION)) {
		$currlogin=$_SESSION['user'];
	}
	include "key.ini";
	mysql_select_db('zootopiadb') or die("Could not select database!");
	mysql_set_charset("utf8");
	if(isset($_POST['in']))
	{
		$oldlogin=strip_tags(trim($_POST['oldlogin']));
		$oldpassword=strip_tags(trim($_POST['oldpassword']));
		if(empty($oldlogin)||empty($oldpassword)) $entered=2; else
		{
			$res=mysql_query("SELECT * FROM users WHERE login='$oldlogin'");
			$row=mysql_fetch_array($res);
			if(!isset($row['password'])) {$entered=3; $oldlogin=NULL; $oldpassword=NULL;} else
			if($row['password']!=$oldpassword) {$entered=4; $oldpassword=NULL;} else
			{
				$_SESSION['user'] = $oldlogin;
				$entered=1;
			}
		}
	}
	if(isset($_POST['add']))
	{
		$username=strip_tags(trim($_POST['username']));
		$login=strip_tags(trim($_POST['login']));
		$res=mysql_query("SELECT login FROM users WHERE login='$login'");
		$password=strip_tags(trim($_POST['password']));
		$password1=strip_tags(trim($_POST['password1']));
		$email=strip_tags(trim($_POST['email']));
		$phone_number=strip_tags(trim($_POST['phone_number']));
		$locality=strip_tags(trim($_POST['locality']));
		if(empty($username)||empty($login)||empty($password)||empty($password1)||empty($email)||empty($phone_number)) $registered=2;
		else if($password!=$password1) {$registered=3; $password=NULL; $password1=NULL;}
		else if(preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/",$username)) {$registered=4; $username=NULL;}
		else if (!preg_match("/[0-9a-zA-Z_.-]+@[a-zA-Z]+\.[a-zA-Z]+/",$email) || preg_match("/[^0-9a-zA-Z_.@-]/",$email)) {$registered=5; $email=NULL;}
		else if(!preg_match("/.{8,}/",$password)) {$registered=6; $password=NULL; $password1=NULL;}
		else if(preg_match("/[^0-9a-zA-Z_.-]/",$password)) {$registered=7; $password=NULL; $password1=NULL;}
		else if (preg_match("/[^0-9]/",$phone_number) || !preg_match("/.{10}/",$phone_number)) {$registered=8; $phone_number=NULL;}
		else if(preg_match("/[^0-9a-zA-Z_.-]/",$login)) {$registered=9; $login=NULL;}
		else if(!preg_match("/.{6,}/",$login)) {$registered=10; $login=NULL;}
		else if(mysql_num_rows($res)!=0) {$registered=11; $login=NULL;}
		else {
				mysql_query(" INSERT INTO users(login,password,email,phone_number,username,locality) VALUES ('$login','$password','$email','$phone_number','$username','$locality')");
				$registered=1;
				$_SESSION['user'] = $login;
				$currlogin=$_SESSION['user'];
		}
		
	}
	mysql_close();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Авторизация</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="style/style.css?0" type="text/css">
		<link rel="stylesheet" href="style/authorization.css?0" type="text/css">
		<link rel="shortcut icon" href="images/paw.ico" type="image/x-icon">
		<meta name="Keywords" content="питомцы, домашние животные, питомцы купить, питомцы продать, домашние животные купить, домашние животные продать, коты, котята, кошки, щенки, собаки, попугаи, рыбки">
	</head>
	<body>
		<div id="header">
			<a href="index.php" id="logo"></a>
			
			<div id="wrapper">
				<?php 
					if($entered==1) echo '<a href="user.php" id="account">'.$oldlogin.'</a>';
					else if(isset($currlogin)) echo '<a href="user.php" id="account">'.$currlogin.'</a>';
					else echo '<a href="#" id="account">Вход / Регистрация</a>';
				?>
				<a href="<?php if(!isset($currlogin)&& $entered!=1) echo 'registration.php'; else echo 'add.php';?>" id="add">НОВОЕ ОБЪЯВЛЕНИЕ</a>
			</div>
		</div>
		<div id="body">
			<form method="post" action="registration.php" id="login">
				Вход в систему:
				<p>Логин:<br> <input type="text" maxlength="20" name="oldlogin" value="<?php if(isset($oldlogin) && $entered!=1) echo $oldlogin; ?>"></p>
				<p>Пароль:<br> <input type="password" maxlength="20" name="oldpassword" value="<?php if(isset($oldpassword) && $entered!=1) echo $oldpassword; ?>"></p>
				<input type="submit" name="in" value="Войти">
				<br>
				<br>
				<?php
					if($entered==1) echo '<div id="win">Вы вошли успешно!</div>'; 
					else if($entered==2)echo '<div id="fail">Не все поля заполнены!</div>';
					else if($entered==3)echo '<div id="fail">Неверный логин!</div>';
					else if($entered==4)echo '<div id="fail">Неверный пароль!</div>';
					$entered=0;
				?>
			</form>
			<form method="post" action="registration.php" id="registration">
				Регистрация:
				<p>Имя:<br> <input type="text" maxlength="20" name="username" value="<?php if(isset($username) && $registered!=1) echo $username; ?>"></p>
				<p>Логин:<br> <input type="text" maxlength="20" name="login" value="<?php if(isset($login) && $registered!=1) echo $login; ?>"></p>
				<p>Пароль:<br> <input type="password" maxlength="20" name="password" value="<?php if(isset($password) && $registered!=1) echo $password; ?>"></p>
				<p>Повторите пароль:<br> <input type="password" maxlength="20" name="password1" value="<?php if(isset($password1) && $registered!=1) echo $password1; ?>"></p>
				<p>Электронная почта:<br> <input type="text" maxlength="40" name="email" placeholder="example@gmail.com" value="<?php if(isset($email) && $registered!=1) echo $email; ?>"></p> 
				<p>Номер телефона:<br> <input type="text" maxlength="10" name="phone_number" placeholder="(050)1234567" value="<?php if(isset($phone_number) && $registered!=1) echo $phone_number; ?>"></p>
				<p>Регион:<br>
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
				<input type="submit" name="add" value="Регистрация">
				<br>
				<br>
				<?php
					if($registered==1) echo '<div id="win">Регистрация прошла успешно!</div>'; 
					else if($registered==2)echo '<div id="fail">Не все поля заполнены!</div>';
					else if($registered==3)echo '<div id="fail">Пароли не совпадают!</div>';
					else if($registered==4)echo '<div id="fail">Недопустимые символы в имени пользователя!</div>';
					else if($registered==5)echo '<div id="fail">Неверный e-mail!</div>';
					else if($registered==6)echo '<div id="fail">Пароль должен содержать как минимум 8 символов!</div>';
					else if($registered==7)echo '<div id="fail">Пароль содержит недопустимые символы!</div>';
					else if($registered==8)echo '<div id="fail">Неверный номер телефона!</div>';
					else if($registered==9)echo '<div id="fail">Логин содержит недопустимые символы!</div>';
					else if($registered==10)echo '<div id="fail">Логин должен содержать как минимум 6 символов!</div>';
					else if($registered==11)echo '<div id="fail">Такой логин уже существует!</div>';
					$registered=0;
				?>
				
			</form>
			
		</div>
		
		<div id="footer">
			2016 All rights recieved
		</div>
	</body>
</html>