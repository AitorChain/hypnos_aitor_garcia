/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import 'bootstrap';
import bsCustomFileInput from 'bs-custom-file-input';

// start the Stimulus application
import './bootstrap';

bsCustomFileInput.init();

// Hide Header on on scroll down

let lastScrollTop = 0;
$(window).scroll(function(){
    var st = $(this).scrollTop();
    var header = $('header');
    setTimeout(function(){
        if (st > lastScrollTop){
            $('header').css('transform', 'translateY(-7em)')
        } else {
            $('header').css('transform', 'translateY(0)')
        }
        lastScrollTop = st;
    }, 300);
});


//This script makes possible to load the suites of each etablissement without reloading the page
const $reservation_etablissement = $("#reservation_etablissement")
const urlParams = new URLSearchParams(window.location.search)

const etablissementQuery = urlParams.get('etablissement')
const suiteQuery = urlParams.get('suite')

$reservation_etablissement.find('option[value="' + etablissementQuery + '"]').attr("selected", "selected")

let euroFR = Intl.NumberFormat("fr-FR", {
    style: "currency",
    currency: "EUR",
});

let dollarUS = Intl.NumberFormat("en-US", {
    style: "currency",
    currency: "USD",
});

let formatDate = new Intl.DateTimeFormat('fr-FR')


$(window).ready(function () {
    $("#submit_reservation").attr('disabled', true)
    let $form = $('#reservation')
    let data = {}
    data[$reservation_etablissement.attr('name')] = $reservation_etablissement.val()


    $.post($form.attr('action'), data).then(function (response) {
        $("#reservation_suite").replaceWith(
            $(response).find("#reservation_suite")
                .removeClass('is-invalid')
        )
        $("#reservation_suite")
            .find('option[value="' + suiteQuery + '"]').attr("selected", "selected")
            .removeClass('is-invalid')
    })

    let today = new Date()
    let dd = String(today.getDate()).padStart(2, '0')
    let mm = String(today.getMonth() + 1).padStart(2, '0')
    let yyyy = today.getFullYear()

    today = yyyy + '-' + mm + '-' + dd

    $('#reservation_checkIn').attr('min',today)
    $('#reservation_checkOut').attr('min',today)

})

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
        $("#reservation_suite").removeClass('is-invalid')
    })
})

// Here I check if there's availability for the room selected, it works like this: First we select the suite, checkIn, and checkOut fields of the form.
// Then we uses the change event in them and, if they all have values,
// we send a request to the /reservation/check_availability route and we receive the availability

$(document).on('change', '#reservation_suite, #reservation_checkIn, #reservation_checkOut', function(){
    let suite = $('#reservation_suite').val()
    let checkIn= $('#reservation_checkIn').val()
    let checkOut= $('#reservation_checkOut').val()
    let hotel = $('#reservation_etablissement').val()
    let hotelText = $('#reservation_etablissement option:selected').text()
    let suiteText = $('#reservation_suite option:selected').text()



    if (suite && checkIn && checkOut) {

        //Price check
        let daysInSeconds = Date.parse(checkOut) - Date.parse(checkIn)
        let days = daysInSeconds/86400000
        let dataPrix={}
        let prix_suite
        let prix_sejour
        dataPrix['suite'] = suite

        $.ajax({
            type: 'POST',
            url: '/reservation/check_price',
            data: dataPrix,
            error: function(){
                console.log(dataPrix)
            },
            success: function(data){
                if(data.status === 'error'){
                    console.log('Impossible d\'envoyer le prix de la suite')

                }else{
                    //console.log(data.message)
                    prix_suite = data.message
                    prix_sejour = (parseInt(prix_suite) * days) / 100
                }
            }
        })

        //Availability check
        let data={}
        data['suite'] = suite
        data['checkIn'] = checkIn
        data['checkOut'] = checkOut

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
                    console.log('La suite n\'est pas disponible pour ces dates, choissisez une autre date')
                    $("#submit_reservation").attr('disabled', true)
                    $("#reservation_checkIn").addClass('is-invalid')
                    $("#reservation_checkOut").addClass('is-invalid')
                    $("#availability_info")
                        .html('<strong class="red">La suite n\'est disponible</strong>, <strong>chossisez une autre date</strong>')

                } else if (data.status === 'invalid_date') {
                    console.log('Vous ne pouvez pas voyager dans le temps!')
                    $("#reservation_checkIn").addClass('is-invalid')
                    $("#reservation_checkOut").addClass('is-invalid')
                    $("#availability_info")
                        .html('<strong>Les dates choisies ne sont pas valides</strong>')

                } else {
                    console.log('La suite est disponible')
                    console.log(data);
                    $("#submit_reservation").attr('disabled', false)
                    $("#reservation_checkIn")
                        .removeClass('is-invalid')
                        .addClass('is-valid')
                    $("#reservation_checkOut")
                        .removeClass('is-invalid')
                        .addClass('is-valid')
                    $("#availability_info")
                        .html(`<strong class="green">La suite est disponible.</strong> Le prix de votre sejour est de <strong> ${euroFR.format(prix_sejour)}</strong>`)
                    $("#alert-modal-body")
                        .html(`<p>Voulez allez reserver la suite <strong>${suiteText}</strong> de l\'hôtel <strong>${hotelText}</strong> pour le suivants dates:</p>
                        <ul>
                            <li>Date d'arrivée: <strong>${formatDate.format(Date.parse(checkIn))}</strong></li>
                            <li>Date de depart: <strong>${formatDate.format(Date.parse(checkOut))}</strong></li>
                        </ul>
                        <p>La tarif de votre sejour est de <strong>${euroFR.format(prix_sejour)}</strong>. Vous réglerez a la reception de l'hôtel lors de votre arrivée</p>`)
                }
            }
        })
    }

})






