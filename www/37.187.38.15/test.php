<?php
file_put_contents('debug.log', "Test écriture debug.log\n", FILE_APPEND);
echo json_encode(['status' => 'ok']);
?>