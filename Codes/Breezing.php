<?php

//initialisation et recupération des champs dans le formulaire
$this->execPieceByName('ff_InitLib');
//nom de l'esat
$nom_esat = ff_getSubmit('nom_esat');
$alias = strtolower(ff_getSubmit('nom_esat'));
//adresse de l'esat
$adresse1 = ff_getSubmit('adresse1');
$adresse2 = ff_getSubmit('adresse2');
$codepostale = ff_getSubmit('code_postal');
$city = ff_getSubmit('ville');
//coordonnée telephone
$telephone = ff_getSubmit('telephone1');
//coordonnée banquaire
$iban = ff_getSubmit('IBAN');
$bic = ff_getSubmit('BIC');

//test pour savoir si l'id est le bon

$user = JFactory::getUser();
$email = $user->email;
$id_soli = $user->username;

$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('user_id');
$query->from($db->quoteName('lh81p_hikashop_user'));
$query->where($db->quoteName('user_email')." = ".$db->quote($email));

// Reset the query using our newly populated query object.
$db->setQuery($query);
$admin_id = $db->loadResult();


$Region  = array(
    "44" => 1,
    "49" => 1,
    "53" => 1,
    "72" => 1,
    "85" => 1,
    "22" => 2,
    "29" => 2,
    "35" => 2,
    "56" => 2,
    "14" => 3,
    "27" => 3,
    "50" => 3,
    "61" => 3,
    "76" => 3,
    "2" => 4,
    "02" => 4,
    "59" => 4,
    "60" => 4,
    "80" => 4,
    "8" => 5,
    "08" => 5,
    "10" => 5,
    "51" => 5,
    "52" => 5,
    "54" => 5,
    "57" => 5,
    "67" => 5,
    "68" => 5,
    "88" => 5,
    "21" => 6,
    "25" => 6,
    "39" => 6,
    "58" => 6,
    "70" => 6,
    "71" => 6,
    "89" => 6,
    "90" => 6,
    "1" => 7,
    "01" => 7,
    "3" => 7,
    "03"=> 7,
    "7" => 7,
    "07"=> 7,
    "15" => 7,
    "26" => 7,
    "38" => 7,
    "42" => 7,
    "43" => 7,
    "63" => 7,
    "69" => 7,
    "73" => 7,
    "74" => 7,
    "4" => 8,
    "04" => 8,
    "5" => 8,
    "05"=> 8,
    "6" => 8,
    "06"=> 8,
    "13" => 8,
    "83" => 8,
    "84" => 8,
    "2A" => 9,
    "2B" => 9,
    "9" => 10,
    "11" => 10,
    "12" => 10,
    "30" => 10,
    "31" => 10,
    "32" => 10,
    "34" => 10,
    "46" => 10,
    "48" => 10,
    "65" => 10,
    "66" => 10,
    "81" => 10,
    "82" => 10,
    "16" => 11,
    "17" => 11,
    "19" => 11,
    "23" => 11,
    "24" => 11,
    "33" => 11,
    "40" => 11,
    "47" => 11,
    "64" => 11,
    "79" => 11,
    "86" => 11,
    "87" => 11,
    "18" => 12,
    "28" => 12,
    "36" => 12,
    "37" => 12,
    "41" => 12,
    "45" => 12,
    "75" => 13,
    "77" => 13,
    "78" => 13,
    "91" => 13,
    "92" => 13,
    "93" => 13,
    "94" => 13,
    "95" => 13,
);


$code2 =  substr ( $codepostale, 0 , -3 );
$Cregion = $Region[$code2];


$vendeur = new stdClass();

$vendeur->vendor_id = "";
$vendeur->vendor_admin_id = $admin_id;
$vendeur->vendor_name = $nom_esat;
$vendeur->vendor_alias = $alias;
$vendeur->vendor_canonical = "";
$vendeur->vendor_email = $email;
//mettre à 1 pour publier ?
$vendeur->vendor_published = 0;
$vendeur->vendor_currency_id = 1;
$vendeur->vendor_description = "";
//pour le mettre dans les groupes Vendeurs
$vendeur->vendor_access = "@0,@12";
$vendeur->vendor_shippings = "";
$vendeur->vendor_params = "";
$vendeur->vendor_image = "";
$vendeur->vendor_created = "";
$vendeur->vendor_modified = "";
$vendeur->vendor_template_id = "";

$vendeur->vendor_address_company = "";
$vendeur->vendor_address_street = $adresse1;
$vendeur->vendor_address_street2 = $adresse2;
$vendeur->vendor_address_post_code = $codepostale;
$vendeur->vendor_address_city = $city;
$vendeur->vendor_address_fax = "";
$vendeur->vendor_address_state = "";
$vendeur->vendor_address_country = "country_France_73";
$vendeur->vendor_address_vat = "";
$vendeur->vendor_zone_id = 0;
$vendeur->vendor_site_id = "";
$vendeur->vendor_address_telephone = $telephone;
$vendeur->vendor_average_score = 0;
$vendeur->vendor_total_vote = 0;
$vendeur->vendor_terms = "";


$vendeur->vendor_address_region = $Cregion;
$vendeur->vendor_iban = $iban;
$vendeur->vendor_bic = $bic;
$vendeur->vendor_frais_port = "";

$result = JFactory::getDbo()->insertObject('lh81p_hikamarket_vendor', $vendeur);

?>
