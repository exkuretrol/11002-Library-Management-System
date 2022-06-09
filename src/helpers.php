<?php
namespace Util;

function pr($var) {
  return "<pre>" . print_r($var, true) . "</pre>";
}
