<?php
namespace Metaregistrar\DNS {
    class dnsAresult extends dnsResult
    {
        /**
         * @var string
         */
        private $ipv4;

        function __construct($ip)
        {
            parent::__construct();
            $this->setIpv4($ip);
        }

        /**
         * @param string $ip
         */
        public function setIpv4($ip)
        {
            $this->ipv4 = $ip;
        }

        /**
         * @return string
         */
        public function getIpv4()
        {
            return $this->ipv4;
        }
    }

}