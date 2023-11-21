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
  let isLoading = false; // Ajout de cette variable

  function loadPosts() {
    isLoading = true; // Définit la variable à true au début de la requête
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
      complete: function () {
        isLoading = false; // Réinitialise la variable à false à la fin de la requête
      },
    });
  }

  function loadMoreClickHandler() {
    // Ajout de la vérification pour éviter les requêtes multiples
    if (!isLoading) {
      currentPage++;
      loadPosts();
    }
  }

  // Gestion du changement de filtre
  $(
    "#filter select[name='categoryfilter'], #filterf select[name='formatfilter'], #yearfilter select[name='yearfilter']"
  ).change(function () {
    selectedCategory = $("#filter select[name='categoryfilter']").val();
    selectedFormat = $("#filterf select[name='formatfilter']").val();
    selectedYear = $("#yearfilter select[name='yearfilter']").val();

    currentPage = 1;

    loadPosts();
  });

  // Gestion du clic sur le bouton "load more"
  $("#load-more").on("click", loadMoreClickHandler);
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
