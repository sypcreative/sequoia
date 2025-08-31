<?php
/**
 * Funciones para el recibir y enviar información por ajax
 *
 * @package sequoia
 */

// Enqueue javascript file
//add_action('wp_enqueue_scripts', 'sequoia_insert_custom_js');
function sequoia_insert_custom_js_2()
{
	wp_localize_script( 'all', 'sequoia_form',
		[
			'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
			'frmNonce' => wp_create_nonce( 'secret-key-form' ), // Crea un nonce (Token) seguro
		] );

}

/**
 * Validación nonce (Token) en WordPress, si el nonce proporcionado en el back coincide con el generado previamente en javascript.
 *
 * @param string $nonce
 * @param string $nonce_name
 *
 * @return void
 */
function sequoia_validate_nonce( $nonce, $nonce_name )
{
	if ( ! wp_verify_nonce( $nonce, $nonce_name ) ) {
		$res = [ 'status' => 0, 'message' => '✋ Error nonce validation' ];
		wp_send_json( $res );

	}
}

function sanitize_field( $value, $type = 'text' )
{
	// REQUERIDO PHP 8.0
	return match ( $type ) {
		'date', 'text', 'select', 'options', 'checkbox', 'radio', 'tel', 'hidden' => sanitize_text_field( $value ),
		'email' => sanitize_email( $value ),
		'textarea' => sanitize_textarea_field( $value ),
		'number' => is_numeric( $value ) ? intval( $value ) : 0,
		'url' => esc_url( $value ),
		default => $value,
	};
}








