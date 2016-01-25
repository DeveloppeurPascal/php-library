<?php
$folder = '';
require_once('utils/header.php');
?>
<div>
    <h1>Quick start / Librairie PHP pour l'API EnvoiMoinsCher</h1>
    <p>Ce document a pour but de vous faciliter le travail pour connecter votre interface
    avec l'API <a href="http://www.envoimoinscher.com" target="_blank">EnvoiMoinsCher.com</a></p>
    <p>On explorera successivement les éléments qui vous permettront de développer un module e-shipping pour
    votre e-boutique. On passera donc en revue ces éléments dans l'ordre :</p> 
    <ul>
        <li><a href="#categories">les catégories de contenu</a></li>
        <li><a href="#pays">la gestion des pays</a></li>
        <li><a href="#devis">le devis</a></li>
        <li><a href="#commande">la passation de commande</a></li>
    </ul> 
    <p>Pour plus d'informations sur les paramètres, la description des classes, les modifications récéntes, veuillez-vous référer à la <a href="http://ecommerce.envoimoinscher.com/api/documentation/" target="_blank">documentation de la librairie</a>.</p>
    
    <br/><p><b>Prérequis et informations générales à propos de l'utilisation de l'API EnvoiMoinsCher.</b></p>
    <p>Avant de pouvoir utiliser l'API EnvoiMoinsCher, il vous faudra créer un compte utilisateur sur le site <a href="http://www.envoimoinscher.com/inscription.html" target="_blank">www.envoimoinscher.com</a> en cochant la case "Je souhaite installer le module EnvoiMoinsCher directement sur mon site E-commerce".</p>
    <p>Une fois reçu le mail contenant votre clé API, vous pouvez commencer à effectuer des tests. Vous pouvez passer de notre serveur de test à notre serveur de prod en utilisant la fonction setEnv :</p>
    <pre>
    $lib = new EnvContentCategory(
        array("user" => "login", "pass" => "mot_de_passe", "key" => "cle_api")
    );
    
    /* if we want to set 'test' environment  */
    $lib->setEnv('test');
    
    /* if we want to set 'prod' environment  */
    $lib->setEnv('prod');
    </pre>
    
    <br/><p><b>Le contenu de la librairie</b></p>
    <p>Le package contient 5 répertoires :</p>
    <ul>
        <li>ca - contient le certificat nécessaire à la communication avec l'API</li>
        <li>css - contient des règles css utilisées pour les exemples</li>
        <li>documentation - une documentation basique de la librairie (documentation plus détaillée <a href="http://ecommerce.envoimoinscher.com/api/documentation/" target="_blank">ici</a>)</li>
        <li>env - les classes qui permettent l'interaction avec l'API</li>
        <li>samples - les fichiers avec les exemples d'utilisation de la librairie</li>
        <li>test - un fichier qui teste si votre environnement de développement possède toutes les extensions utilisées par la librairie</li>
        <li>utils - le dossier avec des fonctions utilisées par les exemples placés dans le répertoire samples/</li>
    </ul>
    
    <br/><p id="categories"><b>1. Comment obtenir la liste des catégories de contenu ?</b></p>
    <p>Grâce à l'API vous pouvez obtenir la liste des catégories de contenu qui vont vous servir pour le développement de votre module.
    On désigne par « catégories de contenu » les différents types d'objets qui peuvent être contenus dans un envoi (nature des envois). </p>
    <pre>
    $contentCl = new EnvContentCategory(
        array("user" => "login", "pass" => "mot_de_passe", "key" => "cle_api")
    );
    $contentCl->getContents();
    </pre>
    <p>L'API a besoin du code de la catégorie de contenu, représenté par la variable $contents.</p>
    <p>Voir aussi l'exemple : <a href="samples/get_categories.php">récupération des catégories de contenu</a></p>
    
    <br/><p id="pays"><b>2. Comment obtenir la liste des pays ?</b></p>
    <p>Les commandes chez EnvoiMoinsCher.com sont réalisées avec les codes ISO des pays. Pour l'instant le système gère uniquement
    les commandes passées depuis la France vers l'étranger, pas les trajets import depuis l'étranger vers la France.
    La récupération des pays de destination se présente ainsi : </p>
    <pre>
    $countryCl = new EnvCountry(
        array("user" => "login", "pass" => "mot_de_passe", "key" => "cle_api")
    );
    $countryCl->getCountries();
    </pre>
    <p>La passation de commande se déroule avec le code ISO, représenté dans le tableau $countries par la clé "code".</p>
    <p>Voir aussi l'exemple : <a href="samples/get_country.php">récupération des pays</a>.</p>
    
    <br/><p id="devis"><b>3. Comment obtenir un devis ?</b></p>
    <p>Voici les éléments dont on a besoin pour obtenir un devis :</p>
    <ul>
        <li>le type d'envoi (encombrant, colis, palette, pli)</li>
        <li>l'identifiant de la catégorie de contenu</li>
        <li>le pays, la ville et le type de l'expéditeur</li>
        <li>le pays, la ville et le type du destinataire</li>
        <li>la date d'enlèvement (sauf dimanche et jours fériés)</li>
        <li>la valeur de l'objet (si un devis à l'international)</li>
    </ul>
    <pre>
    $from = array(
        "pays" => "FR",
        "code_postal" => "44000",
        "adresse" => "1, rue Racine",
        "type" => "particulier"
    );
    $to = array(
        "pays" => "FR",
        "code_postal" => "33000",
        "adresse" => "1, Rue des Faures",
        "type" => "particulier"
    );
    $quotInfo = array(
        "collecte" => "2011-05-11",
        "delai" => "aucun",
        "content_code" => 10120
    );
    $lib = new Env_Quotation(
        array("user" => "login", "pass" => "mot_de_passe", "key" => "cle_api")
    );
    $lib->setPerson("expediteur", $from);
    $lib->setPerson("destinataire", $to);
    $lib->setType(
        "colis",
        array(1 => array("poids" => 2, "longueur" => 30, "largeur" => 44, "hauteur" => 44))
    );
    $lib->getQuotation($quotInfo);
    $lib->getOffers(false);
    </pre>
    <p>Avant d'appeler getOffers() vous pouvez vous assurer que la requête s'est correctement exécutée. Pour cela il faut que $curl_error et $resp_error
    aient comme valeur false.</p>
    <p>Voir aussi les exemples : le devis <a href="samples/get_cotation.php">Paris - Bordeaux</a>, le devis <a href="samples/get_cotation_australia.php">Paris - Sydney</a>.</p>
    
    <br/><p id="commande"><b>4. Comment passer une commande ?</b></p>
    <p>La passation d'une commande se déroule de la même manière que l'obtention d'une cotation.
    La seule différence réside dans les informations que vous devez fournir en plus.
    Pour l'expéditeur et le destinataire vous devez indiquer leurs adresses exactes, numéros de téléphones, données personnelles. Concernant les données 
    de l'envoi, en fonction de l'opérateur et du service choisis, vous pouvez être amenés à fournir des informations comme les disponibilités d'enlèvement, les points de proximité pour le dépôt et le retrait.</p>
    <p>Toutes les expéditions à l'étranger nécessitent la présence du paramètre <em>objet.</em>valeur (où <em>objet</em> signifie le type d'envoi : colis, encombrant, palette, pli)</p>
    <pre>
    $from = array(
        "pays" => "FR",
        "code_postal" => "75002",
        "type" => "particulier",
        "ville" => "Paris",
        "adresse" => "41, rue Saint Augustin| 3e étage",
        "civilite" => "M",
        "prenom" => "Prénom_expéditeur",
        "nom" => "Nom_expéditeur",
        "email" => "dev@boxtale.com",
        "tel" => "0601010101",
        "infos" => "Une information supplémentaire"
    );
    $to = array(
        "pays" => "FR",
        "code_postal" => "13002",
        "type" => "particulier",
        "ville" => "Marseille",
        "adresse" => "1, rue Saint-Thome",
        "civilite" => "Mme",
        "prenom" => "Mme_expéditeur",
        "nom" => "Mme_expéditeur nom",
        "email" => "dev@boxtale.com",
        "tel" => "0601010101",
        "infos" => ""
    );
    $quotInfo = array(
        "collecte" => "2011-05-11",
        "delai" => "aucun",  
        "content_code" => 10120,
        'operator' => 'CHRP',
        'service' => 'Chrono18',
    );
    $lib = new Env_Quotation(
        array("user" => "login", "pass" => "mot_de_passe", "key" => "cle_api")
    );
    $lib->setPerson("expediteur", $from);
    $lib->setPerson("destinataire", $to);
    $lib->setType(
        "colis",
        array(1 => array("poids" => 2, "longueur" => 30, "largeur" => 44, "hauteur" => 44))
    );
    $orderPassed = $lib->makeOrder($quotInfo, true);
    </pre>
    <p>Pour plus d'informations sur les paramètres, la description des classes, veuillez-vous référer à la <a href="http://ecommerce.envoimoinscher.com/api/documentation/" target="_blank">documentation de la librairie</a><!-- et <a href="#" target="_blank">de l'API</a>-->.</p>
    <p>Si vous rencontrez le moindre problème, n'hésitez pas à nous contacter : <a href="mailto:informationAPI@envoimoinscher.com">informationAPI@envoimoinscher.com</a></p>
</div>
<?php 
require_once('utils/footer.php');
?> 
