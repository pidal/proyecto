<?php

if( ini_get('safe_mode') ){
   echo "safe mode";
}else{
   echo "NO safe mode";
}

phpinfo();
?>