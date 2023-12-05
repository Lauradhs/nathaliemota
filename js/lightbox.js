jQuery(document).ready(function ($) {
  const lightboxTrigger = $(".lightbox-trigger");
  const fullscreenIcon = $(".icon-fullscreen");
  const lightboxModal = $("#lightboxModal");
  const crossIconLightbox = $(".cross-icon-lightbox");
  const imgLightbox = $("#img-lightbox");
  const lightboxCategories = $("#lightbox-categories");
  const referencePhoto = $("#reference-photo");
  var currentIndex = 0;

  // Fonction pour mettre à jour la lightbox avec les données de l'image actuelle
  function updateLightbox(index) {
    // Vérifie si imagesData est défini et non vide
    if (imagesData && imagesData.length > 0) {
      // Vérifie si currentIndex est un index valide
      if (index >= 0 && index < imagesData.length) {
        var imageData = imagesData[index];
        imgLightbox.attr("src", imageData.imageUrl);
        referencePhoto.text(imageData.reference);
        lightboxCategories.text(imageData.category);
      } else {
        console.error("Index out of bounds: ", index);
      }
    } else {
      console.error("imagesData is undefined or empty.");
    }
  }

  // Ajoute les données pour chaque image au tableau, en excluant l'icône fullscreen
  var imagesData = lightboxTrigger
    .not(fullscreenIcon)
    .map(function (index) {
      var imageUrl, reference, category;

      if ($(this).hasClass("photo-interest")) {
        imageUrl = $(this).find("img").attr("src");
      } else {
        imageUrl = $(this).attr("src");
      }

      reference = $(this).data("reference");
      category = $(this).data("category");

      // Ajoute un attribut data-index avec l'index actuel
      $(this).attr("data-index", index);

      return {
        imageUrl: imageUrl,
        reference: reference,
        category: category,
      };
    })
    .get();

  // Fonction pour ajouter les données d'une nouvelle image au tableau imagesData
  function addImageData(imageElement) {
    var imageUrl, reference, category;

    if ($(imageElement).hasClass("photo-interest")) {
      imageUrl = $(imageElement).find("img").attr("src");
    } else {
      imageUrl = $(imageElement).attr("src");
    }

    reference = $(imageElement).data("reference");
    category = $(imageElement).data("category");

    // Ajoute un attribut data-index avec l'index actuel
    $(imageElement).attr("data-index", imagesData.length);

    // Ajoute les nouvelles données au tableau imagesData
    imagesData.push({
      imageUrl: imageUrl,
      reference: reference,
      category: category,
    });
  }

  // Exemple d'ajout de données pour une nouvelle image
  var newImageElement = $(".new-images");
  addImageData(newImageElement);

  // Appel de la fonction pour mettre à jour la lightbox après l'ajout d'une nouvelle image
  updateLightbox(imagesData.length - 1);

  $(document).ready(function () {
    $(document).on("click", ".lightbox-trigger", function (event) {
      // Récupère l'index de l'élément cliqué
      currentIndex = lightboxTrigger.index(this);

      // Si l'index n'est pas trouvé (élément ajouté dynamiquement), essayez une approche alternative
      if (currentIndex === -1) {
        currentIndex = lightboxTrigger.index($(this));
      }

      // Ajout de logs pour déboguer
      console.log("Clicked element:", this);
      console.log("Current index:", currentIndex);

      // Met à jour la lightbox avec les données de l'image actuelle
      updateLightbox(currentIndex);

      // Affiche la lightbox
      lightboxModal.addClass("visible");
    });

    // Utilisez également la délégation d'événements pour gérer les clics sur ".icon-fullscreen"
    $(document).on("click", ".icon-fullscreen", function (event) {
      event.preventDefault();
      event.stopPropagation();

      // Récupère l'index de l'élément cliqué
      currentIndex = lightboxTrigger.index(
        $(this).closest(".photo-item").find(".lightbox-trigger")
      );

      // Met à jour la lightbox avec les données de l'image actuelle
      updateLightbox(currentIndex);

      // Affiche la lightbox
      lightboxModal.addClass("visible");
    });
  });

  // Écouteur d'événements pour fermer la lightbox
  crossIconLightbox.on("click", function () {
    lightboxModal.removeClass("visible");
  });

  // Écouteur d'événements pour la navigation précédente
  $("#lightbox-prev-link").on("click", function (event) {
    event.preventDefault();
    currentIndex = (currentIndex - 1 + imagesData.length) % imagesData.length;
    updateLightbox(currentIndex);
  });

  // Écouteur d'événements pour la navigation suivante
  $("#lightbox-next-link").on("click", function (event) {
    event.preventDefault();
    currentIndex = (currentIndex + 1) % imagesData.length;
    updateLightbox(currentIndex);
  });
});

/* Load More */

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

        // Ajoute le au conteneur
        if (currentPage === 1) {
          // Si c'est la première page, remplace le contenu existant
          $(".publication-list").html($content);
        } else {
          // Sinon, ajoute le contenu à la fin de la liste existante
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

  // Gestion du changement de catégorie
  $("#filter select[name='categoryfilter']").change(function () {
    selectedCategory = $(this).val();
    currentPage = 1;
    loadPosts();
  });

  // Gestion du changement de format
  $("#filterf select[name='formatfilter']").change(function () {
    selectedFormat = $(this).val();
    currentPage = 1;
    loadPosts();
  });

  // Gestion du changement d'année
  $("#yearfilter select[name='yearfilter']").change(function () {
    selectedYear = $(this).val();
    currentPage = 1;
    loadPosts();
  });

  // Gestion du clic sur le bouton "load more"
  $("#load-more").on("click", loadMoreClickHandler);
});