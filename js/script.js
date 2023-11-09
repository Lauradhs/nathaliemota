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
    btnContact.addEventListener("click", toggleModale);
  });
  