<?php
namespace Metaregistrar\DNS {
    class dnsAAAAresult extends dnsResult
    {
        /**
         * @var string
         */
        private $ipv6long;
        /**
         * @var string
         */
        private $ipv6short;

        function __construct($ip)
        {
            parent::__construct();
            $this->setIpv6long($ip);
            $ip = str_replace('0000','',$ip);
            while (strpos($ip,':::')!==false) {
                $ip = str_replace(':::','::',$ip);
            }
            while (strpos($ip,':0')!==false) {
                $ip = str_replace(':0',':',$ip);
            }
            $this->setIpv6short($ip);
        }

        /**
         * @param false $long Get long or short version
         * @return string
         */
        public function getIpv6($long = false) {
            if ($long) {
                return $this->getIpv6long();
            } else {
                return $this->getIpv6short();
            }
        }

        /**
         * @param string $ip
         */
        public function setIpv6long($ip)
        {
            $this->ipv6long = $ip;
        }

        /**
         * @return string
         */
        public function getIpv6long()
        {
            return $this->ipv6long;
        }

        /**
         * @param string $ip
         */
        public function setIpv6short($ip)
        {
            $this->ipv6short = $ip;
        }

        /**
         * @return string
         */
        public function getIpv6short()
        {
            return $this->ipv6short;
        }
    }

}