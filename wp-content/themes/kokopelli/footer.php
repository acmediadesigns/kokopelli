<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?>

		</div><!-- #main -->
		<footer id="colophon" class="site-footer" role="contentinfo">
			<?php get_sidebar( 'main' ); ?>

			<div class="site-info">
        <div class="newsletter">
          <h6>Newsletter Signup:</h6>
          <table>
            <tr>
              <td><input type="email" name="email_address" placeholder="Email Address:" /></td>
              <td style="max-width: 60px;"><button type="button" id="newsletterSignup">Sign Up</button></td>
            </tr>
            <tr>
              <td colspan="2" style="padding-top: 0;">
                Stay up to date with all things Koko Pelli
              </td>
            </tr>
          </table>
        </div>

        <div class="logo">
          <img src="/wp-content/themes/kokopelli/images/footer-logo.png" alt="KOKO Pelli Enterprises" />
        </div>
			</div><!-- .site-info -->
		</footer><!-- #colophon -->
	</div><!-- #page -->

	<?php wp_footer(); ?>
</body>
</html>