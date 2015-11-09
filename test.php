<?php
require('DNS/dnsProtocol.php');

$dns = new Metaregistrar\DNS\dnsProtocol();
$dns->setServer('ns1.metaregistrar.com');
$result = $dns->Query('www.metaregistrar.com','A');
/* @var $result Metaregistrar\DNS\dnsResponse */
foreach ($result->getResourceResults() as $resource) {
    if ($resource instanceof Metaregistrar\DNS\dnsAresult) {
        echo $resource->getDomain().' - '.$resource->getIpv4().' - '.$resource->getTtl()."\n";
    }
}
