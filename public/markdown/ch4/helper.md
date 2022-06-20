# helper.php

這個檔案的用意原本是，放置一些輔助使用的函數，不過因為主要程式碼不是使用`javascript`就是集中在`index.php`頁面，所以並沒有放太多函數在這裡。


## pr

直接在網站上輸出php的陣列

參數說明：
- `$var`：輸入陣列

```php
<?php
namespace Util;
/**
 * 直接在網站上印出陣列
 *
 * @param array $var 輸入陣列
 * @return void
 */
function pr(array $var) {
  return "<pre>" . print_r($var, true) . "</pre>";
}
```

範例輸出：

<pre>Array
(
    [0] =&gt; Array
        (
            [CopyNumber] =&gt; 4
            [BookNumber] =&gt; 4
            [SequenceNumber] =&gt; 9789869488471
            [Type] =&gt; 0
            [Price] =&gt; 580
        )

)
</pre>