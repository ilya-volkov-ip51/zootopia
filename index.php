<?php
	if(isset($_POST['out']))
	{
		session_start();
		unset($_SESSION['user']);
		session_unset();
		session_destroy();
	}
	include "key.ini";
	mysql_select_db('zootopiadb') or die("Could not select database!");
	mysql_set_charset("utf8");
	$result = mysql_query(" SELECT * FROM ads ORDER BY date DESC, time DESC");
	$count=mysql_num_rows($result);
	if (isset($_GET['i'])) {
		$i=intval($_GET['i']);
	} else $i=0;
	if($i<0) $i=0;
	if($i>$count) $i=0;	
	session_start();
	if (array_key_exists("user", $_SESSION)) {
		$currlogin=$_SESSION['user'];
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Zootopia - Главная</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="style/style.css?0" type="text/css">
		<link rel="shortcut icon" href="images/paw.ico" type="image/x-icon">
		<meta name="Keywords" content="питомцы, домашние животные, питомцы купить, питомцы продать, домашние животные купить, домашние животные продать, коты, котята, кошки, щенки, собаки, попугаи, рыбки">
		<script type="text/javascript" src="scripts.js"></script>
	</head>
	<body>
		<div id="header">
			<a href="index.php" id="logo"></a>
			
			<div id="wrapper">
				<?php 
					if(!isset($currlogin)) echo '<a href="registration.php" id="account">Вход / Регистрация</a>';
					else echo '<a href="user.php" id="account">'.$currlogin.'</a>';
				?>
				<a href="<?php if(!isset($currlogin)) echo 'registration.php'; else echo 'add.php';?>" id="add">НОВОЕ ОБЪЯВЛЕНИЕ</a>
			</div>
		</div>
		<div id="body">
			<form method="get" action="#" id="menu">
				<div id="category">Категории животных</div>
				<div class="category" onClick="f('animals');">Животные</div>
				<div id="animals" class="subcategory" >
					<input id="cats" class="radio" type="checkbox" name="animals" value="cats">
					<label for="cats">Кошки</label>
					<br>
					<br>
					<input id="dogs" class="radio" type="checkbox" name="animals" value="dogs">
					<label for="dogs">Собаки</label>
					<br>
					<br>
					<input id="rabbits" class="radio" type="checkbox" name="animals" value="rabbits">
					<label for="rabbits">Кролики</label>
				</div>
				<div class="category" onClick="f('male');">Пол</div>
				<div id="male" class="subcategory" >
					<input id="m" class="radio" type="radio" name="male" value="male">
					<label for="m">Мальчик</label>
					<br>
					<br>
					<input id="f" class="radio" type="radio" name="male" value="female">
					<label for="f">Девочка</label>
				</div>
				<div class="category" onClick="f('age');">Возраст</div>
				<div id="age" class="subcategory" >
					От: <input type="text" name="agestart"> 
					до: <input type="text" name="ageend"> лет
				</div>
				<div class="category" onClick="f('price');">Цена</div>
				<div id="price" class="subcategory">
					От: <input type="text" name="pricestart">
					до: <input type="text" name="priceend"> грн
				</div>
				<button type="submit">Отсортировать</button>
				<button type="reset">Сбросить фильтры</button>
			</form>
			<div id="main">
				<div id ="search">
					<form method="get" action="#" id="search-form">
						<select name="locality" size="1">
							<option selected value="all">Искать по всей Украине	
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
						</select>
						<input type="text" name="search" placeholder="Что-то интересует?">
						<input type="submit" name="" value="Найти">
					</form>
				</div>
				
				<?php
				$j=$i;
				$count=0;
				while($j>0){
					if(!$row = mysql_fetch_array($result)) break;
					$author=$row['author'];
					$result1 = mysql_query(" SELECT * FROM users WHERE login='$author'");
					$author =mysql_fetch_array($result1);
					if(!empty($_GET['locality'])) if($author['locality']!=$_GET['locality'] && $_GET['locality']!='all') continue;
					if(!empty($_GET['animals'])) if($row['category']!=$_GET['animals']) continue;
					if(!empty($_GET['male'])) if($row['male']!=$_GET['male']) continue;
					if(!empty($_GET['agestart'])) if($row['age']<$_GET['agestart']) continue;
					if(!empty($_GET['ageend'])) if($row['age']>$_GET['ageend']) continue;
					if(!empty($_GET['pricestart'])) if($row['price']<$_GET['pricestart']) continue;
					if(!empty($_GET['priceend'])) if($row['price']>$_GET['priceend']) continue;
					if(!empty($_GET['search'])) if(stripos(strtolower ($row['name']), strtolower ($_GET['search']))===false) continue;
					$count++;
					$j--;
				}				
				while($j<10)
				{
					if(!$row = mysql_fetch_array($result)) break;
					$author=$row['author'];
					$result1 = mysql_query(" SELECT * FROM users WHERE login='$author'");
					$author =mysql_fetch_array($result1);
					if(!empty($_GET['locality'])) if($author['locality']!=$_GET['locality'] && $_GET['locality']!='all') continue;
					if(!empty($_GET['animals'])) if($row['category']!=$_GET['animals']) continue;
					if(!empty($_GET['search'])) if(stripos(mb_strtolower ($row['name'],'UTF-8'), mb_strtolower ($_GET['search'],'UTF-8'))===false)continue;
					if(!empty($_GET['male'])) if($row['male']!=$_GET['male']) continue;
					if(!empty($_GET['agestart'])) if($row['age']<$_GET['agestart']) continue;
					if(!empty($_GET['ageend'])) if($row['age']>$_GET['ageend']) continue;
					if(!empty($_GET['pricestart'])) if($row['price']<$_GET['pricestart']) continue;
					if(!empty($_GET['priceend'])) if($row['price']>$_GET['priceend']) continue;
					$count++;
					echo '<div class="ads">';
					echo '<a href="ads.php?id='.$row['id'].'">';
					echo '<img src="'.$row['photo'].'">';
					echo '<h2>'.$row['name'].'</h2>';
					echo '<b>'.$row['price'].' грн</b>';
					echo '<div class="text">';
					echo '<p>'.$author['locality'].' '.$row['date'].' '.$row['time'].'</p>';
					echo '</div>';
					echo '</a>';
					echo '</div>';
					$j++;
				}
				mysql_close();
				?>
				
				<div id="pages">
				<div id="prev" onclick="prev('<?php echo $i?>','<?php echo $count?>');"><-Новее</div>
				<div id="next" onclick="next('<?php echo $i?>','<?php echo $count?>');">Старее-></div>
				</div>
				
			</div>
		</div>
		
		<div id="footer">
			2016 All rights recieved
		</div>
		<a href="#" class="topbutton"></a>
	</body>
</html>