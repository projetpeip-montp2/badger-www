$(document).ready(function() {
   $('.informations').hide();
 
    $('.clicker').click(function(){
        $(this).children('.informations').toggle();

        var img = $(this).children('.informationsTitle').children('.imgPlusMinus');
        var state = true;                

        if(img.attr('src') == '../../web/images/plus.png')
            state = false;

            img.attr('src', '../../web/images/' + (state ? 'plus' : 'minus') + '.png');
    });
});
