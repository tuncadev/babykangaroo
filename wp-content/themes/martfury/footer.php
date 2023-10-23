<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Martfury
 */
?>

<?php do_action( 'martfury_before_site_content_close' ); ?>
</div><!-- #content -->
<?php do_action( 'martfury_before_footer' ) ?>
<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
	?>
    <footer id="colophon" class="site-footer">
		<?php do_action( 'martfury_footer' ) ?>
    </footer><!-- #colophon -->
	<?php do_action( 'martfury_after_footer' ) ?>
<?php } ?>
</div><!-- #page -->

<?php wp_footer(); ?>


</body>
</html>
