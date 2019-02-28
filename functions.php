<?php

function dbConnect() {
		try
	{
		$db = new PDO('mysql:host=localhost;dbname=pays;charset=utf8', 'root', '');
	}
	catch(Exception $e)
	{
		 die('Erreur : '.$e->getMessage());
	}
	return $db;
}

function continentData($db) {
	$reqContinent = $db->query("SELECT * FROM t_continents");
	return $reqContinent;
}

function regionData($db) {
	$reqRegion = $db->prepare("SELECT * FROM t_regions WHERE continent_id LIKE ?");
	$reqRegion->execute(array($_GET['continent']));
	return $reqRegion;
}

function paysRegionData($db) {
	$reqPays = $db->prepare("SELECT * FROM t_pays WHERE region_id LIKE ? ORDER BY libelle_pays");
	$reqPays->execute(array($_GET['region']));
	return $reqPays;
}

function paysContinentData($db) {
	$reqPaysContinent = $db->prepare("SELECT t_regions.libelle_region AS libelle, 
	SUM(t_pays.population_pays) AS population,
    ROUND(AVG(t_pays.taux_natalite_pays), 1) AS natalite,
    ROUND(AVG(t_pays.taux_mortalite_pays), 1) AS mortalite,
    ROUND(AVG(t_pays.esperance_vie_pays), 1) AS vie,
    ROUND(AVG(t_pays.taux_mortalite_infantile_pays), 1) AS mort_inf,
    ROUND(AVG(t_pays.nombre_enfants_par_femme_pays), 1) AS nb_enfant,
    ROUND(AVG(t_pays.taux_croissance_pays), 1) AS croissance,
    SUM(t_pays.population_plus_65_pays) AS senior
	FROM t_regions
	LEFT JOIN t_pays
	ON (t_regions.id_region = t_pays.region_id)
	WHERE t_regions.continent_id = ?
	GROUP BY t_regions.libelle_region");
	$reqPaysContinent->execute(array($_GET['continent']));
	return $reqPaysContinent;
}

function paysContinentRegionData($db) {
	$reqPays = $db->prepare("SELECT * FROM t_pays WHERE continent_id LIKE ? ORDER BY libelle_pays");
	$reqPays->execute(array($_GET['continent']));
	return $reqPays;
}

function paysMondeData($db) {
	$reqPaysMonde = $db->query("SELECT UPPER(t_continents.libelle_continent) AS libelle,
	SUM(t_pays.population_pays) AS population,
	ROUND(AVG(t_pays.taux_natalite_pays), 1) AS natalite,
	ROUND(AVG(t_pays.taux_mortalite_pays), 1) AS mortalite,
	ROUND(AVG(t_pays.esperance_vie_pays), 1) AS vie,
	ROUND(AVG(t_pays.taux_mortalite_infantile_pays), 1) AS mort_inf,
	ROUND(AVG(t_pays.nombre_enfants_par_femme_pays), 1) AS nb_enfant,
	ROUND(AVG(t_pays.taux_croissance_pays), 1) AS croissance,
	SUM(t_pays.population_plus_65_pays) AS senior
	FROM t_continents
	LEFT JOIN t_pays
	ON (t_continents.id_continent = t_pays.continent_id)
	GROUP BY t_continents.libelle_continent");
	return $reqPaysMonde;
}

function viewLabelRegion($db) {
	$reqLabel = $db->prepare("SELECT UPPER(libelle_region) AS libelle FROM t_regions WHERE id_region LIKE ?");
	$reqLabel->execute(array($_GET['region']));
	return $reqLabel;
}

function viewLabelContinent($db) {
	$reqLabel = $db->prepare("SELECT UPPER(libelle_continent) AS libelle FROM t_continents WHERE id_continent LIKE ?");
	$reqLabel->execute(array($_GET['continent']));
	return $reqLabel;
}

function totalContinent($db) {
	$reqTotal = $db->query("SELECT SUM(t_pays.population_pays) AS popTotal,
	ROUND(AVG(t_pays.taux_natalite_pays), 1) AS natTotal,
    ROUND(AVG(t_pays.taux_mortalite_pays), 1) AS mortTotal,
    ROUND(AVG(t_pays.esperance_vie_pays), 1) AS espTotal,
    ROUND(AVG(t_pays.taux_mortalite_infantile_pays), 1) AS mortInfTotal,
    ROUND(AVG(t_pays.nombre_enfants_par_femme_pays), 1) AS enfTotal,
    ROUND(AVG(t_pays.taux_croissance_pays), 1) AS croiTotal,
    SUM(t_pays.population_plus_65_pays) AS seniorTotal
	FROM t_pays");
	return $reqTotal;
}

function totalRegion($db) {
	$reqTotal = $db->prepare("SELECT UPPER(t_continents.libelle_continent) AS libelle,
	SUM(t_pays.population_pays) AS popTotal,
	ROUND(AVG(t_pays.taux_natalite_pays), 1) AS natTotal,
    ROUND(AVG(t_pays.taux_mortalite_pays), 1) AS mortTotal,
    ROUND(AVG(t_pays.esperance_vie_pays), 1) AS espTotal,
    ROUND(AVG(t_pays.taux_mortalite_infantile_pays), 1) AS mortInfTotal,
    ROUND(AVG(t_pays.nombre_enfants_par_femme_pays), 1) AS enfTotal,
    ROUND(AVG(t_pays.taux_croissance_pays), 1) AS croiTotal,
    SUM(t_pays.population_plus_65_pays) AS seniorTotal
	FROM t_pays
	LEFT JOIN t_continents
	ON (t_pays.continent_id = t_continents.id_continent)
	WHERE t_pays.continent_id LIKE ?");
	$reqTotal->execute(array($_GET['continent']));
	return $reqTotal;
}

function totalPays($db) {
	$reqTotal = $db->prepare("SELECT t_regions.libelle_region AS libelle,
	SUM(t_pays.population_pays) AS popTotal,
	ROUND(AVG(t_pays.taux_natalite_pays), 1) AS natTotal,
    ROUND(AVG(t_pays.taux_mortalite_pays), 1) AS mortTotal,
    ROUND(AVG(t_pays.esperance_vie_pays), 1) AS espTotal,
    ROUND(AVG(t_pays.taux_mortalite_infantile_pays), 1) AS mortInfTotal,
    ROUND(AVG(t_pays.nombre_enfants_par_femme_pays), 1) AS enfTotal,
    ROUND(AVG(t_pays.taux_croissance_pays), 1) AS croiTotal,
    SUM(t_pays.population_plus_65_pays) AS seniorTotal
	FROM t_pays
	LEFT JOIN t_regions
	ON (t_pays.region_id = t_regions.id_region)
	WHERE t_pays.region_id LIKE ?");
	$reqTotal->execute(array($_GET['region']));
	return $reqTotal;
}

function totalPaysContinent($db) {
	$reqTotal = $db->prepare("SELECT UPPER(t_continents.libelle_continent) AS libelle,
	SUM(t_pays.population_pays) AS popTotal,
	ROUND(AVG(t_pays.taux_natalite_pays), 1) AS natTotal,
    ROUND(AVG(t_pays.taux_mortalite_pays), 1) AS mortTotal,
    ROUND(AVG(t_pays.esperance_vie_pays), 1) AS espTotal,
    ROUND(AVG(t_pays.taux_mortalite_infantile_pays), 1) AS mortInfTotal,
    ROUND(AVG(t_pays.nombre_enfants_par_femme_pays), 1) AS enfTotal,
    ROUND(AVG(t_pays.taux_croissance_pays), 1) AS croiTotal,
    SUM(t_pays.population_plus_65_pays) AS seniorTotal
	FROM t_pays
	LEFT JOIN t_continents
	ON (t_pays.continent_id = t_continents.id_continent)
	WHERE t_pays.continent_id LIKE ?");
	$reqTotal->execute(array($_GET['continent']));
	return $reqTotal;
}

function viewHeader() {
	echo "
		<table class='table table-bordered'>
			<tr>
				<th scope='col'>Pays</th>
				<th scope='col'>Population totale<br><span class='info'>(en milliers)</span></th>
				<th scope='col'>Taux de natalité</th>
				<th scope='col'>Taux de mortalité</th>
				<th scope='col'>Espérance de vie</th>
				<th scope='col'>Taux de mortalité infantile</th>
				<th scope='col'>Nombre d'enfant(s) par femme</th>
				<th scope='col'>Taux de croissance</th>
				<th scope='col'>Population de 65 ans et plus<br><span class='info'>(en milliers)</span></th>
			</tr>
	";
}
