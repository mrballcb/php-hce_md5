<?php

/* Copyright 2014, Todd Lyons
 * License: GPLv2
 * Author: Todd Lyons <tlyons@ivenue.com>
 */

/*
 * Class to provide HCE (Hash Chaining Encryption) using MD5
 */
class hce_md5
{

  private $RKEY;
  private $SKEY;

  public function __construct($SKEY, $RKEY='')
  {
    $this->SKEY = $SKEY;
    $this->RKEY = $RKEY;
  }

  /* Returns an array */
  private function _new_key($str)
  {
    $digest = $this->_hex2raw(md5($this->SKEY . $str));
    return $digest;

  }

  /* Takes the string output of md5() and converts it to an
   * array of decimal values. */
  private function _hex2raw( $str ){
    $chunks = str_split($str, 2);
    $ans = array();
    for( $i = 0; $i < sizeof($chunks); $i++ ) {
      $ans[$i] = hexdec($chunks[$i]);
    }
    return $ans;
  }

  /* Used only for debugging, does reverse of _hex2raw */
  private function _arr2hex( $arr ){
    $data_size = count($arr);
    $ans = '';
    for ($i=0; $i < $data_size; $i++) {
      $ans .= dechex($arr[$i]);
    }
    return $ans;
  }

  public function hce_block_encrypt($str)
  {
    $ans = array();
    $data = array();
    $eblock = array();
    $ret = '';
    $data = unpack('C*', $str);
    $e_block = $this->_new_key($this->RKEY);
    $data_size = count($data);
    for($i=0; $i < $data_size; $i++) {
      $mod = $i %16;
      if (($mod == 0) && ($i > 15)) {
        $tmp = '';
        for ($j=($i-16); $j < $i; $j++) {
          $tmp .= pack('C',$ans[$j]);
        }
        $e_block = $this->_new_key( $tmp );
      }
      /* Pack function can't take an array, so must process one
       * integer at a time */
      /* data array starts at 1, others at 0 */
      $ans[$i] = $e_block[$mod] ^ $data[$i+1];
      $ret .= pack('C*', $ans[$i]);
    }
    return $ret;
  }

  public function hce_block_decrypt($str)
  {
    $data = array();
    $eblock = array();
    $ret = '';
    $data = unpack('C*', $str);
    $e_block = $this->_new_key($this->RKEY);
    $data_size = count($data);
    for($i=0; $i < $data_size; $i++) {
      $mod = $i %16;
      if (($mod == 0) && ($i > 15)) {
        $tmp = '';
        for ($j=($i-16); $j < $i; $j++) {
          $tmp .= pack('C*',$data[$j+1]);
        }
        $e_block = $this->_new_key( $tmp );
      }
      /* Pack function can't take an array, so must process one
       * integer at a time */
      /* data array starts at 1, others at 0 */
      $ans[$i] = $e_block[$mod] ^ $data[$i+1];
      $ret .= pack('C*', $ans[$i]);
    }
    return $ret;
  }
} /* End of hce_md5 class */

?>
