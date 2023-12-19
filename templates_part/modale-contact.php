<div id="modal" class="modal">
    <div class="modal__content">
    <span class="cross-icon-modale">&times;</span>
        <img class="modal-img" src="http://projet11.local/wp-content/uploads/2023/10/Contact-header.png"
            alt="En-tête de la pop-up où il est écrit Contact">
        <?php
        // On insère le formulaire de contact
        echo do_shortcode('[contact-form-7 id="9a87b6c" title="Formulaire de contact"]');
        ?>
    </div>
</div>