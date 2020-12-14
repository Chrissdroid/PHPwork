# Les bases des données

## Lexique

- Fetch : To fetch - Chercher : Aller chercher

- Query - Requête

## L'initialisation (pour une base mysql)

### Avant PHP 7

```php
$host = 'localhost';
$user = 'root';
$password = null;
$db = 'maBaseDeDonnée';

$bdd = new mysqli($host, $user, $password, $db);
$bdd_Err = $bdd->connect_errno;
   
if($bdd_Err) {

    echo 'Erreur de connection';

}
```

### Après PHP 7

```php
try {
    $host = 'localhost';
    $user = 'root';
    $password = null;
    $db = 'maBaseDeDonnée';

    $bdd = new PDO("mysql:dbname=$db;host=$host", $user, $password);

} catch (PDOException $e) {

    echo 'Connexion échouée : ' . $e->getMessage();

}
```

## Requêtes

### Une seule valeur (récupère la première ligne si plusieurs)

```php
$query = $bdd->query("SELECT * FROM user WHERE id = 30;");
$data = $query->fetch();

echo $data['id'];
```

### Plusieurs valeurs

```php
$query = $bdd->query("SELECT * FROM user;");

$data = $query->fetchAll();
foreach ($data as $user) {
    echo $user['id'];
}

# ou alors (sauf certain cas) #
while ($user = $query->fetch()) {
    echo $user['id'];
}
```

## Requêtes (Avancées)

### Méthode barbare

```php
$id = 30;

$query = $bdd->query("SELECT * FROM user WHERE id = {$id};");
$data = $query->fetch();

echo $data['id'];
```

### Méthode nommée

```php
$données = [
    'id' => 30
];

$query = $bdd->prepare("SELECT * FROM user WHERE id = :identifiant;");
$query->execute($données);
$data = $query->fetch();

echo $data['id'];
```

### Méthode Anonymous

```php
$données = [30];

$query = $bdd->prepare("SELECT * FROM user WHERE id = ?;");
$query->execute($données);
$data = $query->fetch();

echo $data['id'];
```

## Conseils d'alex

- Les fetch sont possible qu'une fois sur une même requête, si vous voulez plusieurs fois récuperer les données vous devez réexécuter la requête ou alors l'enregistrer au préalable dans une variable

- Toujours mettre une limite au nombre de résultats, on sais jamais, ça évite de se retrouver avec 50 000 utilisateurs à afficher ou dans le cas d'une seule ligne demandée, limiter a une réponse;
Ex :
`SELECT * FROM user WHERE id = ? LIMIT 1;` ou alors
`SELECT * FROM user LIMIT 50;`

- Preparez vos requetes au début de votre code, et même si c'est pour une petite requêtes, utilisez le combo `prepare => execute` tout le temps; Meilleures performances et surtout requetes plus rapides et légères pour la base de données

- Les requêtes utilisé sous `prepare` peuvent être exécutés plusieurs fois avec des différentes données, utile pour réutiliser ses requêtes *(attention, il est toujours plus approprié et surtout optimisé de récuperer plusieurs données en une grosse requête plutôt qu'en plein de petites)*