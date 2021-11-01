<?php
namespace Metaregistrar\DNS {
    class dnsTXTresult extends dnsResult
    {
        private $record;
        private $recordlength;

        public function __construct($record)
        {
            parent::__construct();
            $this->recordlength = ord($record[0]);
            $this->setRecord(substr($record,1));
        }

        public function setRecord($record)
        {
            $this->record = $record;
        }

        public function getRecord()
        {
            return $this->record;
        }

        public function getRecordLength()
        {
            return $this->recordlength;
        }
    }
}

