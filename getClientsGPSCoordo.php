<?php

// cron programmé pour tourner chaque vendredi à 20h
// 00 20 * * 5 . $HOME/.bashrc; /usr/bin/php -c /etc/php.ini -f /var/www/html/mapping_tournee_comm/cron/getClientsGPSCoordo.php

/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/

set_time_limit(0);

$_PATH = '/../';
$_COMMON = '../common/';
$_PORTAIL = '../portail/';

require_once(__DIR__.$_PATH.$_COMMON.'libs/log4php/2.3.0/Logger.php');
require_once(__DIR__.$_PATH.$_COMMON.'class/databaseOracle.class.php');
require_once(__DIR__.$_PATH.$_COMMON.'class/DBO_pgsql.class.php');

Logger::configure(__DIR__.'/../conf/configLog4cron.xml');
$log = Logger::getLogger('file');

$conf_oracle = '../conf/databaseDB.ini';
$conf_pgsql  = '../conf/databasePg.ini';

$pgsql = new postgresql($conf_pgsql);
$obj_oracle = new Database();

$log->info('Tentative d\'ouverture de la connexion Oracle à partir du fichier de configuration '.$conf_oracle);
$obj_oracle->db_connect($conf_oracle);

$log->info('Début du traitement');

// récupérer tous les clients d'une société 
$toInsertClients = array();

$isEmptyTableSQL = "SELECT * FROM clients_coordo_GPS.coordo_GPS";
$isEmptyTable = $pgsql->Select($isEmptyTableSQL, array());

// si table PG vide, je récupère toutes les données
/*if(count($isEmptyTable) == 0) {
	$getSigtieSQL = "SELECT codsoc, sigtie from soc1.tie where codsoc in (110, 130) and typtie = 'CLI' and codett = 'ACT' order by codsoc, sigtie asc";
	$getSigtie = $obj_oracle->db_select($getSigtieSQL, OCI_ASSOC + OCI_RETURN_NULLS, $conf_oracle);
}
else { // sinon, je ne récupère que les données des 7 derniers jours
*/
	// ajout clause de temporalité pour selectionner les clients dont la création ou modif remonte à 1 semaine
	$getSigtieSQL = "SELECT codsoc, sigtie from soc1.tie where codsoc in (110, 130) and typtie = 'CLI' and codett = 'ACT' and greatest (datmod, datinc, datcre) >= to_char(SYSDATE - 7, 'YYYYMMDD') order by codsoc, sigtie asc";
	$getSigtie = $obj_oracle->db_select($getSigtieSQL, OCI_ASSOC + OCI_RETURN_NULLS, $conf_oracle);
//}

if(count($getSigtie) > 0) {
	foreach($getSigtie as $cli) {
		$ligneAdr = getAdresse($cli['CODSOC'], $cli['SIGTIE'], $obj_oracle, $conf_oracle);
		if($ligneAdr) {
			// var_dump('OK -- adresse complète pour client '. $cli['SIGTIE'].' ('.$cli['CODSOC'].')<br/>');
			$coordGPS = getGPS($ligneAdr);

			if($coordGPS) {
				// var_dump(' ==> '. $coordGPS.'<br/>');
				$explode = explode(',', $coordGPS);
				$latitude = $explode[0];
				$longitude = $explode[1];

				$insert = insertGPS($cli['CODSOC'], $cli['SIGTIE'], $latitude, $longitude, $pgsql);
				if($insert) {
					$log->info('Insert OK - client '.$cli['SIGTIE'] .' ('.$cli['CODSOC'].')');
				} else {
					$log->error('Insert KO - client '.$cli['SIGTIE'].' ('.$cli['CODSOC'].')');
				}
			} else {
				// var_dump('KO -- pas de coordo GPS pour client '. $cli['SIGTIE'] .' ('.$cli['CODSOC'].')<br/>');
				$log->info('pas de coordo GPS pour client '. $cli['SIGTIE'].' ('.$cli['CODSOC'].')');
			}
		} else {
			// var_dump('KO -- adresse INcomplète pour client '. $cli['SIGTIE'].' ('.$cli['CODSOC'].')<br/>');
			$log->info('adresse incomplète pour client '. $cli['SIGTIE'].' ('.$cli['CODSOC'].')');
		}
	}
	$log->info('Fin du traitement');
} else {
	$log->error('Erreur requête clients');
}


