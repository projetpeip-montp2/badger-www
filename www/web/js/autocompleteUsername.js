(function() {

var searchElement = $('.username');
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
            results.append('<img src="/web/images/tools/iconOk.png"/>');
    
            results.append('<select name="selectedLecture" id="selectedLecture">');

            for (var index in response.Lectures)
                $('#selectedLecture').append('<option value="' + index + '">' + response.Lectures[index] + '</option>');

            results.append('</select>');

            results.append('<input type="submit" name="Envoyer" value="Envoyer"/>');
        }

        else
        {
            results.append('<img src="/web/images/tools/iconError.png"/>');

            autocompleteResults = autocompleteResults.split(';');

            for (var i=0; i<autocompleteResults.length ; i++) 
            {
                results.append('<div class="autocompleteResults'+ i +'">' + '<a>' + autocompleteResults[i] + '</a>' + '</div>');

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

