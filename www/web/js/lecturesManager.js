var chosenAvailabilities = new Array();

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

function hasError(data)
{
	var regexp = new RegExp('Erreur');
	if (regexp.exec(data))
		return (true);
	return (false);
}

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
					html += '<option selected="selected" value="' + jsonClassrooms[k].availabilities[l].id + '">' + jsonClassrooms[k].name + ' (' + jsonClassrooms[k].capacity + ')</option>';
				else if (jQuery.inArray(jsonClassrooms[k].availabilities[l].id, chosenAvailabilities) == -1)
					html += '<option value="' + jsonClassrooms[k].availabilities[l].id + '">' + jsonClassrooms[k].name + ' (' + jsonClassrooms[k].capacity + ')</option>';
				else
					html +=  '<option disabled="disabled" value="' + jsonClassrooms[k].availabilities[l].id + '">' + jsonClassrooms[k].name + ' (' + jsonClassrooms[k].capacity + ')</option>';
			}
		}
	}
	
	$('.trLecture').each(function()
	{
		if ($(this).attr('data-id') == lecture.id)
		{
			$(this).children('td').children('select').html(html);
			return (false);
		}
	});
}

function setDisabledStatus(dataId, idDisable, idEnable)
{
	$('.trLecture').each(function()
	{
		if ($(this).attr('data-id') != dataId)
		{
			$(this).children('td').children('select').children('option').each(function()
			{
				if ($(this).attr('disabled') == 'disabled' && $(this).val() == idEnable)
					$(this).removeAttr('disabled');
				else if ($(this).attr('disabled') == undefined && $(this).val() == idDisable && $(this).val() != 0)
					$(this).attr('disabled', 'disabled');
			});
		}
	});
}

function changedAvailability(element)
{
	$element = $(element);
	$parentTr = $element.parent().parent();
	var packet = new Array();
	
	packet.push({"id": $parentTr.attr('data-id'), "idAvailability": $element.val(), "idPackage": $parentTr.attr('data-id-package')});
	$.post("/admin/lectures/assignLectures.html", {
		'jsonPacket': jQuery.stringify(packet)
		}).error(onError).complete(function(data)
		{
			if (hasError(data))
				onError();
			else
			{
				var oldValue = $parentTr.attr('data-id-availability');
				if (oldValue != 0)
					chosenAvailabilities.remove(jQuery.inArray(oldValue, chosenAvailabilities));
				if ($element.val() != 0)
					chosenAvailabilities.push($element.val());
				$parentTr.attr('data-id-availability', $element.val());
				setDisabledStatus($parentTr.attr('data-id'), $element.val(), oldValue);
			}
		});
}

function onError()
{
	alert('Erreur: Connexion au site échouée, les modifications faites n\'ont pas encore été prises en compte. La page va être rechargée');
	location.reload();
}

$(document).ready(function()
{	
	$('select').bind('change', function(e)
	{
		changedAvailability(e.target);
	});
	
	for (i = 0; i < jsonPackages.length; ++i)
	{
		for (j = 0; j < jsonPackages[i].lectures.length; ++j)
		{
			if (jsonPackages[i].lectures[j].idAvailability != 0)
				chosenAvailabilities.push(jsonPackages[i].lectures[j].idAvailability);
		}
	}

	for (i = 0; i < jsonPackages.length; ++i)
	{
		for (j = 0; j < jsonPackages[i].lectures.length; ++j)
		{
			generateOptionsHTML(jsonPackages[i].lectures[j], jsonPackages[i].capacity);
		}
	}
});
