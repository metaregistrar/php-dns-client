<?php
namespace Metaregistrar\DNS {
    class dnsTXTresult extends dnsResult
    {
        private $record;
        private $recordlength;

        public function __construct($record)
        {
            parent::__construct();
            
            $lenght = 0;
            $txt = '';
            foreach(str_split($record, 256) as $chunk) {
                $lenght += ord($chunk[0]);
                $txt .= substr($chunk,1);
            }
            $this->recordlength = $lenght;
            $this->setRecord($txt);
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

