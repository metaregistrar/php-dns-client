<?php
namespace Metaregistrar\DNS {
    class dnsMXresult extends dnsResult
    {
        private $prio;
        private $server;


        public function setPrio($prio)
        {
            $this->prio = $prio;
        }

        public function getPrio()
        {
            return $this->prio;
        }

        public function setServer($server)
        {
            $this->server = $server;
        }

        public function getServer()
        {
            return $this->server;
        }
    }
}