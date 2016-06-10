/**
 * Created by alin.ionescu on 10.05.2016.
 */
$(document).ready(function ()
{
    $('#add-document').click(function (event) {
        event.preventDefault();

        $.ajax({
            url: Routing.generate('add-document'),
            method: 'POST',
        })
    });
});