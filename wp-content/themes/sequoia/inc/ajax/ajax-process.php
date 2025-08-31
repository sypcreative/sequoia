<?php
/**
 * Funciones para el recibir y enviar información por ajax
 *
 * @package sequoia
 */

// Process ajax request
add_action('wp_ajax_nopriv_sequoia_process_contact_form', 'sequoia_process_contact_form');
add_action('wp_ajax_sequoia_process_contact_form', 'sequoia_process_contact_form');
function sequoia_process_contact_form() {
	sequoia_validate_nonce($_POST['nonce'], 'secret-key-form');

	// Cotejar si tiene la clave secreta para dar seguridad
	if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'secret-key-form')) {
		wp_send_json_error(['status' => 0, 'message' => 'Nonce no válido']);
	}

	// Tipo de formulario, indicado en data-formulario en la clase form
	$form = $_POST['formulario'] ?? '';

	// Sanitizamos los datos del formulario y guardamos los datos en la variable $sanitize_data
	$err           = [
		'status'  => 0,
		'message' => 'Faltan campos en los inputs, añádelos en todos los inputs del formulario',
		$_POST['dataForm'],
	];
	$sanitize_data = $_POST['dataForm'];

	// Según el tipo de formulario indicado en el data-formulario del form los manejamos aquí
	switch ($form) {

		case 'contact':
			enviar_crm($sanitize_data);
			break;


		default:
			$test = 'test';
			enviar_crm($sanitize_data);
			break;
	}

}

/**
 * Función para enviar datos al CRM.
 *
 * @param array $data Datos saneados del formulario.
 */

function enviar_crm($data) {
	$client_id     = "1000.AU1BTHZ26DO23RFV5DG75Q9JIFXJRH";
	$client_secret = "6f635ddb4e14bcdbd9853d23a4902ae4e76ad972ba";
	$refresh_token = '1000.a0ab449a482b81d4447fe199d8c75dd5.58f5802d37ee533af3a7a3f1838b8496';

	$token = refresh_access_token($client_id, $client_secret, $refresh_token);


	// Inicializa una nueva sesión cURL
	$ch = curl_init();

	// Configura las opciones de cURL
	curl_setopt_array($ch, array(
		CURLOPT_URL            => 'https://www.zohoapis.com/crm/v2/Leads',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING       => '',
		CURLOPT_MAXREDIRS      => 10,
		CURLOPT_TIMEOUT        => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST  => 'POST',
		CURLOPT_POSTFIELDS     => http_build_query($data),
		CURLOPT_HTTPHEADER     => array(
			'Authorization: Zoho-oauthtoken ' . $token,
			'Content-Type: application/json',
			'Cookie: _zcsr_tmp=5b9179e3-af24-40f5-b647-23ea358353ce; crmcsr=5b9179e3-af24-40f5-b647-23ea358353ce; zalb_1a99390653=a6ea46de4ea8437cf719118464995a88'
		),
	));

	// Ejecuta la solicitud cURL y obtiene la respuesta
	$response = curl_exec($ch);
	$info     = curl_getinfo($ch);
	// Maneja errores de cURL
	if ($response === false) {
		$error_message = curl_error($ch);
		curl_close($ch);
		wp_send_json(['status' => 0, 'message' => 'Hubo un error en el envío al CRM: ' . $error_message]);
	}
	else {
		$decoded_response = json_decode($response, true);

		curl_close($ch);
		// Verifica si hubo un error al decodificar el JSON
		if (json_last_error() !== JSON_ERROR_NONE) {
			$error_message = json_last_error_msg();
			wp_send_json(['status' => 0, 'message' => 'Error al decodificar la respuesta del CRM: ' . $error_message, $data]);
		}
		if (isset($decoded_response['status']) && $decoded_response['status'] === 'error') {
			wp_send_json(['status' => 0, 'data' => http_build_query($data), 'decoded_response' => $decoded_response, '$info' => $info]);
			//wp_send_json(['status' => 0, 'message' => 'Hubo un error en el envío al CRM: ' . $decoded_response['message'], $data, $decoded_response, $ch]);
		}
		else {
			wp_send_json(['status' => 1, 'message' => 'Datos enviados correctamente al CRM', 'response' => $decoded_response, $data]);
		}
	}
}

function refresh_access_token($client_id, $client_secret, $refresh_token) {
	$url     = "https://accounts.zoho.com/oauth/v2/token";
	$payload = [
		"grant_type"    => "refresh_token",
		"client_id"     => $client_id,
		"client_secret" => $client_secret,
		"refresh_token" => $refresh_token
	];

	// Inicializa cURL
	$ch = curl_init($url);

	// Configura las opciones de cURL
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// Ejecuta la solicitud y obtiene la respuesta
	$response = curl_exec($ch);

	// Cierra la sesión de cURL
	curl_close($ch);

	// Convierte la respuesta JSON en un array asociativo
	$response_data = json_decode($response, true);

	return $response_data['access_token'];
}