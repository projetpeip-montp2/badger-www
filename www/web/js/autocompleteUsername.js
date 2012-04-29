(function() {

var searchElement = $('.vbmifareUsername');
var previousValue = searchElement.val();
var selectedResult = -1;
var previousRequest;
var results = $('#results');


function displayResults(response) 
{
    results.css('display',response.length ? 'block' : 'none');

    if (response.length) 
    {
        response = response.split(';');

        results.empty();

        for (var i=0; i<response.length ; i++) 
        {
            results.append('<div>' + response[i] + '</div>');
/*
            div.bind('click',function(){
                chooseResult( $(this) );
            });
*/
        }
    }
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
