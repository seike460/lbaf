<?php
$data   = file_get_contents('php://input');
if ($data === '') {return;};
$storage = new lbaf_storage();
$storage->store($data);
return 'ok';
