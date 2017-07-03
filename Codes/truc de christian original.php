{source}
<?php
$mysqli = new mysqli ('db668555304.db.1and1.com', 'dbo668555304', 'Aspttsluc_1', 'db668555304');

if ($mysqli->connect_errno) {
printf("Echec de la connexion : %s\n", $mysqli->connect_error);
exit();
}
?>
<html>
<head>
<title>Vérification url canoniques</title>
</head>
<body>
<?php
$query = 'SELECT `product_name`,`product_alias`,`product_canonical` FROM `lh81p_hikashop_product` where `product_alias` <>"" and `product_canonical` <>""';
$result = $mysqli->query($query);

?><table>
<th >Nom du produit</th>
<th>Alias</th>
<th>Fin url canonique</th>
<?php
while ($row = $result->fetch_array()) {
$rows[] = $row;
$pos_slash = strripos($row['product_canonical'],"/") + 1;
$fin_url = substr($row['product_canonical'], $pos_slash);
if ($row['product_alias'] !== $fin_url) {
echo '<tr><td>'.$row['product_name'].'</td><td>'.$row['product_alias'].'</td><td>'.$fin_url.'</td></tr>';
}
}
?></table><?php
$result->free();
mysqli_close($mysqli);

?>
</body>
</html>
{/source}
