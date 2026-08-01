
window.chargerPlaces = function (idvoit, selectId) {
    const sel = document.getElementById(selectId);
    if (!idvoit) {
        sel.innerHTML = '<option value="">Sélectionner une voiture</option>';
        return;
    }
    sel.innerHTML = '<option>Chargement...</option>';
    fetch('/reservation/places-libres/' + idvoit)
        .then(r => r.json())
        .then(data => {
            if (data.length === 0) {
                sel.innerHTML = '<option value="">Aucune place libre</option>';
            } else {
                sel.innerHTML = '<option value="">Choisir une place</option>';
                data.forEach(p => {
                    sel.innerHTML += `<option value="${p.place}">Place N° ${p.place}</option>`;
                });
            }
        })
        .catch(() => {
            sel.innerHTML = '<option value="">Erreur de chargement</option>';
        });
};

window.toggleAvance = function (divId, val) {
    const el = document.getElementById(divId);
    if (el) el.style.display = (val === 'avec avance') ? 'block' : 'none';
};
window.verifierAvance = function (inputId, frais, msgId) {
    const input = document.getElementById(inputId);
    const msg   = document.getElementById(msgId);
    if (!input || !msg) return;

    const avance = parseInt(input.value) || 0;

    if (avance <= 0) {
        msg.className = 'text-warning small mt-1';
        msg.innerHTML = 'Aucune avance saisie';
    } else if (avance >= frais) {
        msg.className = 'text-success small mt-1';
        msg.innerHTML = 'Avance complète : <b>' + frais.toLocaleString('fr-FR') + ' Ar</b>';
         input.value = frais; 
    } else if (avance > 0 && avance < frais) {
        msg.className = 'text-info small mt-1';
        msg.innerHTML = 'Reste à payer : <b>' + (frais - avance).toLocaleString('fr-FR') + ' Ar</b>';
    }
};

window.afficherPlacesLibres = function (idvoit) {
    const div = document.getElementById('places_libres_result');
    if (!div) return;
    if (!idvoit) { div.innerHTML = ''; return; }
    fetch('/reservation/places-libres/' + idvoit)
        .then(r => r.json())
        .then(data => {
            if (data.length === 0) {
                div.innerHTML = '<span class="badge bg-danger fs-6">Aucune place libre</span>';
            } else {
                let html = '<b>Places libres :</b> ';
                data.forEach(p => {
                    html += `<span class="badge bg-success me-1">Place ${p.place}</span>`;
                });
                div.innerHTML = html;
            }
        });
};

window.afficherStats = function (idvoit) {
    const div = document.getElementById('stats_result');
    if (!div) return;
    if (!idvoit) { div.innerHTML = ''; return; }
    fetch('/reservation/stats/' + idvoit)
        .then(r => r.json())
        .then(d => {
            div.innerHTML = `
                <span class="badge bg-danger me-2 fs-6">Sans avance : ${d.sans_avance}</span>
                <span class="badge bg-warning text-dark me-2 fs-6">Avec avance (reste dû) : ${d.avec_avance}</span>
                <span class="badge bg-success me-2 fs-6">Tout payé : ${d.tout_paye}</span>
            `;
        });
};

window.afficherRecette = function () {
    const div = document.getElementById('recette_result');
    if (!div) return;
    fetch('/reservation/recette')
        .then(r => r.json())
        .then(d => {
            div.innerHTML = 'Recette totale : ' + Number(d.total).toLocaleString('fr-FR') + ' Ar';
        });
};
