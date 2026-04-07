<?php

$partialPath = __DIR__ . '/' . $sectionType . '.php';
if (!is_file($partialPath)) {
	http_response_code(404);
	echo 'Section partial not found.';
	return;
}

require $partialPath;