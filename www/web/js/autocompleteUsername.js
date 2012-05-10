(function() {

var searchElement = $('.vbmifareUsername');
var previousValue = searchElement.val();
var selectedResult = -1;
var previousRequest;
var results = $('#results');


function displayResults(response) 
{
    response = jQuery.parseJSON(response);

    var found = (response.Found == "T");
    var autocompleteResults = response.Autocomplete;

    if(autocompleteResults.length)
    {
        results.css('display', 'inline');
        results.empty();

        if(found)
        {
            results.append('<img src="/vbMifare/web/images/tools/iconOk.png"/>');
    
            results.append('<select name="vbmifareSelectedLecture" id="vbmifareSelectedLecture">');

            for (var index in response.Lectures)
                $('#vbmifareSelectedLecture').append('<option value="' + index + '">' + response.Lectures[index] + '</option>');

            results.append('</select>');

            results.append('<input type="submit" name="Envoyer" value="Envoyer"/>');
        }

        else
        {
            results.append('<img src="/vbMifare/web/images/tools/iconError.png"/>');

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
        url: "/vbMifare/admin/ajax/autocompleteUsername.html",
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
    $(".vbmifareUsername").keyup();
}


$('.vbmifareUsername').keyup(function(e) 
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

