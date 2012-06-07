// jQuery plugin: PutCursorAtEnd 1.0
// http://plugins.jquery.com/project/PutCursorAtEnd
// by teedyay
//
// Puts the cursor at the end of a textbox/ textarea

// codesnippet: 691e18b1-f4f9-41b4-8fe8-bc8ee51b48d4
(function($)
{
    jQuery.fn.putCursorAtEnd = function()
    {
    return this.each(function()
    {
        $(this).focus()

        // If this function exists...
        if (this.setSelectionRange)
        {
        // ... then use it
        // (Doesn't work in IE)

        // Double the length because Opera is inconsistent about whether a carriage return is one character or two. Sigh.
        var len = $(this).val().length * 2;
        this.setSelectionRange(len, len);
        }
        else
        {
        // ... otherwise replace the contents with itself
        // (Doesn't work in Google Chrome)
        $(this).val($(this).val());
        }

        // Scroll to the bottom, in case we're in a tall textarea
        // (Necessary for Firefox and Google Chrome)
        this.scrollTop = 999999;
    });
    };
})(jQuery);

$(document).ready(function()
{
	$(".editable").live("click", function(e)
	{
		modifyCell(e.target);
	});
	$(".addable").live("click", function(e)
	{
		addForm(e.target);
	});
	$(".deletable").live("click", function(e)
	{
		deleteEntry(e.target);
	});
	$("#verify-count").live("click", function(e)
	{
		verifyCount(e.target);
	});
});

function addForm(element)
{	
	switch ($(element).attr('data-entry-name'))
	{
		case "Availabilities":
			addAvailabilityEntry(element);
			break;
		case "Classrooms":
			addClassroomEntry(element);
			break;
		default:
			alert("Error on addForm data-entry-name value");
			break;
	}
}

function getYear()
{
	var d = new Date();
	
	return (d.getFullYear()); 
}

function modifyCell(element)
{
	switch ($(element).attr('data-form-type'))
	{
		case 'text':
		case 'number':
		case 'textbox':
			previousValue = $(element).html();

			// To Fix
			if ($(element).attr('data-form-type') == 'textbox')
				newElem = $('<textarea />');
			else
				newElem = $('<input />');
			tagName = $(element).get(0).tagName;
			$(newElem).attr('value', previousValue);
			$(newElem).attr('oldValue', previousValue);
			$(newElem).attr('data-entry-name', $(element).attr('data-entry-name'));
			$(newElem).attr('data-field-name', $(element).attr('data-field-name'));
			$(newElem).attr('data-form-type', $(element).attr('data-form-type'));
			$(newElem).attr('data-id', $(element).attr('data-id'));
			$(newElem).attr('data-id-sub', $(element).attr('data-id-sub'));
			$(newElem).attr('data-subfield-name', $(element).attr('data-subfield-name'));
			
			if ($(element).attr('data-verify-callback'))
				$(newElem).attr('data-verify-callback', $(element).attr('data-verify-callback'));
			if ($(element).attr('data-form-size'))
				$(newElem).attr('size', $(element).attr('data-form-size'));
			if ($(element).attr('data-form-type') != 'textbox')
				$(newElem).keydown(function(e)
				{
					if (e.keyCode == 13)
						sendTextForm(this, tagName);
				});
			$(newElem).blur(function()
			{
				sendTextForm(this, tagName);
			});
			$(element).replaceWith(newElem);
			if ($(element).attr('data-form-type') == 'textbox')
				$("textarea").focus().putCursorAtEnd();
			else
				$("input").focus().putCursorAtEnd();
			break;
		default:
			alert('Error');
		break;
	}
}
