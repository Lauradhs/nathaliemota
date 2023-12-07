jQuery(document).ready(function ($) {
  const lightboxTrigger = $(".lightbox-trigger");
  const fullscreenIcon = $(".icon-fullscreen");
  const lightboxModal = $("#lightboxModal");
  const crossIconLightbox = $(".cross-icon-lightbox");
  const imgLightbox = $("#img-lightbox");
  const lightboxCategories = $("#lightbox-categories");
  const referencePhoto = $("#reference-photo");
  var currentIndex = 0;

  // Tableau pour stocker les données des images
  var imagesData = [];
  var totalImages = 0; // Nouvelle variable

  function updateLightbox(index) {
    console.log("Update Lightbox - Index:", index);
    console.log("Update Lightbox - imagesData:", imagesData);
    if (imagesData && imagesData.length > 0) {
      if (index >= 0 && index < imagesData.length) {
        var imageData = imagesData[index];
        var urlImage = imageData.imageUrl;
        imgLightbox.attr("src", urlImage);
        referencePhoto.text(imageData.reference);
        lightboxCategories.text(imageData.category);
        $("#photo-id-lightbox").text(imageData.photoId);
      } else if (index === -1) {
        // Gestion spéciale lorsque l'index est -1 (hors limites)
        var firstImageData = imagesData[0];
        imgLightbox.attr("src", firstImageData.imageUrl);
        referencePhoto.text(firstImageData.reference);
        lightboxCategories.text(firstImageData.category);
        $("#photo-id-lightbox").text(firstImageData.photoId);
      }
    }
  }

  // Ajoute les données pour chaque image au tableau, en excluant l'icône fullscreen
  lightboxTrigger.not(fullscreenIcon).map(function (index) {
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

    imagesData.push({
      imageUrl: imageUrl,
      reference: reference,
      category: category,
    });
  });

  // Fonction pour ajouter les données d'une collection d'images au tableau imagesData
  function addImageData(imageElements) {
    imageElements.each(function () {
      var imageUrl, reference, category, photoId;

      if ($(this).hasClass("photo-interest")) {
        imageUrl = $(this).find("img").attr("src");
      } else {
        imageUrl = $(this).attr("src");
      }

      reference = $(this).data("reference");
      category = $(this).data("category");
      photoId = $(this).data("photo");

      // Ajoute un identifiant unique à chaque élément
      var uniqueId = "lightbox_" + totalImages;

      // Ajoute la classe dynamic-image aux images dynamiques
      $(this).addClass("dynamic-image");

      // Ajoute les nouvelles données au tableau imagesData
      imagesData.push({
        id: uniqueId,
        imageUrl: imageUrl,
        reference: reference,
        category: category,
        photoId: photoId,
        index: totalImages, // Utilisez totalImages comme index
      });

      totalImages++; // Incrémente le nombre total d'images
    });
  }

  // Exemple d'ajout de données pour une nouvelle image
  var newImageElements = $(".new-images .lightbox-trigger");
  addImageData(newImageElements);

  // Appel de la fonction pour mettre à jour la lightbox après l'ajout d'une nouvelle image
  updateLightbox(imagesData.length - 1);

  // Utilisez un élément parent statique pour déléguer l'événement click
  $(document).on("click", ".photo-item .dynamic-image", function (event) {
    // Récupère l'index à partir de l'attribut data-index
    var dataIndex = $(this).data("index");
    console.log("Dynamic Image Click - dataIndex:", dataIndex);

    // Vérifie que l'index est défini et dans les limites du tableau
    if (
      dataIndex !== undefined &&
      dataIndex >= 0 &&
      dataIndex < imagesData.length
    ) {
      // Utilisez l'index stocké dans l'attribut data-index
      var currentIndex = dataIndex;

      // Met à jour la lightbox avec les données de l'image actuelle
      updateLightbox(currentIndex);

      // Affiche la lightbox
      lightboxModal.addClass("visible");
    }
  });

  // Utilisez également la délégation d'événements pour gérer les clics sur ".icon-fullscreen"
  $(document).on("click", ".icon-fullscreen", function (event) {
    event.preventDefault();
    event.stopPropagation();

    // Trouve l'élément .photo-item parent
    var photoItem = $(this).closest(".photo-item");

    // Trouve l'élément .lightbox-trigger à l'intérieur de .photo-item
    var lightboxTrigger = photoItem.find(".lightbox-trigger");

    // Récupère l'index de l'élément .lightbox-trigger dans l'ensemble des .lightbox-trigger
    currentIndex = $(".lightbox-trigger").index(lightboxTrigger);

    // Met à jour la lightbox avec les données de l'image actuelle
    updateLightbox(currentIndex);

    // Affiche la lightbox
    lightboxModal.addClass("visible");
  });

  crossIconLightbox.on("click", function () {
    lightboxModal.removeClass("visible");
  });

  // Écouteur d'événements pour la navigation précédente
  $("#lightbox-prev-link").on("click", function (event) {
    event.preventDefault();
    currentIndex = (currentIndex - 1 + imagesData.length) % imagesData.length;

    // Ajoutez cette vérification pour éviter un index négatif
    if (currentIndex < 0) {
      currentIndex = imagesData.length - 1;
    }

    updateLightbox(currentIndex);
  });

  // Écouteur d'événements pour la navigation suivante
  $("#lightbox-next-link").on("click", function (event) {
    event.preventDefault();
    currentIndex = (currentIndex + 1) % imagesData.length;
    updateLightbox(currentIndex);
  });

  /* Load More */

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
        // Ajout de données pour une nouvelle image
        var newImageElements = $(".new-images .lightbox-trigger");
        addImageData(newImageElements);

        // Appel de la fonction pour mettre à jour la lightbox après l'ajout d'une nouvelle image
        updateLightbox(imagesData.length - 1);
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
    imagesData = [];
    loadPosts();
  });

  // Gestion du changement de format
  $("#filterf select[name='formatfilter']").change(function () {
    selectedFormat = $(this).val();
    currentPage = 1;
    imagesData = [];
    loadPosts();
  });

  // Gestion du changement d'année
  $("#yearfilter select[name='yearfilter']").change(function () {
    selectedYear = $(this).val();
    currentPage = 1;
    imagesData = [];
    loadPosts();
  });

  // Gestion du clic sur le bouton "load more"
  $("#load-more").on("click", loadMoreClickHandler);
});
