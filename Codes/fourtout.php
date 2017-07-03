        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
        <script type="text/javascript" src="https://widget.mondialrelay.com/parcelshop-picker/v3_2_1/scripts/jquery.plugin.mondialrelay.parcelshoppicker.min.js"></script>
        <script type="text/javascript">
            var t;
            $(document).ready(function () {

                // Charge le widget dans la DIV d'id "Zone_Widget" avec les paramètres indiqués
                 $("#Zone_Widget").MR_ParcelShopPicker({
                    Target: "#Target_Widget"                                // Selecteur JQuery de l'élément dans lequel sera renvoyé l'ID du Point Relais sélectionné (généralement un champ input hidden)
                    , TargetDisplay: "#TargetDisplay_Widget"                // Selecteur JQuery de l'élément dans lequel sera renvoyé l'ID du Point Relais sélectionné (secondaire pour affichage)
                    , TargetDisplayInfoPR: "#TargetDisplayInfoPR_Widget"    // Selecteur JQuery de l'élément dans lequel seront renvoyé les coordonnées complètes de la selection de l'utilisateur
                    , Brand: "BDTEST13"                                     // Le code client Mondial Relay
                                                                            // Lorsqu'il est indiqué "BDTEST  ", un message d'avertissement apprait pour avertir que ce sont les paramètres de test qui sont utilisés
                    , Country: "FR"                                         // Code ISO 2 lettres du pays utilisé pour la recherche
                    //,AllowedCountries: "FR,ES"                            // Liste des pays selectionnable par l'utilisateur pour la recherche (codes ISO 2 lettres séparés par des virgules)
                    , PostCode: "59000"                                     // Code postal pour lancer une recherche par défaut
                    //,EnableGeolocalisatedSearch: "true"                   // Active ou non la possibilité d'effectuer la recherche sur la position courante lorsque le navigateur de l'utilisateur supporte cette fonction (demande au navigateur)
                    , ColLivMod: "24R"                                      // Permet de filtrer les Points Relais selon le mode de livraison utilisé (Standard [24R], XL [24L], XXL [24X], Drive [DRI])
                    //,Weight: ""                                           // Permet de filtrer les Points Relais selon le Poids (en grammes) du colis à livrer
                    , NbResults: "7"                                        // Nombre de Point Relais à afficher
                    //,SearchDelay: "3"                                     // Permet de spécifier le nombre de jour entre la recherche et la dépose du colis dans notre réseau
                    //,SearchFar: ""                                        // Permet de limiter la recherche des Points Relais à une distance maximum
                    //,CSS: "1"                                             // Permet de spécifier que vous souhaitez utiliser votre propre feuille de style CSS lorsque vous lui donnez la valeur "0"
                    //,MapScrollWheel: "false"                              // Active ou non le zoom on scroll sur la carte des résultats
                    //,MapStreetView: "false"                               // Active ou non le mode Street View sur la carte des résultats (attention aux quotas imposés par Google)
                    //,ShowResultsOnMap: "true"                             // Active ou non l'affichage des résultats sur une carte
                    , DisplayMapInfo: true                                  // Active ou non l'affichage d'une popup sur la carte avec les informations du point relais
                    , OnParcelShopSelected:                                 // Permet l'appel d'une fonction lors de la selection d'un Point Relais
                        function (data) {                                   // Implémentation de la fonction de traitement, le paramètre Data contient un objet avec les informations du Point Relais

                            // Remplace les données de la balise ayant l'Id "cb_ID" par le contenu html de data.ID
                            // "data" est le paramètre reçu par la fonction, sont contenu est inconnu à la compilation
                            // "ID" est contenu dans "data", il pourrait y avoir une erreur si "ID" n'existe pas dans la variable "data" reçue en paramètre
                            $("#cb_ID").html(data.ID);
                            $("#cb_Nom").html(data.Nom);
                            $("#cb_Adresse").html(data.Adresse1 + ' ' + data.Adresse2);
                            $("#cb_CP").html(data.CP);
                            $("#cb_Ville").html(data.Ville);
                            $("#cb_Pays").html(data.Pays);
                        }
                });
            });
        </script>

<body>
    <!-- C'est dans cette zone que le Widget sera chargé -->
    <div id="Zone_Widget"></div>
    <div style="padding:8px; overflow:auto;">
        <div style="background:#edffb2; border:solid 1px #a5f913; padding:5px; font-family:verdana; font-size:10px;">
            <em>Cette zone n'est pas située dans le Widget mais bien dans la page appelante.</em><br/><br/>
            <div style="display:inline-block; vertical-align:top;">
                <!-- La balise ayant pour Id "TargetDisplay_Widget" a été paramétrée pour reçevoir l'ID du Point Relais sélectionné -->
                Point Relais Selectionné : <input type="text" id="TargetDisplay_Widget"/><br/>
                <!-- La balise ayant pour Id "Target_Widget" a été paramétrée pour reçevoir l'ID du Point Relais sélectionné -->
                Hidden : <input type="text" id="Target_Widget" /><br/>
                <!-- La balise ayant pour Id "TargetDisplayInfoPR_Widget" a été paramétrée pour reçevoir l'adresse du Point Relais sélectionné -->
                InfosPR : <span id="TargetDisplayInfoPR_Widget" />
            </div>
            <div style="display:inline-block;">
                <!-- Les balises suivantes sont utilisées dans la fonction de CallBack pour reçevoir des données à afficher -->
                <span style="font-weight:bold;text-decoration:underline;">Callback zone</span><br/>
                data.ID = <span id="cb_ID"></span><br/>
                data.Nom = <span id="cb_Nom"></span><br/>
                data.Adresse = <span id="cb_Adresse"></span><br/>
                data.CP = <span id="cb_CP"></span><br/>
                data.Ville = <span id="cb_Ville"></span><br/>
                data.Pays = <span id="cb_Pays"></span><br/>
            </div>
        </div>
    </div>
</body>
</html>

piTgRL8F










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
