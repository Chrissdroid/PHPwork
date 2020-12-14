<?php
require_once './src/php/database.php';

(isset($_GET['id'])) ? $user = $data[$_GET['id']] : header('Location: ./');
?>
<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= isset($user) ? "{$user['firstName']} {$user['lastName']}" : '404' ?></title>

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">

	<link rel="preload stylesheet" as="style" href="src/css/main.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
	<nav>
		<div class="nav-wrapper">
			<div class="container">
				<a href="./" class="breadcrumb">Recherche</a>
				<span class="breadcrumb"><?= isset($user) ? "{$user['firstName']} {$user['lastName']}" : '404' ?></span>
			</div>
		</div>
	</nav>
	<main>
		<?php if (isset($user)): ?>
		<section>
			<div class="row">
				<div class="col s12 m8 l6">
					<div class="card">
						<div class="card-image">
							<img src="src/img/unnamed.jpg">
							<h1 class="card-title"><strong><?= "{$user['firstName']} {$user['lastName']}" ?></strong>
							</h1>
							<a class="btn-floating btn-large halfway-fab waves-effect waves-light <?= implode(' ', $user['theme']) ?>">
								<?php if ($user['avatar']['type'] == 'IMG'): ?>

								<img src="src/img/<?= $user['avatar']['path'] ?>"
									alt="<?= "Avatar de {$user['firstName']} {$user['lastName']}" ?>"
									style="height:100%">

								<?php elseif ($user['avatar']['type'] == 'SVG'): ?>
								<div class="circle">
									<svg width="100%" height="100%" viewBox="0 0 24 24" fill="none"
										xmlns="http://www.w3.org/2000/svg">
										<?= $user['avatar']['path'] ?>
									</svg>
								</div>

								<?php endif; ?>
							</a>
						</div>
						<div class="card-content">
							<span style="margin-left:0;margin-right:14px"
								class="left new badge <?= implode(' ', $user['theme']) ?>"
								data-badge-caption="ans"><?= $user['age'] ?></span>
							<p><?= $user['description'] ?></p>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php else: ?>
		<blockquote>
			L'utilisateur n'existe pas
		</blockquote>
		<?php endif; ?>
	</main>

	<script src="src/js/bin/materialize.min.js"></script>
</body>

</html>