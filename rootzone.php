<?php
$ns = array();
$rootzone = file('https://www.internic.net/domain/root.zone',FILE_IGNORE_NEW_LINES);
foreach ($rootzone as $zone){
    $array = explode("\t",$zone);
    if (isset($array[4])) {
        if ($array[4]=='IN') {
            if ($array[5]=='NS') {
                $type = $array[5];
                $tld = strtolower($array[0]);
                if (strlen($tld)>1) {
                    if ($tld{strlen($tld)-1}=='.') {
                        $tld = substr($tld,0,strlen($tld)-1);
                    }
                    $content = $array[6];
                    $ns[$tld][]=$content;
                }


            }

        }
    }
}
echo 'private $ns = array('."\n";
$tldcount = count($ns);
$tldteller = 0;
foreach ($ns as $index=>$tld) {
    echo "'".$index."'=>array(";
    $count = count($tld);
    $teller = 0;
    foreach ($tld as $content) {
        echo "'".$content."'";
        $teller++;
        if ($teller < $count) {
            echo ',';
        }
    }
    echo ')';
    $tldteller++;
    if ($tldteller < $tldcount) {
        echo ',';
    }
    echo"\n";
}
echo ");";