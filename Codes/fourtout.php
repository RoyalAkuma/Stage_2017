//
           "Pays de la Loire" 1
           $Ld1 = (44,49,53,72,85);

           "Bretagne" 2
           $Ld2 = (22,29,35,56);

           "Normandie" 3
           $Ld3 = (14,27,50,61,76);

           "Hauts-de-France" 4
           $Ld4 = (2,59,60,62,80);

           "Grand Est" 5
           $Ld5 = (8,10,51,52,54,55,57,67,68,88);

           "Bourgogne-France-Comté" 6
           $Ld6 = (21,25,39,58,70,71,89,90);

           "Auvergne-Rhônes-Alpes" 7
           $Ld7 = (1,3,7,15,26,38,42,43,63,69,73,74);

           "Provence Cote d'Azure"8
           $Ld8 = (4,5,6,13,83,84);

           "Corse" 9
           $Ld9 = (2A, 2B);

           "Occitanie" 10
           $Ld10 = (09,11,12,30,31,32,34,46,48,65,66,81,82);

           "Nouvelle-Aquitaine"11
           $Ld11 = (16,17,19,23,24,33,40,47,64,79,86,87);

           "Centre-Val de Loire"12
           $Ld12 = (18,28,36,37,41,45);

           "ile de France"13
           $Ld13 = (75,77,78,91,92,93,94,95);









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
