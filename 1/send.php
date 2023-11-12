<?php
const UPLOAD_DIR = 'files/';

if (empty($_FILES)) {
    echo json_encode(array('type' => 'error', 'message' => 'Error: The file was not uploaded!'));
    return;
}

if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

$targetFile = UPLOAD_DIR . basename($_FILES['file']['name']);
move_uploaded_file($_FILES['file']['tmp_name'], $targetFile);

$fileContent = file_get_contents($targetFile);

if (!$_POST['separator']){
    echo json_encode(array('type' => 'ok', 'result' => [$fileContent . ' = ' . preg_match_all('/\d/', $fileContent)]));
    return;
}

$separator = $_POST['separator'];
$lines = explode($separator, $fileContent);

$res = [];
foreach ($lines as $line) {
    $digitCount = preg_match_all('/\d/', $line);
    $res[] = $line . ' = ' . $digitCount;
}


echo json_encode(array('type' => 'ok', 'result' => $res));
