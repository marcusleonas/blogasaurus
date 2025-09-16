		</main>


		<footer class="footer">
		    <div class="container">
		        <?php wp_nav_menu(array('menu' => 'Footer', 'container' => false,)); ?>

		        <div class="footer__content">
		            <p class="footer__content__copyright"><?php bloginfo("title"); ?> &copy; <?php echo date("Y"); ?> - All Rights Reserved</p>
		            <p class="footer__content__custom"><?= blogasaurus_get_option('footer_text') ?></p>
		        </div>
		    </div>
		</footer>

		<?php wp_footer(); ?>

		</body>

		</html>