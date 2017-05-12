<?php

$this->execPieceByName('ff_InitLib');

$db = JFactory::getDbo();

// Create a new query object.
$query = $db->getQuery(true);


// on vérifie que le mail correspond bien à un mail de la base de donné
$query->select($db->quoteName('user_id'));
$query->from($db->quoteName('lh81p_hikashop_user'));
$query->where($db->quoteName('user_email') . ' LIKE '. $db->quote(ff_getSubmit('email'));




// Reset the query using our newly populated query object.
$db->setQuery($query);

$results = $db->loadResult();


$query2 = $db->getQuery(true);

  // Create and populate an object.
$vendeur = new stdClass();
  //regarder comment c'est attribué
$vendeur->vendor_id = ;
$vendeur->vendor_admin_id = $results;
  // nom_esat
$vendeur->vendor_name = ff_getSubmit('nom_esat');
$vendeur->vendor_alias = strtolower(ff_getSubmit('nom_esat'));
$vendeur->vendor_canonical = "";
$vendeur->vendor_email = ff_getSubmit('email');
  //par défaut 1, le changement en "actif le fera passer à 0, à verifier"
$vendeur->vendor_published = 1;
  //par défaut à 1, en euros j'imagine;
$vendeur->vendor_currency_id = 1;
$vendeur->vendor_description = "";
$vendeur->vendor_access = "@0,@12";
$vendeur->vendor_shippings = "";
$vendeur->vendor_params = "O:8:"stdClass":5:{s:21:"invoice_number_format";s:0:"";s:12:"paypal_email";s:0:"";s:18:"product_limitation";s:0:"";s:20:"notif_order_statuses";s:0:"";s:16:"extra_categories";s:0:"";}";
$vendeur->vendor_image = "";
$vendeur->vendor_created = 0;
$vendeur->vendor_modified = 0;
$vendeur->vendor_template_id = "";
$vendeur->vendor_address_company = "";
//adresse
$vendeur->vendor_address_street = ff_getSubmit('adresse1');
$vendeur->vendor_address_street2 = ff_getSubmit('adresse2');
$vendeur->vendor_address_post_code = ff_getSubmit('code_postale');
$vendeur->vendor_address_city = ff_getSubmit('ville');
$vendeur->vendor_address_fax = "";

$vendeur->vendor_address_state = "state_Maine_et_Loire_1355";
$vendeur->vendor_address_country = "country_France_73";
$vendeur->vendor_address_vat = "";
$vendeur->vendor_zone_id = 0;
$vendeur->vendor_site_id = "";
//tel
$vendeur->vendor_address_telephone = ff_getSubmit('telephone1');
$vendeur->vendor_average_score = 0.00000;
$vendeur->vendor_total_vote = 0;
$vendeur->vendor_terms = "";
//obligatoire
$vendeur->vendor_address_region = 1;
//banque
$vendeur->vendor_iban = ff_getSubmit('IBAN');
$vendeur->vendor_bic = ff_getSubmit('BIC');
$vendeur->vendor_frais_port = "";
// Insert the object into the user profile table.
$result = JFactory::getDbo()->insertObject('lh81p_hikamarket_vendor', $vendeur);
// Set the query using our newly populated query object and execute it.


?>
