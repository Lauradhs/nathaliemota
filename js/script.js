document.addEventListener("DOMContentLoaded", function () {
  // Récupére le lien du menu et la modale
  const lienContact = document.getElementById("menu-item-15");
  const modaleContact = document.getElementById("modal");
  const btnContact = document.querySelector(".btn-contact");

  let modaleOuverte = false; // Variable pour suivre l'état de la modale

  // Fonction pour gérer l'ouverture/fermeture de la modale
  function toggleModale(event) {
    event.preventDefault();
    if (modaleOuverte) {
      modaleContact.style.display = "none"; // Ferme la modale
    } else {
      modaleContact.style.display = "block"; // Ouvre la modale
    }
    modaleOuverte = !modaleOuverte; // Inverse l'état de la modale
  }

  // Écouteur d'événements pour ouvrir/fermer la modale
  lienContact.addEventListener("click", toggleModale);
  if (btnContact) {
    btnContact.addEventListener("click", toggleModale);
  }
});


// Champ Ref automatique
jQuery(document).ready(function ($) {
  $("#modal").on("click", function () {
    // Récupère la valeur du champ "Référence"
    var referenceValue = $(".post-ref").text();

    // Affiche la valeur dans la modale
    $("#label-ref").text(referenceValue);

    // Ouverture modale
    $("#modal").show();
  });
});


// Filter
jQuery(document).ready(function ($) {
  let currentPage = 1;

  // Ajouter une gestion pour le changement de catégorie
  $("#filter").change(function () {
    let selectedCategory = $("#filter select[name='categoryfilter']").val();
    currentPage = 1; // Réinitialiser la page à 1 lors du changement de catégorie

    $.ajax({
      type: "POST",
      url: "/wp-admin/admin-ajax.php",
      dataType: "json", 
      data: {
        action: "weichie_ajax_handler",
        paged: currentPage,
        category: selectedCategory, // Ajoute la catégorie aux données envoyées
      },
      success: function (response) {
        $(".publication-list").html(response.content); // Remplace le contenu existant avec le nouveau
      },
    });
  });

// Load More 
  $("#load-more").on("click", function () {
    currentPage++;

    $.ajax({
      type: "POST",
      url: "/wp-admin/admin-ajax.php",
      dataType: "json",
      data: {
        action: "weichie_ajax_handler",
        paged: currentPage,
        category: $("#filter select[name='categoryfilter']").val(), // Ajoute la catégorie aux données envoyées
      },
      success: function (response) {
        $(".publication-list").append(response.content);
      },
    });
  });
});

