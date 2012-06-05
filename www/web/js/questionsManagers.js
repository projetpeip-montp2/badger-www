function sendValue(e)
{
    $.post("/vbMifare/admin/ajax/modifyText.html", {
		'data-id': $(e).attr('data-id'),
        'data-entry-name': $(e).attr('data-entry-name'),
        'data-field-name': $(e).attr('data-field-name'),
        'data-form-type': 'text',
        'value': $(e).val()
		}).error(onError).complete(function(data)
		{
			if (hasError(data))
				onError();
        });
}

$(document).ready(function()
{
    $('.selectable').bind('change', function(e)
    {
        sendValue(e.target);
    });
});
