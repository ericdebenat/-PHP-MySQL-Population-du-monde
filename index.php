<?php require 'functions.php'; ?>

<!DOCTYPE HTML>
<html>
<head>
	<meta charset='utf-8'>
	<title>Population du monde</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="style.css">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script type="text/javascript">
		function changeContinent() {
			parent.location.href='index.php?continent='+document.getElementById('continent').options[document.getElementById('continent').selectedIndex].value;
		}
	</script>
	<script type="text/javascript">
		function changeRegion() {
			parent.location.href='index.php?continent='+document.getElementById('c').value+'&region='+document.getElementById('region').options[document.getElementById('region').selectedIndex].value;
		}
	</script>
</head>
<body>
<div class='container'>
	<h1>Population du monde</h1>
</div>
<?php
$db = dbConnect();

$reqContinent = continentData($db);
?>

<!-- Affichage de la liste Continents -->

<div class='container'>
	<div class='row menu'>
		<div class='form-group formulaire'>
			<label for='continent'>Par continent</label>
			<select class='form-control' id ='continent' name='continent' onchange="changeContinent();">
			<option>Monde</option>
			<?php
			while ($dataContinent = $reqContinent->fetch(PDO::FETCH_ASSOC))
			{ ?>
				<option value='<?= $dataContinent['id_continent'] ?>' <?php
					if (isset($_GET['continent']) && ($_GET['continent']==$dataContinent['id_continent'])){
						echo "selected";
					}
				?>>
				<?=$dataContinent['libelle_continent']?>
				</option>
			<?php }
			$reqContinent->closeCursor();
			?>
			</select>
		</div>

		<?php 
		if (isset($_GET['continent'])) {
			$reqRegion = regionData($db);
			if ($reqRegion->rowCount() > 0) {
		?>

<!-- Affichage de la liste Régions -->

				<input id='c' type= 'hidden' name='continent' value='<?= $_GET['continent'] ?>'>
				<div class='form-group formulaire'>
					<label for='region'>Pays par région</label>
					<select class='form-control' id='region' name='region' onchange="changeRegion();">
					<option>--</option>
					<?php
					while ($dataRegion = $reqRegion->fetch(PDO::FETCH_ASSOC))
					{ ?>
						<option value='<?= $dataRegion['id_region'] ?>' <?php
							if (isset($_GET['region']) && ($_GET['region']==$dataRegion['id_region'])) {
								echo "selected";
							}
						?>><?=$dataRegion['libelle_region'];?></option>
					<?php } 
					$reqRegion->closeCursor();
					?>
					</select>
				</div>
			<?php }
		} ?>
	</div>
</div>

<!-- Affichage du titre du tableau -->

<?php
if (isset($_GET['region']) && $_GET['region'] != "--") { ?>
	<div class='container'>
		<h3><?php $labelRegion = viewLabelRegion($db)->fetch(PDO::FETCH_ASSOC);
		echo $labelRegion['libelle']; ?><span class='estim'> - Estimations 2019</span></h3>
	</div>
<?php }
elseif (isset($_GET['continent']) && ($_GET['continent']) != 'Monde') { ?>
	<div class='container'>
		<h3><?php $labelContinent = viewLabelContinent($db)->fetch(PDO::FETCH_ASSOC);
		echo $labelContinent['libelle']; ?><span class='estim'> - Estimations 2019</span></h3>
	</div>
<?php }
else { ?>
	<div class='container'><h3>MONDE<span class='estim'> - Estimations 2019</span></h3></div>
<?php }
?>

<!-- Affichage du tableau de données -->

