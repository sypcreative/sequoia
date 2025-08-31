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

?>

<?php foreach ( $this->fields['sections']['general'] as $sections_key => $sections ) : ?>

	<?php if ( ! isset( $sections['ids'] ) ) : ?>
		<h2 class="ahsc-title <?php echo esc_html( $sections['class'] ); ?>"><?php echo esc_html( $sections['title'] ); ?></h2>
		<?php continue; ?>
	<?php endif; //phpcs:ignore Squiz.PHP.NonExecutableCode.Unreachable ?>

	<table class="form-table ahsc-table-<?php echo esc_html( $sections_key ); ?> <?php echo esc_html( $sections['class'] ); ?>">
		<tbody>
			<tr class="<?php echo esc_html( $sections_key ); ?>">
				<th scope="row">
					<?php echo esc_html( $sections['name'] ); ?>
					<small><?php echo ( isset( $sections['legend'] ) ) ? esc_html( $sections['legend'] ) : ''; ?></small>
				</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span><?php echo esc_html( $sections['name'] ); ?></span>
						</legend>
						<?php foreach ( $sections['ids'] as $filedkey ) : ?>
							<label
							for="<?php echo esc_html( $this->fields[ $filedkey ]['id'] ); ?>">
								<input
								type="<?php echo esc_html( $this->fields[ $filedkey ]['type'] ); ?>"
								value="1"
								name="<?php echo esc_html( $this->fields[ $filedkey ]['id'] ); ?>"
								id="<?php echo esc_html( $this->fields[ $filedkey ]['id'] ); ?>"
								<?php echo esc_html( $this->fields[ $filedkey ]['checked'] ); ?>
								/>
								<?php
								// phpcs:disable
								_e( $this->fields[ $filedkey ]['name'] );
								// phpcs:enable
								?>
							</label>
						<?php endforeach; ?>
					</fieldset>
				</td>
			</tr>
		</tbody>
	</table>
<?php endforeach; ?>

<?php foreach ( $this->fields['sections']['cache_warmer'] as $sections_key => $sections ) : ?>

	<?php if ( ! isset( $sections['ids'] ) ) : ?>
		<h2 class="ahsc-title <?php echo esc_html( $sections['class'] ); ?>"><?php echo esc_html( $sections['title'] ); ?></h2>
		<?php continue; ?>
	<?php endif; //phpcs:ignore Squiz.PHP.NonExecutableCode.Unreachable ?>

	<table class="form-table ahsc-table-<?php echo esc_html( $sections_key ); ?> <?php echo esc_html( $sections['class'] ); ?>">
		<tbody>
			<tr class="<?php echo esc_html( $sections_key ); ?>">
				<th scope="row">
					<?php echo esc_html( $sections['name'] ); ?>
				</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span><?php echo esc_html( $sections['name'] ); ?></span>
						</legend>
						<?php foreach ( $sections['ids'] as $filedkey ) : ?>
							<label
							for="<?php echo esc_html( $this->fields[ $filedkey ]['id'] ); ?>">
								<input
								type="<?php echo esc_html( $this->fields[ $filedkey ]['type'] ); ?>"
								value="1"
								name="<?php echo esc_html( $this->fields[ $filedkey ]['id'] ); ?>"
								id="<?php echo esc_html( $this->fields[ $filedkey ]['id'] ); ?>"
								<?php echo esc_html( $this->fields[ $filedkey ]['checked'] ); ?>
								/>
								<?php
								// phpcs:disable
								_e( $this->fields[ $filedkey ]['name'] );
								// phpcs:enable
								?>
							</label>
							<small><?php echo ( isset( $this->fields[ $filedkey ]['legend'] ) ) ? _e( $this->fields[ $filedkey ]['legend'] ) : ''; ?></small>
						<?php endforeach; ?>
					</fieldset>
				</td>
			</tr>
		</tbody>
	</table>
<?php endforeach; ?>
