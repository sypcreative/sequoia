<?php

// Enqueue javascript file
add_action( 'wp_enqueue_scripts', 'sequoia_insert_custom_js_ajax' );
function sequoia_insert_custom_js_ajax()
{
	wp_localize_script(
		'all',
		'ajax_forms',
		[
			'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
			'frmNonce' => wp_create_nonce( 'secret-key-form' )
		]
	);
}

// Create the contact form
add_filter( 'the_content', 'sequoia_show_contact_ajax_form' );
function sequoia_show_contact_ajax_form( $content )
{
	ob_start();
	$htm_form = ob_get_contents();
	ob_end_clean();

	return $content . $htm_form;
}

/**
 * @link https://angelcruz.dev/post/como-usar-de-forma-sencilla-mailchimp-en-wordpress
 * @link https://rudrastyh.com/wordpress/using-mailchimp-api.html
 */
// sequoia_mailchimp();
function sequoia_mailchimp()
{

	if ( ! empty( $_POST ) ) {

		$email             = $_POST['email'];
		$nombre            = $_POST['nombre'] ?? '';
		$tipo              = $_POST['tipo'] ?? '';
		$api               = 'eb664283d682fbb4b1c3c1584258a2c9-us13_';
		$list_id           = 'e5895892ea_';
		$subscription_page = $_POST['pagina'];
		// URL para suscribir al usuario
		$subscribe_url = 'https://' . substr( $api, strpos( $api, '-' ) + 1 ) . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . md5( strtolower( $email ) );

		// Datos del campo personalizado
		$merge_fields = array(
			'PAGINA' => $subscription_page,
			'FNAME'  => $nombre,
			'TIPO'   => $tipo
		);

		// Datos de la suscripción
		$subscription_data = array(
			'email_address' => $email,
			'status'        => 'subscribed',
			'merge_fields'  => $merge_fields
		);

		// Realizar la solicitud para suscribir al usuario
		$response = wp_remote_request(
			$subscribe_url,
			array(
				'method'  => 'PUT',
				'headers' => array(
					'Authorization' => 'Basic ' . base64_encode( 'user:' . $api )
				),
				'body'    => json_encode( $subscription_data )
			)
		);

		// if('OK' === wp_remote_retrieve_response_message($response)) {
		//     echo 'The user has been successfully subscribed.';
		// }
	}
}