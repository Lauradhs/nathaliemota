jQuery(document).ready(function ($) {
  const lienContact = $("#menu-item-15");
  const modaleContact = $("#modal");
  const btnContact = $(".btn-contact");
  const lightboxTriggerSelector = ".lightbox-trigger";

  // Variable pour suivre l'état de la modale
  let modaleOuverte = false;

  function toggleModale(event) {
    event.preventDefault();
    modaleOuverte = !modaleOuverte;

    if (modaleOuverte) {
      modaleContact.show(); // Affiche la modale
    } else {
      modaleContact.hide(); // Masque la modale
    }
  }

  // Écouteur d'événements pour ouvrir/fermer la modale
  lienContact.on("click", toggleModale);
  btnContact.on("click", toggleModale);

  // Champ Ref automatique
  btnContact.on("click", function () {
    // Récupère la valeur du champ "Référence"
    var referenceValue = $(".num-ref").text();

    // Met à jour la valeur dans le champ d'entrée avec l'attribut "name" égal à "photo-reference"
    $("input[name='photo-reference']").val(referenceValue);
    // Si la modale est fermée, vide le champ d'entrée
    if (!modaleOuverte) {
      $("input[name='photo-reference']").val("");
    }
  });
});



// Menu Burger

document.addEventListener("DOMContentLoaded", function () {
  var mobileMenuToggle = document.querySelector(".mobile-menu-toggle");
  var navMenu = document.getElementById("nav");

  mobileMenuToggle.addEventListener("click", function () {
    navMenu.style.display =
      navMenu.style.display === "none" || navMenu.style.display === ""
        ? "flex"
        : "none";
  });
});
