<?php

http_response_code(503);
echo "<h1>503 Service Unavailable</h1><p>Website Kelurahan saat ini sedang dalam pemeliharaan sistem (Maintenance Server dan Database). Silakan kembali beberapa saat lagi.</p>";
exit;

$_ENV['APP_STORAGE'] = '/tmp';
putenv('APP_STORAGE=/tmp');
putenv('VIEW_COMPILED_PATH=/tmp');
putenv('APP_SERVICES_CACHE=/tmp/services.php');
putenv('APP_PACKAGES_CACHE=/tmp/packages.php');
putenv('APP_CONFIG_CACHE=/tmp/config.php');
putenv('APP_ROUTES_CACHE=/tmp/routes.php');
putenv('APP_EVENTS_CACHE=/tmp/events.php');

require __DIR__ . '/../public/index.php';

