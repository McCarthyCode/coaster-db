<?php
define(SALT_LENGTH, 8);

function generateHash($plaintext, $salt = null) {
    $salt =
        $salt ?
        substr($salt, 0, SALT_LENGTH) :
        substr(md5(uniqid(rand(), true)), 0, SALT_LENGTH);
    
    return $salt . sha1($salt . $plaintext);
}

?>

