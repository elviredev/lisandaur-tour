<footer>
    <div class="footer__socials">
        <a href="https://youtube.com" target="_blank"><i class="uil uil-youtube"></i></a>
        <a href="https://facebook.com" target="_blank"><i class="uil uil-facebook-f"></i></a>
        <a href="https://instagram.com" target="_blank"><i class="uil uil-instagram-alt"></i></a>
        <a href="https://linkedin.com" target="_blank"><i class="uil uil-linkedin"></i></a>
        <a href="https://twitter.com" target="_blank"><i class="uil uil-twitter"></i></a>
    </div>

    <div class="container footer__container">
        <article>
            <h4>Pays visités</h4>
            <ul>
                <li><a href="<?= ROOT_URL ?>country-posts.php">Japon</a></li>
                <li><a href="<?= ROOT_URL ?>country-posts.php">Italie</a></li>
                <li><a href="<?= ROOT_URL ?>country-posts.php">Espagne</a></li>
                <li><a href="<?= ROOT_URL ?>country-posts.php">Corée du Sud</a></li>
                <li><a href="<?= ROOT_URL ?>country-posts.php">Etats-Unis</a></li>
                <li><a href="<?= ROOT_URL ?>country-posts.php">Islande</a></li>
            </ul>
        </article>
        <article>
            <h4>Pays à visiter</h4>
            <ul>
                <li>
                    <a href="https://www.diplomatie.gouv.fr/fr/dossiers-pays/perou/" target="_blank">Pérou</a>
                </li>
                <li>
                    <a href="https://www.diplomatie.gouv.fr/fr/dossiers-pays/indonesie/" target="_blank">Indonésie</a>
                </li>
                <li>
                    <a href="https://www.diplomatie.gouv.fr/fr/dossiers-pays/canada/" target="_blank">Canada</a>
                </li>
                <li>
                    <a href="https://www.diplomatie.gouv.fr/fr/dossiers-pays/irlande/" target="_blank">Irlande</a>
                </li>
                <li>
                    <a href="https://www.diplomatie.gouv.fr/fr/dossiers-pays/royaume-uni/" target="_blank">Ecosse</a>
                </li>
                <li>
                    <a href="https://www.diplomatie.gouv.fr/fr/dossiers-pays/singapour/" target="_blank">Singapour</a>
                </li>
            </ul>
        </article>
        <article>
            <h4>Blog</h4>
            <ul>
                <li><a href="<?= ROOT_URL ?>blog.php">Recherche</a></li>
                <li><a href="<?= ROOT_URL ?>index.php">Les derniers</a></li>
                <li><a href="">Autres pays</a></li>
            </ul>
        </article>
        <article>
            <h4>Permaliens</h4>
            <ul>
                <li><a href="<?= ROOT_URL ?>index.php">Accueil</a></li>
                <li><a href="<?= ROOT_URL ?>blog.php">Blog</a></li>
                <li><a href="<?= ROOT_URL ?>about.php">À propos</a></li>
                <li><a href="<?= ROOT_URL ?>contact.php">Contact</a></li>
                <li><a href="<?= ROOT_URL ?>dashboard.php">Gestion</a></li>
            </ul>
        </article>
    </div>

    <div class="footer__copyright">
        <small>Copyright &copy; 2025 | Elviredev | Tous droits réservés</small>
    </div>
</footer>

<!-- Modale utilisée pour confirmer la suppression d'un utilisateur -->
<div id="confirm_modal" class="modal" style="display: none;">
  <div class="modal__content">
    <p id="modal_message">Êtes-vous sûr de vouloir supprimer cet utilisateur ?</p>
    <div class="modal__actions">
      <button id="confirm_delete_btn" class="btn danger">Oui</button>
      <button id="cancel_delete_btn" class="btn edit">Non</button>
    </div>
  </div>
</div>


<script src="<?= ROOT_URL ?>js/fslightbox.js"></script>
<!-- Version cache-busting forcer le rechargement du fichier JS quand on fait des modifs -->
<script src="<?= ROOT_URL ?>js/main.js?v=<?= filemtime(__DIR__ . '/../js/main.js') ?>"></script>
</body>
</html>