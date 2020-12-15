<?php

/**
 * Controlleur de notre système de recherche
 */
class searchController {
	private $donnée;
	private $allowSVG;
	
	/**
	 * Méthode constructeur
	 *
	 * @param array $donnée Données contennant un tableau des utilisateurs
	 *
	 * @return void
	 */
	public function __construct(array $donnée, bool $allowSVG = true) {
		$this->donnée = $donnée;
		$this->allowSVG = $allowSVG;
	}
	
	/**
	 * Méthode utilisé pour faire la recherches 
	 *
	 * @param string|null $query Recherche a effectuer
	 *
	 * @return array Utilisateurs trouvés par la recherche
	 */
	function makeSearch($query): array {
		if (empty($query)) return $this->donnée;

		foreach ($this->donnée as $id => $user) stripos("{$user['firstName']} {$user['lastName']}", $query) === false ?: $results[$id] = $user;
		return $results;
	}

	/**
	 * Méthode pour transformer un code svg en url
	 *
	 * @param string $url code svg à transformer 
	 *
	 * @return string code formaté
	 */
	private function svgUrlEncode(string $url): string {
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
	 * @return array tableau d'utilisateur transformé
	 */
	function transformUser(): array {
		foreach ($this->donnée as $user)
			$return["{$user['firstName']} {$user['lastName']}"] = ($user['avatar']['type'] == 'IMG') ?
				("src/img/".$user['avatar']['path']) :
				(($user['avatar']['type'] == 'SVG' and $this->allowSVG) ?
					'data:image/svg+xml,'.$this->svgUrlEncode('<svg width="42" height="42" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">'.$user['avatar']['path']."</svg>") :
					null
				);
		return $return ?? [];
	}
}