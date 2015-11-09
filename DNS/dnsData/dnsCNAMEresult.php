<?php
namespace Metaregistrar\DNS {
    class dnsCNAMEresult extends dnsResult
    {
        private $redirect;

        public function __construct($redirect)
        {
            parent::__construct();
            $this->setRedirect($redirect);
        }

        public function setRedirect($redirect)
        {
            $this->redirect = $redirect;
        }

        public function getRedirect()
        {
            return $this->redirect;
        }
    }
}