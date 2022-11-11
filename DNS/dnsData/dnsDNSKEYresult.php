<?php
namespace Metaregistrar\DNS {
    class dnsDNSKEYresult extends dnsResult
    {
        /**
         * @var string
         */
        private $flags;
        /**
         * @var string
         */
        private $algorithm;
        /**
         * @var string
         */
        private $protocol;
        /**
         * @var string
         */
        private $sep;
        /**
         * @var bool
         */
        private $zonekey;
        /**
         * @var string
         */
        private $keylength;
        /**
         * @var string
         */
        private $publickey;
        /**
         * @var string
         */
        private $publickeybase64;
        /**
         * @var string
         */
        private $keytag;

        public function __construct($flags, $protocol, $algorithm, $pubkey)
        {
            parent::__construct();
            //$this->setKeytag($flags, $protocol, $algorithm, $pubkey);
            $this->setKeylength(strlen($pubkey));
            $this->setFlags($flags);
            $this->setProtocol($protocol);
            $this->setAlgorithm($algorithm);
            $this->setPublicKey($pubkey);
            $this->setPublicKeyBase64(base64_encode($pubkey));
            $this->sep = false;
            $this->zonekey = false;
        }

        /*
        private function setKeytag($flags,$protocol,$algorithm, $pubkey)
        {
            $sum=0;
            $wire = pack("ncc", $flags, $protocol, $algorithm) . $pubkey;
            if ($algorithm == 1)
            {
                $this->keytag = 0xffff & unpack("n", substr($wire,-3,2)) ;
            }
            else
            {
                $sum=0;
                for($i = 0; $i < strlen($wire); $i++)
                {
                    $a = unpack("C", substr($wire,$i,1));
                    $sum += ($i & 1) ? $a[1] : $a[1] << 8;
                }
            $this->keytag = 0xffff & ($sum + ($sum >> 16));
            }
        }
        */
        /**
         * @param string $keytag
         */
        public function setKeytag($keytag)
        {
            $this->keytag = $keytag;
        }

        /**
         * @return string
         */
        public function getKeytag()
        {
            return $this->keytag;
        }

        /**
         * @param string $keylength
         */
        public function setKeylength($keylength)
        {
            $this->keylength = $keylength;
        }

        /**
         * @return string
         */
        public function getKeylength()
        {
            return $this->keylength;
        }

        /**
         * @param string $flags
         */
        public function setFlags($flags)
        {
            $this->flags = $flags;
        }

        /**
         * @return string
         */
        public function getFlags()
        {
            return $this->flags;
        }

        /**
         * @param string $algorithm
         */
        public function setAlgorithm($algorithm)
        {
            $this->algorithm = $algorithm;
        }

        /**
         * @return string
         */
        public function getAlgorithm()
        {
            return $this->algorithm;
        }

        /**
         * @param string $protocol
         */
        public function setProtocol($protocol)
        {
            $this->protocol = $protocol;
        }

        /**
         * @return string
         */
        public function getProtocol()
        {
            return $this->protocol;
        }

        /**
         * @param bool $bool
         */
        public function setZoneKey($bool)
        {
            $this->zonekey = $bool;
        }

        /**
         * @return bool
         */
        public function getZoneKey()
        {
            return $this->zonekey;
        }

        public function setSep($bool)
        {
            $this->sep = $bool;
        }

        /**
         * @return string
         */
        public function getSep()
        {
            return $this->sep;
        }

        public function setPublicKey($key)
        {
            $this->publickey = $key;
        }

        /**
         * @return string
         */
        public function getPublicKey()
        {
            return $this->publickey;
        }

        /**
         * @param string $key
         */
        public function setPublicKeyBase64($key)
        {
            $this->publickeybase64 = $key;
        }

        /**
         * @return string
         */
        public function getPublicKeyBase64()
        {
            return $this->publickeybase64;
        }
    }
}
