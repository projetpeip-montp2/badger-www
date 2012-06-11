(function() {

var searchElement = $('.username');
var previousValue = searchElement.val();
var selectedResult = -1;
var previousRequest;
var results = $('#results');
var infos = $('#infos');


function displayResults(response) 
{
    response = jQuery.parseJSON(response);

    var found = (response.Found == "T");
    var autocompleteResults = response.Autocomplete;

    if(autocompleteResults.length)
    {
        results.css('display', 'block');
        results.empty();

        infos.css('display', 'inline');
        infos.empty();

        if(found)
        {
            infos.append('<img src="/web/images/tools/iconOk.png"/>');
    
            infos.append('<select name="selectedLecture" id="selectedLecture">');

            for (var index in response.Lectures)
                $('#selectedLecture').append('<option value="' + index + '">' + response.Lectures[index] + '</option>');

            infos.append('</select>');

            infos.append('<input type="submit" name="Envoyer" value="Envoyer"/>');
        }

        else
        {
            infos.append('<img src="/web/images/tools/iconError.png"/>');

            autocompleteResults = autocompleteResults.split(';');

            for (var i=0; i<autocompleteResults.length ; i++) 
            {
                results.append('<div class="autocompleteResults'+ i +'">' + autocompleteResults[i] + '</div>');

                $('.autocompleteResults' + i).bind('click',function(){
                    chooseResult( $(this) );
                });
            }
        }
    }

    else
        results.css('display', 'none');
}


function getResults(keywords)
{  
    return $.ajax({
        type: "POST",
        url: "/admin/ajax/autocompleteUsername.html",
        data: { text:searchElement.val() }
    }).done(function(msg) {
        displayResults(msg);
    });
}


function chooseResult(choice) 
{
    previousValue = searchElement.val();
    searchElement.val(choice.text());
    results.css('display', 'none');
    selectedResult = -1;
    searchElement.focus();

    // Simulate a keyup
    $(".username").keyup();
}


$('.username').keyup(function(e) 
{
    if(searchElement.val() != previousValue)
    {
        previousValue = searchElement.val();

        if (previousRequest && previousRequest.readyState < 4) {
            previousRequest.abort();
        }

        previousRequest = getResults(previousValue);

        selectedResult = -1;
    }
});


})();