<div class='container data'>
<?php 
	
	if (isset($_GET['region']) && $_GET['region'] != "--") {
		$reqPaysRegion = paysRegionData($db);
		viewHeader();
			while ($dataPaysRegion = $reqPaysRegion->fetch(PDO::FETCH_ASSOC)) { ?>
				<tr>
					<td><?= $dataPaysRegion['libelle_pays'] ?></td>
					<td class='right'><?= $dataPaysRegion['population_pays'] ?></td>
					<td class='right'><?= $dataPaysRegion['taux_natalite_pays'] ?></td>
					<td class='right'><?= $dataPaysRegion['taux_mortalite_pays'] ?></td>
					<td class='right'><?= $dataPaysRegion['esperance_vie_pays'] ?></td>
					<td class='right'><?= $dataPaysRegion['taux_mortalite_infantile_pays'] ?></td>
					<td class='right'><?= $dataPaysRegion['nombre_enfants_par_femme_pays'] ?></td>
					<td class='right'><?= $dataPaysRegion['taux_croissance_pays'] ?></td>
					<td class='right'><?= $dataPaysRegion['population_plus_65_pays'] ?></td>
				</tr>

			<?php } $reqPaysRegion->closeCursor();
			$reqTotalRegion = totalPays($db);
			$dataTotalRegion = $reqTotalRegion->fetch(PDO::FETCH_ASSOC); ?>
			<tr>
				<td class='total'><?= $dataTotalRegion['libelle'] ?></td>
				<td class='right total'><?= $dataTotalRegion['popTotal'] ?></td>
				<td class='right total'><?= $dataTotalRegion['natTotal'] ?></td>
				<td class='right total'><?= $dataTotalRegion['mortTotal'] ?></td>
				<td class='right total'><?= $dataTotalRegion['espTotal'] ?></td>
				<td class='right total'><?= $dataTotalRegion['mortInfTotal'] ?></td>
				<td class='right total'><?= $dataTotalRegion['enfTotal'] ?></td>
				<td class='right total'><?= $dataTotalRegion['croiTotal'] ?></td>
				<td class='right total'><?= $dataTotalRegion['seniorTotal'] ?></td>
			</tr>
			
		</table>
	<?php $reqTotalRegion->closeCursor();
	}
	elseif (isset($_GET['continent']) && ($_GET['continent'] == 3)) {
		$reqPaysContinent = paysContinentRegionData($db);
		viewHeader();
			while ($dataPaysContinent = $reqPaysContinent->fetch(PDO::FETCH_ASSOC)) { ?>
			<tr>
				<td><?= $dataPaysContinent['libelle_pays'] ?></td>
				<td class='right'><?= $dataPaysContinent['population_pays'] ?></td>
				<td class='right'><?= $dataPaysContinent['taux_natalite_pays'] ?></td>
				<td class='right'><?= $dataPaysContinent['taux_mortalite_pays'] ?></td>
				<td class='right'><?= $dataPaysContinent['esperance_vie_pays'] ?></td>
				<td class='right'><?= $dataPaysContinent['taux_mortalite_infantile_pays'] ?></td>
				<td class='right'><?= $dataPaysContinent['nombre_enfants_par_femme_pays'] ?></td>
				<td class='right'><?= $dataPaysContinent['taux_croissance_pays'] ?></td>
				<td class='right'><?= $dataPaysContinent['population_plus_65_pays'] ?></td>
			</tr>

			<?php } $reqPaysContinent->closeCursor();
			$reqTotalPaysContinent = totalPaysContinent($db);
			$dataTotalPaysContinent = $reqTotalPaysContinent->fetch(PDO::FETCH_ASSOC); ?>
			<tr>
				<td class='total'><?= $dataTotalPaysContinent['libelle'] ?></td>
				<td class='right total'><?= $dataTotalPaysContinent['popTotal'] ?></td>
				<td class='right total'><?= $dataTotalPaysContinent['natTotal'] ?></td>
				<td class='right total'><?= $dataTotalPaysContinent['mortTotal'] ?></td>
				<td class='right total'><?= $dataTotalPaysContinent['espTotal'] ?></td>
				<td class='right total'><?= $dataTotalPaysContinent['mortInfTotal'] ?></td>
				<td class='right total'><?= $dataTotalPaysContinent['enfTotal'] ?></td>
				<td class='right total'><?= $dataTotalPaysContinent['croiTotal'] ?></td>
				<td class='right total'><?= $dataTotalPaysContinent['seniorTotal'] ?></td>
			</tr>

		</table>
	<?php $reqTotalPaysContinent->closeCursor();
	} 	
	elseif (isset($_GET['continent']) && ($_GET['continent']) != 'Monde') {
		$reqPaysContinent = paysContinentData($db);
		viewHeader();
			while ($dataPaysContinent = $reqPaysContinent->fetch(PDO::FETCH_ASSOC)) { ?>
				<tr>
					<td><?= $dataPaysContinent['libelle'] ?></td>
					<td class='right'><?= $dataPaysContinent['population'] ?></td>
					<td class='right'><?= $dataPaysContinent['natalite'] ?></td>
					<td class='right'><?= $dataPaysContinent['mortalite'] ?></td>
					<td class='right'><?= $dataPaysContinent['vie'] ?></td>
					<td class='right'><?= $dataPaysContinent['mort_inf'] ?></td>
					<td class='right'><?= $dataPaysContinent['nb_enfant'] ?></td>
					<td class='right'><?= $dataPaysContinent['croissance'] ?></td>
					<td class='right'><?= $dataPaysContinent['senior'] ?></td>
				</tr>

			<?php } $reqPaysContinent->closeCursor();
			$reqTotalContinent = totalRegion($db);
			$dataTotalContinent = $reqTotalContinent->fetch(PDO::FETCH_ASSOC); ?>
			<tr>
				<td class='total'><?= $dataTotalContinent['libelle'] ?></td>
				<td class='right total'><?= $dataTotalContinent['popTotal'] ?></td>
				<td class='right total'><?= $dataTotalContinent['natTotal'] ?></td>
				<td class='right total'><?= $dataTotalContinent['mortTotal'] ?></td>
				<td class='right total'><?= $dataTotalContinent['espTotal'] ?></td>
				<td class='right total'><?= $dataTotalContinent['mortInfTotal'] ?></td>
				<td class='right total'><?= $dataTotalContinent['enfTotal'] ?></td>
				<td class='right total'><?= $dataTotalContinent['croiTotal'] ?></td>
				<td class='right total'><?= $dataTotalContinent['seniorTotal'] ?></td>
			</tr>

		</table>
	<?php $reqTotalContinent->closeCursor();
	} 
	else {
		$reqPaysMonde = paysMondeData($db);
		viewHeader();
		
			while ($dataPaysMonde = $reqPaysMonde->fetch(PDO::FETCH_ASSOC)) { ?>
				<tr>
					<td><?= $dataPaysMonde['libelle'] ?></td>
					<td class='right'><?= $dataPaysMonde['population'] ?></td>
					<td class='right'><?= $dataPaysMonde['natalite'] ?></td>
					<td class='right'><?= $dataPaysMonde['mortalite'] ?></td>
					<td class='right'><?= $dataPaysMonde['vie'] ?></td>
					<td class='right'><?= $dataPaysMonde['mort_inf'] ?></td>
					<td class='right'><?= $dataPaysMonde['nb_enfant'] ?></td>
					<td class='right'><?= $dataPaysMonde['croissance'] ?></td>
					<td class='right'><?= $dataPaysMonde['senior'] ?></td>
				</tr>
			
			<?php } $reqPaysMonde->closeCursor();
			$reqTotalMonde = totalContinent($db);
			$dataTotalMonde = $reqTotalMonde->fetch(PDO::FETCH_ASSOC); ?>
			<tr>
				<td class='total'>MONDE</td>
				<td class='right total'><?= $dataTotalMonde['popTotal'] ?></td>
				<td class='right total'><?= $dataTotalMonde['natTotal'] ?></td>
				<td class='right total'><?= $dataTotalMonde['mortTotal'] ?></td>
				<td class='right total'><?= $dataTotalMonde['espTotal'] ?></td>
				<td class='right total'><?= $dataTotalMonde['mortInfTotal'] ?></td>
				<td class='right total'><?= $dataTotalMonde['enfTotal'] ?></td>
				<td class='right total'><?= $dataTotalMonde['croiTotal'] ?></td>
				<td class='right total'><?= $dataTotalMonde['seniorTotal'] ?></td>
			</tr>
		</table>
	<?php $reqTotalMonde->closeCursor();
	}
	?>
</div>
</body>
</html>