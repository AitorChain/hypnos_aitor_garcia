/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';

//This script makes possible to load the suites of each etablissement without reloading the page
var $reservation_etablissement = $("#reservation_etablissement")
var $token = $("#reservation__token")

$reservation_etablissement.change(function()
{
    var $form = $(this).closest('form')
    var data = {}
    data[$reservation_etablissement.attr('name')] = $reservation_etablissement.val()
    data[$token.attr('name')] = $token.val()

    $.post($form.attr('action'), data).then(function(response)
    {
        $("#reservation_suite").replaceWith(
            $(response).find("#reservation_suite")
        )
    })
})

////////////////////////////7