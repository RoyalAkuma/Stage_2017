{source}
<html>
<head>
<title>Vérification url canoniques</title>
</head>
<body>
<?php

$db = JFactory::getDbo();
// Create a new query object.
$query = $db->getQuery(true);

$query->select($db->quoteName(array('product_name','product_alias','product_canonical')));
$query->from($db->quoteName('lh81p_hikashop_product'));
$query->where($db->quoteName('product_alias') . ' NOT LIKE '. $db->quote("") .' and '. $db->quoteName('product_canonical') . ' NOT LIKE '. $db->quote(""));
// Reset the query using our newly populated query object.
$db->setQuery($query);



?><table>
<th >Nom du produit</th>
<th>Alias</th>
<th>Fin url canonique</th>
<?php

while ($row = $db->loadRow()) {
$pos_slash = strripos($row[2],"/") + 1;
$fin_url = substr($row[2], $pos_slash);
if ($row[1] !== $fin_url) {
echo '<tr><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$fin_url.'</td></tr>';
}
}
?></table><?php
?>
</body>
</html>
{/source}
