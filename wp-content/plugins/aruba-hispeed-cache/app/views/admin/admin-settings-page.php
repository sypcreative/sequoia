<?php
/**
 * A telmplate frame
 * php version 5.6
 *
 * @category Wordpress-plugin
 * @package  Aruba-HiSpeed-Cache
 * @author   Aruba Developer <hispeedcache.developer@aruba.it>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     none
 */

global $pagenow;
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap ahsc-wrapper">
	<div id="ahsc-main">

		<h1 class="ahsc-settings-title">
			<?php \esc_html_e( 'Aruba HiSpeed Cache settings', 'aruba-hispeed-cache' ); ?>
		</h1>

		<h2 class="nav-tab-wrapper ahsc-settings-nav">
			<a class="nav-tab nav-tab-active" data-tab="#general"><?php \esc_html_e( 'General', 'aruba-hispeed-cache' ); ?></a>
			<?php if ( \ArubaSPA\HiSpeedCache\Helper\Functions\ahsc_has_debug() ) : ?>
				<a class="nav-tab" data-tab="#debug"><?php \esc_html_e( 'Debug', 'aruba-hispeed-cache' ); ?></a>
			<?php endif; ?>
		</h2>

		<form id="ahsc-settings-form" method="post" action="#" name="ahsc-settings-form" class="clearfix" encoding="multipart/form-data">
			<input type="hidden" name="ahs-settings-nonce" value="<?php echo esc_attr( \wp_create_nonce( 'ahs-save-settings-nonce' ) ); ?>" />
			<div id="general" class="ahsc-tab ahsc-options-wrapper">
				<?php require $this->container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_BASEPATH' ) . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'partials' . DIRECTORY_SEPARATOR . 'admin-tab-general.php'; ?>
			</div>
		</form>

		<?php if ( \ArubaSPA\HiSpeedCache\Helper\Functions\ahsc_has_debug() ) : ?>
			<div id="debug" class="ahsc-tab hidden">
				<?php require $this->container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_BASEPATH' ) . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'partials' . DIRECTORY_SEPARATOR . 'admin-tab-debug.php'; ?>
			</div>
		<?php endif; ?>

		<div class="ahsc-actions-wrapper">
			<table class="form-table ahst-table">
				<tr>
					<th></th>
					<td>
						<?php
						\submit_button( __( 'Save changes', 'aruba-hispeed-cache' ), 'primary', 'ahsc_settings_save', false, array( 'form' => 'ahsc-settings-form' ) );
						?>
						<a id="purgeall" href="#" class="button button-secondary"> <?php \esc_html_e( 'Purge entire cache', 'aruba-hispeed-cache' ); ?></a>
					</td>
				</tr>
			</table>
		</div>

	</div> <!-- End of #ahsc-main -->

	<div id="ahsc-side-bar">
	</div> <!-- End of #ahsc-main -->
	<div class="clear"></div>
</div> <!-- End of .wrap .ahsc-wrapper -->
