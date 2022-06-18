<?php
namespace Util;
/**
 * 直接在網站上印出向量
 *
 * @param array $var 輸入向量
 * @return void
 */
function pr(array $var) {
  return "<pre>" . print_r($var, true) . "</pre>";
}
