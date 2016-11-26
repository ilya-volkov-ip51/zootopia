<?php
	$added=0;
	session_start();
	if (array_key_exists("user", $_SESSION)) {
		$currlogin=$_SESSION['user'];
	} else die("Access denied!");
	include "key.ini";
	mysql_select_db('zootopiadb') or die("Could not select database!");
	mysql_set_charset("utf8");
	if(isset($_POST['add']))
	{
		$age=strip_tags(trim($_POST['age']));
		$category=strip_tags(trim($_POST['category']));
		$male=strip_tags(trim($_POST['male']));
		$name=strip_tags(trim($_POST['name']));
		$description=strip_tags(trim($_POST['description']));
		$price=strip_tags(trim($_POST['price']));
		$date = date("Y-m-d");
		$time = date("H:i:s");
		if(empty($category)||empty($male)||empty($name)||empty($description)||empty($price)) $added=2;
		else if (preg_match("/[^0-9]/",$age)){$added=3; $age=NULL;}
		else if (preg_match("/[^0-9]/",$price)){$added=4; $price=NULL;}
		else if(preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/",$name)) {$added=5; $name=NULL;}
		else {
			$added=1;
			if(empty($age)) $age=-1;
			if($_FILES['userfile']['size'] > 1024*3*1024)$added=7;
			else if(!preg_match("/image/",$_FILES['userfile']['type']))
			{
				$added=8;
				mysql_query(" INSERT INTO ads(author,age,category,male,name,description,price,date,time) VALUES ('$currlogin','$age','$category','$male','$name','$description','$price','$date','$time')");
			}
			else
			{
				$uploaddir = 'userfiles/ads/';
				$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
				if(preg_match("/[\x7F-\xFF]/" , $uploadfile)){$added=9; echo $uploadfile;} else
				if(mysql_query(" INSERT INTO ads(author,age,category,male,name,description,photo,price,date,time) VALUES ('$currlogin','$age','$category','$male','$name','$description','$uploadfile','$price','$date','$time')")!=NULL){
					if (!move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) $added=6;
				}else $added=10;
			}
			
			$id=mysql_insert_id();
			if($id!=0)
			{
				$res=mysql_query("SELECT * FROM users WHERE login='$currlogin'");
				$row=mysql_fetch_array($res);
				$ads_id=$row['ads_id'];
				$ads_id=$ads_id.','.$id;
				mysql_query(" UPDATE users SET ads_id='$ads_id' WHERE login = '$currlogin'");
			}
		}
	}
	mysql_close();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Новое объявление</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="style/style.css?0" type="text/css">
		<link rel="stylesheet" href="style/add.css?0" type="text/css">
		<link rel="shortcut icon" href="images/paw.ico" type="image/x-icon">
		<meta name="Keywords" content="питомцы, домашние животные, питомцы купить, питомцы продать, домашние животные купить, домашние животные продать, коты, котята, кошки, щенки, собаки, попугаи, рыбки">
		<script type="text/javascript" src="scripts.js"></script>
		<script type="text/javascript" src="jquery.js"></script>
	</head>
	<body>
		<div id="header">
			<a href="index.php" id="logo"></a>
			
			<div id="wrapper">
				<?php
					if(!isset($currlogin)) echo '<a href="registration.php" id="account">Войти в профиль</a>';
					else echo '<a href="user.php" id="account">'.$currlogin.'</a>';
				?>
				<a href="add.php" id="add">НОВОЕ ОБЪЯВЛЕНИЕ</a>
			</div>
		</div>
		<div id="body">
		<br>
			<h2>Вы перешли в раздел создания обьявления!</h2>
			<form method="post" action="add.php" id="newad" enctype="multipart/form-data">
			<div  id="lefthalf">
				<p>Кого вы хотите продать?<br> <input type="text" name="name" maxlength="30" value="<?php if(isset($name) && $added!=1 && $added!=8) echo $name; ?>"></p>
				<p>Выберите категорию:<br> 
				<select name="category" size="1">
					<option <?php if(!empty($category) && $added!=1 && $added!=8)if($category=='all') echo 'selected'; ?> value="all">Не выбрана
					<option <?php if(!empty($category) && $added!=1 && $added!=8)if($category=='cats') echo 'selected'; ?> value="cats">Кошки
					<option <?php if(!empty($category) && $added!=1 && $added!=8)if($category=='dogs') echo 'selected'; ?> value="dogs">Собаки
					<option <?php if(!empty($category) && $added!=1 && $added!=8)if($category=='rabbits') echo 'selected'; ?> value="rabbits">Кролики
				</select>
				</p>
				<p>Возраст (по желанию):<br> <input type="text" name="age" maxlength="3" value="<?php if(isset($age) && $added!=1 && $added!=8) echo $age; ?>"></p>
				<p>Пол:<br>
				<select name="male" size="1">
					<option <?php if(!empty($male) && $added!=1 && $added!=8)if($male=='none') echo 'selected'; ?> value="none">Не выбран
					<option <?php if(!empty($male) && $added!=1 && $added!=8)if($male=='male') echo 'selected'; ?> value="male">Мальчик
					<option <?php if(!empty($male) && $added!=1 && $added!=8)if($male=='female') echo 'selected'; ?> value="female">Девочка
				</select>
				</p>
				<p>Укажите цену (в гривнах):<br> <input type="text" name="price" maxlength="6" value="<?php if(isset($price) && $added!=1 && $added!=8) echo $price; ?>"></p>
				<input type="submit" name="add" value="Создать обьявление">
				<br>
				<br>
				<?php
					if($added==1) echo '<div id="win">Объявление успешно создано!</div>'; 
					else if($added==2)echo '<div id="fail">Не все обязательные поля заполнены!</div>';
					else if($added==3)echo '<div id="fail">Неверно указан возраст!</div>';
					else if($added==4)echo '<div id="fail">Неверно указана цена!</div>';
					else if($added==5)echo '<div id="fail">Недопустимые символы в названии!</div>';
					else if($added==6)echo '<div id="fail">Файл не был загружен!</div>';
					else if($added==7)echo '<div id="fail">Файл не должен быть больше 3 МБ!</div>';
					else if($added==8)echo '<div id="win">Объявление создано, но фото загружено не было!</div>';
					else if($added==9)echo '<div id="fail">Объявление загружено не было! Неверный путь к фото!</div>';
					else if($added==10)echo '<div id="fail">Объявление загружено не было по неизвестной причине!</div>';
					$added=0;
				?>
				</div>
				<div  id="righthalf">
					<p>Опишите своего питомца:<br> <textarea name="description" maxlength="2000"><?php if(isset($description) && $added!=1 && $added!=8) echo $description; ?></textarea></p>
					<p>Фотография (по желанию):<br>
					<div class="fileform">
					<div id="fileformlabel"></div>
					<div class="selectbutton">Обзор</div>
					<input type='file' name="userfile" id="imgInp" onchange="readURL(this); getName(this.value);" >
					</div></p>
					<br>
					<img id="sample" src="#" alt="Ваше фото	">
				</div>
			</form>
		</div>
		
		<div id="footer">
			2016 All rights recieved
		</div>
	</body>
</html>