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

function addClassroomHTML(element, data)
{
	if (hasError(data))
		alert(data);
	else
	{
		var object = '<tr><td data-id="' + data + '" data-form-type="text" data-field-name="Name" data-entry-name="Classrooms" class="editable">Nouvelle salle</td><td data-id="' + data + '" data-form-type="number" data-field-name="Size" data-entry-name="Classrooms" class="editable">30</td><td></td><td><img class="deletable" data-id="' + data + '" data-entry-name="Classrooms" src="../../web/images/delete.png" /></td></tr>';
		$('#editableTable').append(object);
	}
}

function addClassroomEntry(element)
{
	$.post("/admin/ajax/addEntry.html", {
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
    $(newElem).attr('data-verify-callback', $(element).attr('data-verify-callback'));
    $(newElem).attr('is-config-date', $(element).attr('is-config-date'));
    $(newElem).attr('post-delete', $(element).attr('post-delete'));
	if (hasError(data))
	{
		$(newElem).html($(element).attr('oldValue'));
		alert(data);
	}
	else
	{
		$(newElem).html($(element).attr('value'));
	}
	$(element).replaceWith(newElem);
}

function sendTextForm(element, tagName)
{
	$.post("/admin/ajax/modifyText.html", {
	'data-entry-name': $(element).attr('data-entry-name'),
	'data-field-name': $(element).attr('data-field-name'),
	'data-form-type': $(element).attr('data-form-type'),
	'data-id': $(element).attr('data-id'),
	'data-id-sub': $(element).attr('data-id-sub'),
	'data-subfield-name': $(element).attr('data-subfield-name'),
    'data-verify-callback': $(element).attr('data-verify-callback'),
    'is-config-date': $(element).attr('is-config-date'),
    'post-delete': $(element).attr('post-delete'),
	'value': $(element).val()}).error(onError).complete(function(data, textStatus)
	{
		finishSendTextForm(element, data.responseText, tagName);
	});
}

function finishDeleteEntry(element, data)
{
	if (hasError(data))
		alert(data);
	else
	{
		if ($(element).attr('data-entry-name') == 'Availabilities')
		{
			$(element).next().remove();
			for (i = 0; i < 18; ++i)
				$(element).prev().remove();
			$(element).remove();
		}

		else if ($(element).attr('data-entry-name') == 'Questions')
		{
            // Note: there is only on question per table

            // Delete associated questions table before
			$(element).parent().parent().parent().parent().next().remove();
			$(element).parent().parent().parent().remove();
        }

		else
			$(element).parent().parent().remove();
	}
}

function deleteEntry(element)
{
	if (window.confirm("Etes-vous sûr de vouloir supprimer cet élément ?"))
    {
        var url = "/admin/ajax/deleteEntry.html";

        if($(element).attr('data-app-name') == 'frontend')
            url = '/ajax/deleteEntry.html';

		$.post(url, {
		'data-entry-name': $(element).attr('data-entry-name'),
		'post-delete': $(element).attr('post-delete'),
		'data-id': $(element).attr('data-id')}).error(onError).complete(function(data, textStatus)
		{
			finishDeleteEntry(element, data.responseText);
		});
    }
}
