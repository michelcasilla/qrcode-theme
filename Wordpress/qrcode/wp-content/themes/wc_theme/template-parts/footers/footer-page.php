    <footer class="footer">
        <div class="container">
            <div class="footer-wrapper">
                <div class="footer-block">
                    <ul class="footer__nav">
                        <li><a href="/support"><?=__("AYUDA", "sp")?></a></li>
                        <li><a href="/privacy-policy"><?=__("POLITICA DE PRIVACIDAD", "sp")?></a></li>
                        <!-- <li><a href="#">SEGURIDAD</a></li> -->
                    </ul>
                    <span class="footer__copyright">
                        <?=__("World Crowns. Copyright © Todos los derechos reservados", "sp")?>
                    </span>
                </div>
                <div class="footer-block">
                    <div class="footer__logos">
                        <img src="<?=load_static_theme_file("/assets/images/logo-visa.svg")?>" alt="Visa">
                        <img src="<?=load_static_theme_file("/assets/images/logo-mc.svg")?>" alt="MasterCard">
                        <img src="<?=load_static_theme_file("/assets/images/logo-ae.svg")?>" alt="American Express">
                        <!-- <img src="<?=load_static_theme_file("/assets/images/logo-visa.svg")?>" alt="Visa"> -->
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <?php wp_footer(); ?>
</body>
<!-- <script>document.oncontextmenu = document.body.oncontextmenu = function() {return false;}</script> -->
</html>