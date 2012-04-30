(function() {

var searchElement = $('.vbmifareUsername');
var previousValue = searchElement.val();
var selectedResult = -1;
var previousRequest;
var results = $('#results');


function displayResults(response) 
{
    response = response.split('#');

    var found = (response[0] == "T");
    var autocompleteResults = response[1];

    if(autocompleteResults.length)
    {
        results.css('display', 'block');
        results.empty();

        results.append('<img src="/vbMifare/web/images/icon' + (found ? 'Ok' : 'Error') + '.png"/>');

        autocompleteResults = autocompleteResults.split(';');

        for (var i=0; i<autocompleteResults.length ; i++) 
        {
            results.append('<div>' + autocompleteResults[i] + '</div>');
        /*
            div.bind('click',function(){
                chooseResult( $(this) );
            });
        */
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


function chooseResult(result) 
{
    searchElement.val(results.text());
    previousValue = results.text();
    results.css('display', 'none');
    selectedResult = -1;
    searchElement.focus();
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
