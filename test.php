<?php
require('autoload.php');

$dns = new Metaregistrar\DNS\dnsProtocol();
$dns->setServer('ns1.google.com');
$result = $dns->Query('google.com','TXT');
/* @var $result Metaregistrar\DNS\dnsResponse */
foreach ($result->getResourceResults() as $resource) {
    //var_dump($resource);
    if ($resource instanceof Metaregistrar\DNS\dnsAresult) {
        echo $resource->getDomain().' - '.$resource->getIpv4().' - '.$resource->getTtl()."\n";
    }
    if ($resource instanceof Metaregistrar\DNS\dnsAAAAresult) {
        echo $resource->getDomain().' - '.$resource->getIpv6().' - '.$resource->getTtl()."\n";
    }
    if ($resource instanceof Metaregistrar\DNS\dnsTXTresult) {
        echo $resource->getDomain().' - '.$resource->getRecord().' - '.$resource->getTtl()."\n";
    }
}
