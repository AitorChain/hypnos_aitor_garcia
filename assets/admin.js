import './styles/admin.css';

import bsCustomFileInput from 'bs-custom-file-input';

bsCustomFileInput.init();

$('.action-delete').attr('data-bs-target', '#modal-delete-restriction')

$('#main').prepend('<div id="modal-delete-restriction" class="modal fade" tabindex="-1" bis_skin_checked="1">\n' +
    '    <div class="modal-dialog" bis_skin_checked="1">\n' +
    '        <div class="modal-content" bis_skin_checked="1">\n' +
    '            <div class="modal-body" bis_skin_checked="1">\n' +
    '                <h4>Voulez-vous supprimer cette réservation ?</h4>\n' +
    '                <p><strong>Rappel:</strong> Les reservations dont la date d\'arrivé est dans 3 jours ne sont pas annulables.</p>\n' +
    '            </div>\n' +
    '            <div class="modal-footer" bis_skin_checked="1">\n' +
    '                <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">\n' +
    '                    <span class="btn-label">Annuler</span>\n' +
    '                </button>\n' +
    '\n' +
    '                <button type="button" data-bs-dismiss="modal" class="btn btn-danger" id="modal-delete-button" form="delete-form">\n' +
    '                    <span class="btn-label">Supprimer</span>\n' +
    '                </button>\n' +
    '            </div>\n' +
    '        </div>\n' +
    '    </div>\n' +
    '</div>')