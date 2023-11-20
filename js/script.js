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

jQuery(document).ready(function ($) {
  let currentPage = 1;
  let selectedCategory = "";
  let selectedFormat = "";
  let selectedYear = "";

  function loadPosts() {

      $.ajax({
          type: "POST",
          url: "/wp-admin/admin-ajax.php",
          dataType: "json",
          data: {
              action: "weichie_ajax_handler",
              paged: currentPage,
              category: selectedCategory,
              format: selectedFormat,
              yearfilter: selectedYear,
          },
          success: function (response) {
              // Assurez-vous que le contenu est un élément jQuery
              let $content = $(response.content);

              // Ajoutez le contenu à votre conteneur
              if (currentPage === 1) {
                  // Si c'est la première page, remplacez le contenu existant
                  $(".publication-list").html($content);
              } else {
                  // Sinon, ajoutez le contenu à la fin de la liste existante
                  $(".publication-list").append($content);
              }

              // Exemple de gestion de la pagination
              if (currentPage < response.max_pages) {
                  $("#load-more").show();
              } else {
                  $("#load-more").hide();
              }
          },
      });
  }

  function loadMoreClickHandler() {
    currentPage++;
    loadPosts();
  }

  $("#load-more").on("click", loadMoreClickHandler);

  // Ajouter une gestion pour le changement de catégorie, de format et d'année
  $(
    "#filter select[name='categoryfilter'], #filterf select[name='formatfilter'], #yearfilter select[name='yearfilter']"
  ).change(function () {
    selectedCategory = $("#filter select[name='categoryfilter']").val();
    selectedFormat = $("#filterf select[name='formatfilter']").val();
    selectedYear = $("#yearfilter select[name='yearfilter']").val();

    currentPage = 1;

    loadPosts();
  });
});
