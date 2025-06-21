</main>
    <!-- ========================== FOOTER ============================== -->
    <footer class="footer">
        <div class="footer__container container">
            <div class="footer__content grid">
                <div class="footer__group">
                    <a href="/ShelfControl" class="footer__logo">
                        <i class="ri-book-open-fill"></i> ShelfControl
                    </a>
                    <p class="footer__description">
                        Your all-in-one website for library management. Both local and nationwide.
                    </p>
                </div>

                <div class="footer__group">
                    <h3 class="footer__title">Quick Links</h3>
                    <ul class="footer__links">
                        <li><a href="/ShelfControl" class="footer__link">Home</a></li>
                        <li><a href="/ShelfControl/library" class="footer__link">Library</a></li>
                        <li><a href="/ShelfControl/toread" class="footer__link">To Read</a></li>
                        <li><a href="/ShelfControl/explore" class="footer__link">Explore</a></li>
                    </ul>
                </div>

                <div class="footer__group">
                    <h3 class="footer__title">Account</h3>
                    <ul class="footer__links">
                        <li><a href="/ShelfControl/profile" class="footer__link">Profile</a></li>
                        <li><a href="/ShelfControl/settings" class="footer__link">Settings</a></li>
                        <li><a href="/ShelfControl/logout" class="footer__link">Logout</a></li>
                    </ul>
                </div>

                <div class="footer__group">
                    <h3 class="footer__title">Support</h3>
                    <ul class="footer__links">
                        <li><a href="/ShelfControl/help" class="footer__link">Help Center</a></li>
                        <li><a href="/ShelfControl/contact" class="footer__link">Contact Us</a></li>
                        <li><a href="https://dar1acraciun.github.io/documentatie/" class="footer__link">About</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer__bottom">
                <p class="footer__copy">
                    © <?php echo date('Y'); ?> ShelfControl. All rights reserved.
                </p>
                <div class="footer__social">
                    <a href="#" class="footer__social-link"><i class="ri-facebook-fill"></i></a>
                    <a href="#" class="footer__social-link"><i class="ri-twitter-fill"></i></a>
                    <a href="#" class="footer__social-link"><i class="ri-instagram-fill"></i></a>
                    <a href="#" class="footer__social-link"><i class="ri-github-fill"></i></a>
                </div>
            </div>
        </div>
    </footer>
         
    <!-- ==================== COMMON JAVASCRIPT =========================== -->
    <script src="/ShelfControl/views/scripts/darkTheme.js"></script>
    <script src="/ShelfControl/views/scripts/header.js"></script>
    <script src="/ShelfControl/views/scripts/admin.js"></script>
    <script src="/ShelfControl/views/scripts/bookLoaders.js"></script>




    <!-- ==================== PAGE SPECIFIC JAVASCRIPT =========================== -->
    <?php if (isset($additionalScripts) && is_array($additionalScripts)): ?>
        <?php foreach ($additionalScripts as $script): ?>
            <script src="<?php echo $script; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>