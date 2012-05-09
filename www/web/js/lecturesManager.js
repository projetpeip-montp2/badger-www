var chosenAvailabilities = new Array();
var conflicts = new Array();

Array.prototype.remove = function(from, to)
{
  var rest = this.slice((to || from) + 1 || this.length);
  this.length = from < 0 ? this.length + from : from;
  return this.push.apply(this, rest);
};

Array.prototype.countOccurrences = function(elem)
{
	var count = 0;
	
	for (i = 0; i < this.length; ++i)
	{
		if (this[i] == elem)
			++count;
	}
	return (count);
};

jQuery.extend({
    stringify  : function stringify(obj) {

        if ("JSON" in window) {
            return JSON.stringify(obj);
        }

        var t = typeof (obj);
        if (t != "object" || obj === null) {
            // simple data type
            if (t == "string") obj = '"' + obj + '"';

            return String(obj);
        } else {
            // recurse array or object
            var n, v, json = [], arr = (obj && obj.constructor == Array);

            for (n in obj) {
                v = obj[n];
                t = typeof(v);
                if (obj.hasOwnProperty(n)) {
                    if (t == "string") {
                        v = '"' + v + '"';
                    } else if (t == "object" && v !== null){
                        v = jQuery.stringify(v);
                    }

                    json.push((arr ? "" : '"' + n + '":') + String(v));
                }
            }

            return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");
        }
    }
});

function convertDate(strDate)
{ 
	day = strDate.substring(0,2);
	month = strDate.substring(3,5);
	year = strDate.substring(6,10);

	return (year + '-' + month + '-' + day);  
}

function generateOptionsHTML(lecture, size)
{
	var startTime = new Date(convertDate(lecture.date) + 'T' + lecture.startTime);
	var endTime = new Date(convertDate(lecture.date) + 'T' + lecture.endTime);
	var html = '<option value="0">Non assign&eacute;e</option>';

	for (k = 0; k < jsonClassrooms.length; ++k)
	{
		for (l = 0; l < jsonClassrooms[k].availabilities.length; ++l)
		{
			var availabilityStartTime = new Date(convertDate(jsonClassrooms[k].availabilities[l].date) + 'T' + jsonClassrooms[k].availabilities[l].startTime);
			var availabilityEndTime = new Date(convertDate(jsonClassrooms[k].availabilities[l].date) + 'T' + jsonClassrooms[k].availabilities[l].endTime);

			if (startTime >= availabilityStartTime && endTime <= availabilityEndTime && parseInt(jsonClassrooms[k].capacity) >= parseInt(size))
			{
				if (jsonClassrooms[k].availabilities[l].id == lecture.idAvailability)
					html += '<option selected="selected" value="' + jsonClassrooms[k].availabilities[l].id + '">' + jsonClassrooms[k].name + '</option>';
				else
					html += '<option value="' + jsonClassrooms[k].availabilities[l].id + '">' + jsonClassrooms[k].name + '</option>';

			}
		}
	}
	
	$('.trLecture').each(function()
	{
		if ($(this).attr('data-id') == lecture.id)
		{
			$(this).children('td').children('select').html(html);
			$(this).children('td').children('select').data('oldValue', $(this).children('td').children('select').val());
			return (false);
		}
	});
}

function solveConflict(id)
{
	$('.trLecture td select').each(function()
	{
		if ($(this).val() == id)
			$(this).css('backgroundColor', '');
	});
	
	conflicts.remove(jQuery.inArray(id, conflicts));
}

function makeConflict(id)
{
	if (jQuery.inArray(id, conflicts) == -1)
 		conflicts.push(id);
		
	$('.trLecture td select').each(function()
	{
		if ($(this).val() == id)
			$(this).css('backgroundColor', 'red');	
	});

}

function changedAvailability(element)
{
	$element = $(element);

	if ($element.data('oldValue') != 0)
	{
		if (jQuery.inArray($element.data('oldValue'), chosenAvailabilities) == -1)
		{
			// Ca ne devrait pas arriver.
			alert('Erreur dans la gestion DOM du Javascript');
			return ;
		}
		
		// On enlève dans le tableau des disponibilités choisies cette disponibilité
		chosenAvailabilities.remove(jQuery.inArray($element.data('oldValue'), chosenAvailabilities));
		
		// La lecture est obligatoirement sortie de son conflit de disponibilités, on remet le select en blanc (pour l'instant)
		$element.css('backgroundColor', '');
		
		// Est-ce que ce changement à permis de résoudre entièrement le conflit de disponibilités dans laquelle la lecture était engagée ?
		if (chosenAvailabilities.countOccurrences($element.data('oldValue')) == 1)
			solveConflict($element.data('oldValue'));
	}
	
	if ($element.val() != 0)
	{
		if (jQuery.inArray($element.val(), chosenAvailabilities) != -1)
			makeConflict($element.val());
		chosenAvailabilities.push($element.val());
	}
	
	$element.data('oldValue', $element.val());
}

function onError()
{
	alert('Erreur: Connexion au site échouée, les modifications faites n\'ont pas encore été prises en compte. Réessayez dès que la connexion est rétablie');
}

function sendAvailabilities()
{
	var packet = new Array();
	
	if (conflicts.length != 0)
	{
		alert('Erreur: Vous devez résoudre les conflits d\'horaires avant de pouvoir enregistrer');
		return ;
	}
	
	$('.trLecture').each(function()
	{
		if ($(this).children('td').children('select').val() != $(this).attr('data-id-availability'))
		{
			packet.push({"id": $(this).attr('data-id'), "idAvailability": $(this).children('td').children('select').val(), "idPackage": $(this).attr('data-id-package')});
		}
	});
		alert(jQuery.stringify(packet));

	if (packet.length != 0)
		$.post("/vbMifare/admin/lectures/assignLectures.html", {
		'jsonPacket': jQuery.stringify(packet)
		}).error(onError).complete(function()
		{
		//	location.href = '/vbMifare/admin';
		});
//	else
	//	location.href = '/vbMifare/admin';
}

$(document).ready(function()
{	
	$('select').bind('change', function(e)
	{
		changedAvailability(e.target);
	});
	
	$('.positive').bind('click', function(e)
	{
		sendAvailabilities();
	});
	
	for (i = 0; i < jsonPackages.length; ++i)
	{
		for (j = 0; j < jsonPackages[i].lectures.length; ++j)
		{
			if (jsonPackages[i].lectures[j].idAvailability != 0)
				chosenAvailabilities.push(jsonPackages[i].lectures[j].idAvailability);
			generateOptionsHTML(jsonPackages[i].lectures[j], jsonPackages[i].capacity);
		}
	}

});
