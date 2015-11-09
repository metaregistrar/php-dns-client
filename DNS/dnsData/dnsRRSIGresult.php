<?php
namespace Metaregistrar\DNS {
    class dnsRRSIGresult extends dnsResult
    {
        private $typecovered;
        private $algorithm;
        private $labels;
        private $originalttl;
        private $expirationdate;
        private $expirationtimestamp;
        private $inceptiondate;
        private $inceptiontimestamp;
        private $keytag;
        private $signername;
        private $signature;
        private $signaturebase64;
        private $publickey;

        public function __construct($type, $algorithm, $labels, $originalttl, $expiration, $inception, $keytag)
        {
            parent::__construct();
            date_default_timezone_set('UTC');
            $types = new DNSTypes();
            $this->setTypecovered($types->GetById($type));
            $this->setAlgorithm($algorithm);
            $this->setLabels($labels);
            $this->setOriginalTTL($originalttl);
            $this->setExpirationTimestamp($expiration);
            $this->setInceptionTimestamp($inception);
            $this->setExpirationDate(date('YmdHis',$expiration));
            $this->setInceptionDate(date('YmdHis',$inception));
            $this->setKeytag($keytag);
        }

        public function setExpirationTimestamp($timestamp)
        {
            $this->expirationtimestamp = $timestamp;
        }

        public function getExpirationTimestamp()
        {
            return $this->expirationtimestamp;
        }

        public function setInceptionTimestamp($timestamp)
        {
            $this->inceptiontimestamp = $timestamp;
        }

        public function getInceptionTimestamp()
        {
            return $this->inceptiontimestamp;
        }

        public function setSignature($sig)
        {
            $this->signature = $sig;
        }

        public function getSignature()
        {
            return $this->signature;
        }

        public function setSignatureBase64($sig)
        {
            $this->signaturebase64 = $sig;
        }

        public function getSignatureBase64()
        {
            return $this->signaturebase64;
        }

        public function setSignername($name)
        {
            $this->signername = $name;
        }

        public function getSignername()
        {
            return $this->signername;
        }

        public function setTypecovered($type)
        {
            $this->typecovered = $type;
        }

        public function getTypecovered()
        {
            return $this->typecovered;
        }

        public function setAlgorithm($algorithm)
        {
            $this->algorithm = $algorithm;
        }

        public function getAlgorithm()
        {
            return $this->algorithm;
        }

        public function setLabels($labels)
        {
            $this->labels = $labels;
        }

        public function getLabels()
        {
            return $this->labels;
        }

        public function setExpirationDate($expiration)
        {
            $this->expirationdate = $expiration;
        }

        public function getExpirationDate()
        {
            return $this->expirationdate;
        }

        public function setInceptionDate($inception)
        {
            $this->inceptiondate = $inception;
        }

        public function getInceptionDate()
        {
            return $this->inceptiondate;
        }

        public function setOriginalTTL($ttl)
        {
            $this->originalttl = $ttl;
        }

        public function getOriginalTTL()
        {
            return $this->originalttl;
        }

        public function setKeytag($keytag)
        {
            $this->keytag = $keytag;
        }

        public function getKeytag()
        {
            return $this->keytag;
        }

        public function setPublicKey($key)
        {
            $this->publickey = $key;
        }

        public function getPublicKey()
        {
            return $this->publickey;
        }
    }
}
