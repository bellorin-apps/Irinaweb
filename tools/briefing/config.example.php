<?php
// Copiar a config.php en el servidor (no versionar). El token se genera al desplegar.
return [
	'token'    => 'REEMPLAZAR_POR_TOKEN_ALEATORIO',
	'notify'   => '',
	'data_dir' => __DIR__ . '/../../briefing-privado', // fuera de public_html en Hostinger
];
