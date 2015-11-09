<?php
namespace Metaregistrar\DNS {
    class dnsResult
    {

        private $type;
        private $typeid;
        private $class;
        private $ttl;
        private $data;
        private $domain;
        private $string;
        private $record;
        private $extras = array();


        public function __construct()
        {
            date_default_timezone_set('UTC');
        }

        public function getType()
        {
            return $this->type;
        }

        public function setType($type)
        {
            $this->type = $type;
        }

        public function setTypeid($typeid)
        {
            $this->typeid = $typeid;
        }

        public function getTypeid()
        {
            return $this->typeid;
        }

        public function getClass()
        {
            return $this->class;
        }

        public function setClass($class)
        {
            $this->class = $class;
        }

        public function getTtl()
        {
            return $this->ttl;
        }

        public function setTtl($ttl)
        {
            $this->ttl = $ttl;
        }


        public function getData()
        {
            return $this->data;
        }

        public function setData($data)
        {
            $this->data = $data;
        }

        public function getDomain()
        {
            return $this->domain;
        }

        public function setDomain($domain)
        {
            $this->domain = $domain;
        }


        public function getString()
        {
            return $this->string;
        }

        public function setString($string)
        {
            $this->string = $string;
        }


        public function getRecord()
        {
            return $this->record;
        }

        public function setRecord($record)
        {
            $this->record = $record;
        }

        public function getExtras()
        {
            return $this->extras;
        }

        public function setExtras($extras)
        {
            $this->extras = $extras;
        }
    }
}