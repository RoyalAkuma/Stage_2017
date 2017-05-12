<?php


$this->execPieceByName('ff_InitLib');

$email = ff_getSubmit('email');
$nom_esat = ff_getSubmit('nom_esat');
$alias = strtolower(ff_getSubmit('nom_esat'));
$adresse1 = ff_getSubmit('adresse1');
$adresse2 = ff_getSubmit('adresse2');
$codepostale = ff_getSubmit('code_postal');
$city = ff_getSubmit('ville');
$telephone = ff_getSubmit('telephone1');
$iban = ff_getSubmit('IBAN');
$bic = ff_getSubmit('BIC');



$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('user_id');
$query->from($db->quoteName('lh81p_hikashop_user'));
$query->where($db->quoteName('user_email')." = ".$db->quote($email));

// Reset the query using our newly populated query object.
$db->setQuery($query);
$admin_id = $db->loadResult();


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
$vendeur->vendor_address_region = "";
$vendeur->vendor_iban = $iban;
$vendeur->vendor_bic = $bic;
$vendeur->vendor_frais_port = "";

$result = JFactory::getDbo()->insertObject('lh81p_hikamarket_vendor', $vendeur);
















?>
