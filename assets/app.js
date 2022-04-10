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
const $reservation_etablissement = $("#reservation_etablissement")
const urlParams = new URLSearchParams(window.location.search)

const etablissementQuery = urlParams.get('etablissement')
const suiteQuery = urlParams.get('suite')

console.log(etablissementQuery)
console.log(suiteQuery)

//$reservation_etablissement.val(etablissementQuery);
//$reservation_suite.val(suiteQuery);

$reservation_etablissement.find('option[value="' + etablissementQuery + '"]').attr("selected", "selected")

$(window).ready(function () {
    let $form = $('#reservation')
    let data = {}
    data[$reservation_etablissement.attr('name')] = $reservation_etablissement.val()


    $.post($form.attr('action'), data).then(function (response) {
        $("#reservation_suite").replaceWith(
            $(response).find("#reservation_suite")
        )
        $("#reservation_suite").find('option[value="' + suiteQuery + '"]').attr("selected", "selected")
    })

})

//This script makes possible to load the suites of each etablissement without reloading the page

$reservation_etablissement.on('change', function()
{
    let $form = $('#reservation')
    let data = {}
    data[$reservation_etablissement.attr('name')] = $reservation_etablissement.val()
    $.post($form.attr('action'), data).then(function(response)
    {
        $("#reservation_suite").replaceWith(
            $(response).find("#reservation_suite")
        )
    })
})

// Here I check if there's availability for the room selected, it works like this: First we add the 'check_availability' class
// to the suite, checkIn, and checkOut fields of the form. Then we uses the change event in them and, if they all have values,
// we send a request to the /reservation/check_availability route and we receive the availability

$('#reservation_suite, #reservation_checkIn, #reservation_checkOut').addClass('check_availability')
const $check_availability = $('.check_availability')

$check_availability.change(function(){

    let $suite = $('#reservation_suite').val()
    let $checkIn= $('#reservation_checkIn').val()
    let $checkOut= $('#reservation_checkOut').val()

    if ($suite && $checkIn && $checkOut) {
        let data={};
        data['suite'] = $suite;
        data['checkIn'] = $checkIn;
        data['checkOut'] = $checkOut;

        $.ajax({
            type: 'POST',
            url: '/reservation/check_availability',
            data: data,
            error: function(){
                console.log('error')
            },
            success: function(data){
                console.log(data)
                if(data.status === 'error'){
                    console.log('La suite n\'est pas disponible, choissisez une autre date')
                    $("#submit_reservation").attr('disabled', true)

                }else{
                    console.log('La suite est disponible')
                    $("#submit_reservation").attr('disabled', false)
                }
            }
        })
    }

})

