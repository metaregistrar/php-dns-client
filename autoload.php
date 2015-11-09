<?php
spl_autoload_register('autoload');


function autoload($className) {
    $className = str_replace('Metaregistrar\DNS\\','',$className);
    loadfile($className,'');
    loadfile($className,'dnsData');
    loadfile($className,'dnsResponses');
}

function loadfile($className, $directory) {
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $delimiter = '\\';
    } else {
        $delimiter = '/';
    }
    if (strlen($directory)>0) {
        $directory .= $delimiter;
    }
    $fileName = __DIR__ . $delimiter . 'DNS' . $delimiter . $directory . $className . '.php';
    //echo "Test autoload $fileName\n";
    if (is_readable($fileName)) {
        //echo "Autoloaded $fileName\n";
        require($fileName);
    }
}