<ul>
    <li><a href="/admin/packages/registrationStudent.html">Inscrire un élève à un package</a></li>
    <li><a href="/admin/packages/registrationBatch.html">Inscrire des promos entières à un package</a></li>
</ul>

<div class="clicker">
<p class="informationsTitle"><img class="imgPlusMinus" src='../../web/images/plus.png'>  Format CSV pour les packages</p>
    <div class="informations">
        <p>"Capacité","Nom fr","Nom en","Description fr","Description en"</p>
    </div>
</div>

<script src="../../web/js/plusMinus.js"></script>

<p>Ajouter des packages aux packages existant:<p>
<?php
    $form = new Form('/admin/packages/addPackages.html', 'post');

    $form->add('file', 'CSVFile')
         ->isInParagraph(false);

    $form->add('submit', 'Envoyer')
         ->isInParagraph(false);

    echo $form->toString();
?>

<br/>
<br/>
<br/>

<script src="../../web/js/handlers.js"></script>
<script src="../../web/js/editTable.js"></script>

<table id="editableTable">
    <caption>Tableau d'édition des packages</caption>
	<tr>
		<th>Capacité</th>
		<th>Nombre d'inscriptions</th>
		<th>Nom Fr</th>
		<th>Nom En</th>
		<th>Description Fr</th>
		<th>Description En</th>
		<th>Action</th>
	</tr>
<?php
	foreach ($packages as $package)
	{
		$sizeCapacity = strlen($package->getCapacity());
		$sizeRegistrationsCount = strlen($package->getRegistrationsCount());
		$sizeNameFR = strlen($package->getName('fr'));
		$sizeNameEN = strlen($package->getName('en'));

		echo '<tr>';
		echo "<td><p class='editable' data-id='{$package->getId()}' data-verify-callback='true' data-entry-name='Packages' data-field-name='Capacity' data-form-type='number' data-form-size='{$sizeCapacity}'>{$package->getCapacity()}</p></td>";
		echo "<td><p class='labelable' data-form-size='{$sizeRegistrationsCount}'>{$package->getRegistrationsCount()}</p></td>";
		echo "<td><p class='editable' data-id='{$package->getId()}'  data-entry-name='Packages' data-field-name='Name_fr' data-form-type='text' data-form-size='{$sizeNameFR}'>{$package->getName('fr')}</p></td>";
		echo "<td><p class='editable' data-id='{$package->getId()}'  data-entry-name='Packages' data-field-name='Name_en' data-form-type='text' data-form-size='{$sizeNameEN}'>{$package->getName('en')}</p></td>";
		echo "<td><p class='editable' data-id='{$package->getId()}'  data-entry-name='Packages' data-field-name='Description_fr' data-form-type='textbox'>{$package->getDescription('fr')}</p></td>";
		echo "<td><p class='editable' data-id='{$package->getId()}'  data-entry-name='Packages' data-field-name='Description_en' data-form-type='textbox'>{$package->getDescription('en')}</p></td>";
		echo "<td><img class='deletable' data-entry-name='Packages' data-id='{$package->getId()}' src='../../web/images/delete.png' /></a></td>";
		echo '</tr>';
	}
?>
</table>
