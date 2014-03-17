php-hce_md5
===========

PHP Hash Chaining Encryption using MD5

This package implements a Hash Chaining method of encryption using MD5 for the hash algorithm.  It is copied from the perl module <em>Crypt::HCE_MD5</em>.  By combining a secret digest and a public digest, you can achieve encryption and decryption of plaintext.

<strong>WARNING:</strong>  Please be warned that significant advancements in MD5 hash cracking have occurred in the past decade, so MD5 is considered a fairly weak hash to use for any purpose that involves authentication.

Usage:
======

<pre>
require "hce_md5.class.php";

$md5 = new hce_md5($pubKey, $privKey);
/* Returns a binary blob 16 bytes long, but most apps/urls tend
 * to deal with the base64 encoded version, so convert it. */
$encrypted = $md5->hce_block_encrypt($plaintext);
$b64encrypted = base64_encode($encrypted);

$md5 = new hce_md5($pubKey, $privKey);
/* Assuming the incoming value is base64, convert it to a binary
 * blob and decrypt it.  The returned value will not be base64
 * encoded. */
$to_decrypt = base64_decode($b64encrypted);
$decrypted = $md5->hce_block_decrypt($to_decrypt);

// At this point, $decrypted is equal to original $plaintext
</pre>
