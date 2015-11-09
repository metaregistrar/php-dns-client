<?php
namespace Metaregistrar\DNS {
    class dnsPTRresult extends dnsResult
    {
        private $data;

        function __construct($data)
        {
            parent::__construct();
            $this->setData($data);
        }

        public function setData($data)
        {
            $this->dat = $data;
        }

        public function getData()
        {
            return $this->data;
        }
    }
}