﻿function trim (myString)
{
	return myString.replace(/^\s+/g,'').replace(/\s+$/g,'')
} 

function onError()
{
	alert('Erreur: Connexion au site échouée, les modifications faites n\'ont pas été prises en compte. La page va se recharger.');
	location.reload();
}

function hasError(data)
{
	var regexp = new RegExp('Erreur');
	if (regexp.exec(data))
		return (true);
	return (false);
}

function addAvailabilityHTML(element, data)
{
	if (hasError(data))
		alert(trim(data));
	else
	{
		$('<p class="editable" data-entry-name="Availabilities" data-field-name="Date" data-subfield-name="Day" data-form-type="number" data-form-size="2" data-id="' + data + '" data-id-sub="' + $(element).attr('data-id') + '">01</p><p class="separator">-</p>').insertBefore(element);
		$('<p class="editable" data-entry-name="Availabilities" data-field-name="Date" data-subfield-name="Month" data-form-type="number" data-form-size="2" data-id="' + data + '" data-id-sub="' + $(element).attr('data-id') + '">01</p><p class="separator">-</p>').insertBefore(element);
		$('<p class="editable" data-entry-name="Availabilities" data-field-name="Date" data-subfield-name="Year" data-form-type="number" data-form-size="2" data-id="' + data + '" data-id-sub="' + $(element).attr('data-id') + '">2012</p><p class="separator"> | </p>').insertBefore(element);
		$('<p class="editable" data-entry-name="Availabilities" data-field-name="StartTime" data-subfield-name="Hours" data-form-type="number" data-form-size="2" data-id="' + data + '" data-id-sub="' + $(element).attr('data-id') + '">00</p><p class="separator">:</p>').insertBefore(element);
		$('<p class="editable" data-entry-name="Availabilities" data-field-name="StartTime" data-subfield-name="Minutes" data-form-type="number" data-form-size="2" data-id="' + data + '" data-id-sub="' + $(element).attr('data-id') + '">00</p><p class="separator">:</p>').insertBefore(element);
		$('<p class="editable" data-entry-name="Availabilities" data-field-name="StartTime" data-subfield-name="Seconds" data-form-type="number" data-form-size="2" data-id="' + data + '" data-id-sub="' + $(element).attr('data-id') + '">00</p><p class="separator"> -> </p>').insertBefore(element);
		$('<p class="editable" data-entry-name="Availabilities" data-field-name="EndTime" data-subfield-name="Hours" data-form-type="number" data-form-size="2" data-id="' + data + '" data-id-sub="' + $(element).attr('data-id') + '">23</p><p class="separator">:</p>').insertBefore(element);
		$('<p class="editable" data-entry-name="Availabilities" data-field-name="EndTime" data-subfield-name="Minutes" data-form-type="number" data-form-size="2" data-id="' + data + '" data-id-sub="' + $(element).attr('data-id') + '">59</p><p class="separator">:</p>').insertBefore(element);
		$('<p class="editable" data-entry-name="Availabilities" data-field-name="EndTime" data-subfield-name="Seconds" data-form-type="number" data-form-size="2" data-id="' + data + '" data-id-sub="' + $(element).attr('data-id') + '">59</p><br />').insertBefore(element);
	}
}

function addAvailabilityEntry(element)
{
	$.post("/vbMifare/admin/ajax/addEntry.html", {
	'data-id': $(element).attr('data-id'),
	'data-entry-name': $(element).attr('data-entry-name')
	}).error(onError).complete(function(data)
	{
		addAvailabilityHTML(element, data.responseText);
	});
}

function addClassroomHTML(element, data)
{
	if (hasError(data))
		alert(data);
	else
	{
		var object = '<tr><td data-id="' + data + '" data-form-type="text" data-field-name="Name" data-entry-name="Classrooms" class="editable">Nouvelle salle</td><td data-id="' + data + '" data-form-type="number" data-field-name="Size" data-entry-name="Classrooms" class="editable">30</td><td><a data-id="' + data + '" data-entry-name="Availabilities" class="addable">Insérer une nouvelle disponibilité</a></td></tr>';
		$('#classroom').append(object);
	}
}

function addClassroomEntry(element)
{
	$.post("/vbMifare/admin/ajax/addEntry.html", {
	'data-entry-name': $(element).attr('data-entry-name'),
	'data-id': -1
	}).error(onError).complete(function(data)
	{
		addClassroomHTML(element, data.responseText);
	});
}

function finishSendTextForm(element, data, tagName)
{
	// TODO: To Fix
	newElem = $('<' + tagName + '/>');
	$(newElem).attr('class', 'editable');
	$(newElem).attr('data-entry-name', $(element).attr('data-entry-name'));
	$(newElem).attr('data-field-name', $(element).attr('data-field-name'));
	$(newElem).attr('data-form-type', $(element).attr('data-form-type'));
	$(newElem).attr('data-id', $(element).attr('data-id'));
	$(newElem).attr('data-id-sub', $(element).attr('data-id-sub'));
	$(newElem).attr('data-subfield-name', $(element).attr('data-subfield-name'));
	if (hasError(data))
	{
		$(newElem).html($(element).attr('oldValue'));
		alert(trim(data));
	}
	else
	{
		$(newElem).html($(element).attr('value'));
	}
	$(element).replaceWith(newElem);
}

function sendTextForm(element, tagName)
{
	$.post("/vbMifare/admin/ajax/modifyText.html", {
	'data-entry-name': $(element).attr('data-entry-name'),
	'data-field-name': $(element).attr('data-field-name'),
	'data-form-type': $(element).attr('data-form-type'),
	'data-id': $(element).attr('data-id'),
	'data-id-sub': $(element).attr('data-id-sub'),
	'data-subfield-name': $(element).attr('data-subfield-name'),
	'value': $(element).val()}).error(onError).complete(function(data, textStatus)
	{
		finishSendTextForm(element, data.responseText, tagName);
	});
}