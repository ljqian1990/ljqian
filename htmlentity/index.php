<?php
$str = '&lt;a href=&#39;tel:123456&#39;&gt;123456&lt;/a&gt;';
//$str = '&lt;a href=&quot;tel:123456&quot;&gt;123456&lt;/a&gt;';
$str = '';
echo html_entity_decode($str, ENT_QUOTES);