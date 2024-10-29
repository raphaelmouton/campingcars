import './styles/app.scss';

// Import jQuery
import $ from 'jquery';

// Import Bootstrap (vous pouvez choisir la version ES5 si besoin)
import 'bootstrap/dist/js/bootstrap.bundle';

// Log pour confirmer le chargement
console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

// Attendre que le DOM soit prêt
$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});
