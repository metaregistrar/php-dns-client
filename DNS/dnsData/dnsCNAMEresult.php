<?php
namespace Metaregistrar\DNS {
    class dnsCNAMEresult extends dnsResult
    {
        /**
         * @var string
         */
        private $redirect;

        public function __construct($redirect)
        {
            parent::__construct();
            $this->setRedirect($redirect);
        }

        /**
         * @param string $redirect
         */
        public function setRedirect($redirect)
        {
            $this->redirect = $redirect;
        }

        /**
         * @return string
         */
        public function getRedirect()
        {
            return $this->redirect;
        }
    }
}