function getAdresse($codsoc, $sigtie, $obj_oracle, $conf_oracle) {
	$getAdressesSQL = "SELECT codsoc, sigadr, adress, codpos, cenpos
					FROM soc1.adr
					WHERE codsoc = :codsoc AND sigadr = :sigtie 
					AND typadr = 'COM' AND numadr = 1 AND codpay = 'F'
					AND trim(adress) IS NOT null AND trim(codpos) IS NOT null AND trim(cenpos) IS NOT null";
	$getAdresse = $obj_oracle->db_select_with_params($getAdressesSQL, array(':codsoc' => $codsoc, ':sigtie' => $sigtie), OCI_ASSOC + OCI_RETURN_NULLS, $conf_oracle);

	if($getAdresse) {
		$adr = $getAdresse[0]['ADRESS'].', '.$getAdresse[0]['CODPOS'].' '.$getAdresse[0]['CENPOS'];
		return $adr;
	} else {
		return false;
	}
}


function getGPS($adr) {
	//  https://developers.arcgis.com/rest/geocode/api-reference/geocoding-find-address-candidates.htm
	$url = "https://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/findAddressCandidates?outSr=4326&forStorage=false&outFields=*&maxLocations=20&singleLine=".urlencode($adr)."&f=json";
	$json = file_get_contents($url);

	$APIcall = json_decode($json);	
	if($APIcall) {
		foreach($APIcall->candidates as $api) {
			$gps = $api->location->y . ','.$api->location->x; // latitude, longitude
			return $gps;
		}
	} else {
		return false;
	}
}


// 26/10/2022 AFA - merge / upsert fonctionne MAIS en local avec la lib PostgreSQL 9.6 (que j'ai en local) et pas la 9.2 qui est en recette
// donc, en attendant de mettre l'envrionnement au niveau, je ne fais qu'un INSERT basique
function insertGPS($codsoc, $sigtie, $latitude, $longitude, $pgsql) {
	$qry = false;
	$params = array(
		':codsoc' => $codsoc,
		':sigtie' => $sigtie,
		':latitude' => $latitude,
		':longitude' => $longitude
	);

	// on vérifie si codsoc et sigtie sont déjà dans la table
	$ifExistSQL = "SELECT sigtie, codsoc FROM clients_coordo_GPS.coordo_GPS WHERE sigtie = :sigtie AND codsoc = :codsoc";
	$ifExist = $pgsql->Select($ifExistSQL, array(':sigtie' => $sigtie, ':codsoc' => $codsoc));

	// si vide, on insert
	if(count($ifExist) == 0) {
		$addGPSCoord = "INSERT INTO clients_coordo_GPS.coordo_GPS (sigtie, codsoc, latitude, longitude, date_add) VALUES (:sigtie, :codsoc, :latitude, :longitude, NOW())";
		$qry = $pgsql->Insert($addGPSCoord, $params);
	} else {	// si pas vide, on update
		$addGPSCoord = "UPDATE clients_coordo_GPS.coordo_GPS SET latitude = :latitude, longitude = :longitude, date_add = NOW() WHERE coordo_GPS.sigtie = :sigtie AND coordo_GPS.codsoc = :codsoc";
		$qry = $pgsql->Update($addGPSCoord, $params);
	}

	/*$addGPSCoord = "INSERT INTO clients_coordo_GPS.coordo_GPS (sigtie, codsoc, latitude, longitude, date_add) VALUES (:sigtie, :codsoc, :latitude, :longitude, NOW())
					ON CONFLICT (sigtie, codsoc) 
					DO UPDATE SET latitude = :latitude, longitude = :longitude, date_add = NOW() WHERE coordo_GPS.sigtie = :sigtie AND coordo_GPS.codsoc = :codsoc";
	$update = $pgsql->Insert($addGPSCoord, $params);*/
	return $qry;
}
