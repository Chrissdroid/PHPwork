<?php
require_once './src/php/database.php';

if (!empty($_GET['q']))
	foreach ($data as $id => $user)
		stripos("{$user['firstName']} {$user['lastName']}", $_GET['q']) === false ?: $results[$id] = $user;

$results = $results ?? $data;

/**
 * Méthode pour transformer un code svg en url
 *
 * @param string $url code svg à transformer 
 *
 * @return string code formaté
 */
function svgUrlEncode(string $url): string {
	$url = \preg_replace('/\v(?:[\v\h]+)/', ' ', $url);
	$url = \str_replace('"', "'", $url);
	$url = \rawurlencode($url);
	// re-decode a few characters understood by browsers to improve compression
	$url = \str_replace('%20', ' ', $url);
	$url = \str_replace('%3D', '=', $url);
	$url = \str_replace('%3A', ':', $url);
	$url = \str_replace('%2F', '/', $url);
	return $url;
}

/**
 * Méthode qui permet de faire l'intégration de l'autocomplete de materialize
 * 
 * [Voir la doc](https://materializecss.com/autocomplete.html)
 *
 * @param array $data tableau d'utilisateur
 *
 * @return array tableau d'utilisateur transformé
 */
function transformUser(array $data): array {
	foreach ($data as $user)
		$return["{$user['firstName']} {$user['lastName']}"] = ($user['avatar']['type'] == 'IMG') ?
			("src/img/".$user['avatar']['path']) :
			(($user['avatar']['type'] == 'SVG') ?
				'data:image/svg+xml,'.svgUrlEncode('<svg width="42" height="42" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">'.$user['avatar']['path']."</svg>") :
				null
			);
	return $return ?? [];
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Liste des Utilisateurs</title>

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">

	<link rel="preload stylesheet" as="style" href="src/css/main.css">
</head>

<body>
	<nav>
		<div class="nav-wrapper">
			<div class="container">
				<span class="breadcrumb">Recherche<?= empty($_GET['q']) ? null : ' - '.count($results)." résultat".(count($results)<1 ?: 's') ?></span>
			</div>
		</div>
	</nav>
	<main class="row">
		<section class="col s7">
			<div class="container">
				
				<?php if (count($results)) { ?>
				<ul class="collection">

					<?php foreach ($results as $id => $result) { ?>

					<li class="collection-item avatar">
						<?php if ($result['avatar']['type'] == 'IMG'): ?>

						<img src="src/img/<?= $result['avatar']['path'] ?>"
							alt="<?= "Avatar de {$result['firstName']} {$result['lastName']}" ?>" class="circle">

						<?php elseif ($result['avatar']['type'] == 'SVG'): ?>
						<div class="circle <?= implode(' ', $result['theme']) ?>">
							<svg width="42" height="42" viewBox="0 0 24 24" fill="none"
								xmlns="http://www.w3.org/2000/svg">
								<?= $result['avatar']['path'] ?>
							</svg>
						</div>

						<?php endif; ?>

						<span class="title"><?= "{$result['firstName']} {$result['lastName']}" ?></span>

						<p>
							<span class="new badge <?= implode(' ', $result['theme']) ?>" style="float:none" data-badge-caption="ans">
								<?= $result['age'] ?>
							</span>
						</p>

						<a href="user.php?id=<?= $id ?>"
							class="secondary-content tooltipped <?= "{$result['theme']['color']}-text text-{$result['theme']['acc']}" ?>" data-position="left" data-tooltip="Voir plus">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
								xmlns="http://www.w3.org/2000/svg">
								<path
									d="M12.0519 14.8285L13.4661 16.2427L17.7087 12L13.4661 7.7574L12.0519 9.17161L13.8803 11H6.34318V13H13.8803L12.0519 14.8285Z"
									fill="currentColor" />
								<path fill-rule="evenodd" clip-rule="evenodd"
									d="M1 19C1 21.2091 2.79086 23 5 23H19C21.2091 23 23 21.2091 23 19V5C23 2.79086 21.2091 1 19 1H5C2.79086 1 1 2.79086 1 5V19ZM5 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3H5C3.89543 3 3 3.89543 3 5V19C3 20.1046 3.89543 21 5 21Z"
									fill="currentColor" />
							</svg>
						</a>
					</li>

					<?php } ?>
				</ul>
				<?php } else { ?>
					<blockquote>
						Ce que vous recherchez n'existe pas
					</blockquote>
				<?php } ?>
			</div>
		</section>
		<aside class="col s5">
			<form class="container" action="" id="searchForm" method="get">
				<div class="row">
					<div class="input-field col s12">
						<svg class="prefix" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.55024 10.5503C8.55024 11.1026 8.10253 11.5503 7.55024 11.5503C6.99796 11.5503 6.55024 11.1026 6.55024 10.5503C6.55024 9.99801 6.99796 9.55029 7.55024 9.55029C8.10253 9.55029 8.55024 9.99801 8.55024 10.5503Z" fill="currentColor" /><path d="M10.5502 11.5503C11.1025 11.5503 11.5502 11.1026 11.5502 10.5503C11.5502 9.99801 11.1025 9.55029 10.5502 9.55029C9.99796 9.55029 9.55024 9.99801 9.55024 10.5503C9.55024 11.1026 9.99796 11.5503 10.5502 11.5503Z" fill="currentColor" /><path d="M13.5502 11.5503C14.1025 11.5503 14.5502 11.1026 14.5502 10.5503C14.5502 9.99801 14.1025 9.55029 13.5502 9.55029C12.998 9.55029 12.5502 9.99801 12.5502 10.5503C12.5502 11.1026 12.998 11.5503 13.5502 11.5503Z" fill="currentColor" /><path fill-rule="evenodd" clip-rule="evenodd" d="M16.2071 4.89344C19.0922 7.7786 19.313 12.3192 16.8693 15.4577C16.8846 15.4712 16.8996 15.4853 16.9142 15.4999L21.1568 19.7426C21.5473 20.1331 21.5473 20.7663 21.1568 21.1568C20.7663 21.5473 20.1331 21.5473 19.7426 21.1568L15.5 16.9141C15.4853 16.8995 15.4713 16.8846 15.4578 16.8693C12.3193 19.3131 7.77858 19.0923 4.89338 16.2071C1.76918 13.083 1.76918 8.01763 4.89338 4.89344C8.01757 1.76924 13.0829 1.76924 16.2071 4.89344ZM6.30759 14.7929C8.65074 17.1361 12.4497 17.1361 14.7929 14.7929C17.136 12.4498 17.136 8.6508 14.7929 6.30765C12.4497 3.96451 8.65074 3.96451 6.30759 6.30765C3.96445 8.6508 3.96445 12.4498 6.30759 14.7929Z" fill="currentColor" /></svg>
						<input type="text" name="q" value="<?= !empty($_GET['q']) ? htmlspecialchars($_GET['q'], ENT_QUOTES, 'UTF-8') : '' ?>" id="autocomplete-input" class="autocomplete" autocomplete="off">
						<label for="autocomplete-input">Rechercher</label>
					</div>
				</div>
			</form>
		</aside>
	</main>

	<script src="src/js/bin/materialize.min.js"></script>
	<script>
		M.AutoInit();

		M.Autocomplete.init(document.querySelectorAll('.autocomplete'), {
			data: <?= json_encode(transformUser($data)) ?>,
			onAutocomplete: () => {
				let form = document.querySelector('#searchForm');
				form.submit();
			}
		});
	</script>
</body>

</html>