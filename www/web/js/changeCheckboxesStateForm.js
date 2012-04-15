function changeCheckboxesStateForm(container_id, state)
{
    var checkboxes = document.getElementById(container_id).getElementsByTagName('input');

    for(var i=0; i < checkboxes.length; i++)
    {
        if(checkboxes[i].type == 'checkbox')
            checkboxes[i].checked = state;
    }
}
