<?php

namespace Metaregistrar\DNS {
    class dnsProtocol
    {
        private $header;
        private $rawheader;
        private $rawbuffer;
        /**
         *
         * @var boolean $logging
         */
        protected $logging;
        /**
         *
         * @var array $logentries
         */
        protected $logentries;
        /**
         *
         * @var string $server
         */
        protected $server;
        /**
         *
         * @var int $port=53
         */
        protected $port;
        /**
         *
         * @var integer $timeout = 60
         */
        protected $timeout;
        /**
         *
         * @var boolean $udp = true;
         */
        protected $udp;
        /**
         *
         * @var array $types
         */
        protected $types;

        function __construct($logging = false)
        {
            if ($logging)
            {
                $this->enableLogging();
            }
            $this->port=53;
            $this->timeout=60;
            $this->udp=false;
            $this->types=new dnsTypes();
            set_error_handler(array($this,'error_handler'));
            $this->writelog("dnsProtocol Class Initialised");
        }

        function __destruct()
        {
            if ($this->logging)
            {
                $this->showLog();
            }
        }

        function error_handler($errno = 0, $errstr = null, $errfile = null, $errline = null)
        {
            // If error is suppressed with @, don't throw an exception
            if (error_reporting() === 0)
            {
                return true; // return true to continue through the others error handlers
            }
            throw new dnsException('Found '.$errstr.' in line '.$errline.' of '.$errfile, $errno, null);
        }


        /**
         * @param $question
         * @param string $type
         * @return dnsResponse
         * @throws dnsException
         */
        function Query($question,$type='A')
        {
            $typeid=$this->types->GetByName($type);
            if ($typeid===false)
            {
                throw new dnsException("Invalid Query Type ".$type);
            }

            if ($this->udp)
            {
                $host="udp://".$this->server;
            }
            else
            {
                $host=$this->server;
            }
            if (!$socket=@fsockopen($host,$this->port,$errno,$errstr,$this->timeout))
            {
                throw new dnsException("Failed to open socket to ".$host);
            }

            // Split Into Labels
            if (preg_match("/[a-z|A-Z]/",$question)==0) // IP Address
            {
                $labeltmp=explode(".",$question);	// reverse ARPA format
                for ($i=count($labeltmp)-1; $i>=0; $i--)
                {
                    $labels[]=$labeltmp[$i];
                }
                $labels[]="IN-ADDR";
                $labels[]="ARPA";
            }
            else
            {
                $labels=explode(".",$question);
            }
            $question_binary="";
            for ($a=0; $a<count($labels); $a++)
            {
                $size=strlen($labels[$a]);
                $question_binary.=pack("C",$size); // size byte first
                $question_binary.=$labels[$a]; // then the label
            }
            $question_binary.=pack("C",0); // end it off

            $this->writeLog("Question: ".$question." (type=".$type."/".$typeid.")");

            $id=rand(1,255)|(rand(0,255)<<8);	// generate the ID

            // Set standard codes and flags
            $flags=(0x0100 & 0x0300) | 0x0020; // recursion & queryspecmask | authenticated data

            $opcode=0x0000; // opcode

            // Build the header
            $header="";
            $header.=pack("n",$id);
            $header.=pack("n",$opcode | $flags);
            $header.=pack("nnnn",1,0,0,0);
            $header.=$question_binary;
            $header.=pack("n",$typeid);
            $header.=pack("n",0x0001); // internet class
            $headersize=strlen($header);
            $headersizebin=pack("n",$headersize);

            $this->writeLog("Header Length: ".$headersize." Bytes");
            #$this->DebugBinary($header);

            if ( ($this->udp) && ($headersize>=512) )
            {
                fclose($socket);
                throw new dnsException("Question too big for UDP (".$headersize." bytes)");
            }

            if ($this->udp) // UDP method
            {
                if (!fwrite($socket,$header,$headersize))
                {
                    fclose($socket);
                    throw new dnsException("Failed to write question to socket");
                }
                if (!$this->rawbuffer=fread($socket,4096)) // read until the end with UDP
                {
                    fclose($socket);
                    throw new dnsException("Failed to write read data buffer");
                }
            }
            else // TCP method
            {
                if (!fwrite($socket,$headersizebin)) // write the socket
                {
                    fclose($socket);
                    throw new dnsException("Failed to write question length to TCP socket");
                }
                if (!fwrite($socket,$header,$headersize))
                {
                    fclose($socket);
                    throw new dnsException("Failed to write question to TCP socket");
                }
                if (!$returnsize=fread($socket,2))
                {
                    fclose($socket);
                }
                $tmplen=unpack("nlength",$returnsize);
                $datasize=$tmplen['length'];
                $this->writeLog("TCP Stream Length Limit ".$datasize);
                if (!$this->rawbuffer=fread($socket,$datasize))
                {
                    fclose($socket);
                    throw new dnsException("Failed to read data buffer");
                }
            }
            fclose($socket);

            $buffersize=strlen($this->rawbuffer);
            $this->writelog("Read Buffer Size ".$buffersize);
            if ($buffersize<12)
            {
                throw new dnsException("DNS query return buffer too small");
            }

            $this->rawheader=substr($this->rawbuffer,0,12); // first 12 bytes is the header
            $this->rawresponse=substr($this->rawbuffer,12); // after that the response
            #$this->DebugBinary($this->rawbuffer);
            $this->header=unpack("nid/nflags/nqdcount/nancount/nnscount/narcount",$this->rawheader);
            $flags = sprintf("%016b\n",$this->header['flags']);
            $response=new dnsResponse();

            $response->setAuthorative($flags{5}=='1');
            $response->setTruncated($flags{6}=='1');
            $response->setRecursionRequested($flags{7}=='1');
            $response->setRecursionAvailable($flags{8}=='1');
            $response->setAuthenticated($flags{10}=='1');
            $response->setDnssecAware($flags{11}=='1');
            $response->setAnswerCount($this->header['ancount']);

            $this->writeLog("Query returned ".$this->header['ancount']." Answers");

            // Deal with the header question data
            if ($this->header['qdcount']>0)
            {
                $response->setQueryCount($this->header['qdcount']);
                $this->writeLog("Found ".$this->header['qdcount']." questions");
                $q = '';
                for ($a=0; $a<$this->header['qdcount']; $a++)
                {
                    $c=1;
                    while ($c!=0)
                    {
                        $c=hexdec(bin2hex($response->ReadResponse($this->rawbuffer, 1)));
                        $q .= $c;
                    }
                    $response->addQuery($q);
                    $response->ReadResponse($this->rawbuffer, 4);
                }
            }

            $this->writeLog("Found ".$this->header['ancount']." answer records");
            $response->setResourceResultCount($this->header['ancount']);
            for ($a=0; $a<$this->header['ancount']; $a++)
            {
                $response->ReadRecord($this->rawbuffer,dnsResponse::RESULTTYPE_RESOURCE);
            }

            $this->writeLog("Found ".$this->header['nscount']." authorative records");
            $response->setNameserverResultCount($this->header['nscount']);
            for ($a=0; $a<$this->header['nscount']; $a++)
            {
                $response->ReadRecord($this->rawbuffer,dnsResponse::RESULTTYPE_NAMESERVER);
            }
            $response->setAdditionalResultCount($this->header['arcount']);
            $this->writeLog("Found ".$this->header['arcount']." additional records");
            for ($a=0; $a<$this->header['arcount']; $a++)
            {
                $response->ReadRecord($this->rawbuffer,dnsResponse::RESULTTYPE_ADDITIONAL);
            }
            return $response;
        }

        public function setServer($server)
        {
            $this->server = $server;
        }

        public function getServer()
        {
            return $this->server;
        }

        public function setPort($port)
        {
            $this->port = $port;
        }

        public function getPort()
        {
            return $this->port;
        }

        private function enableLogging()
        {
            $this->logging = true;
        }

        private function showLog()
        {
            echo "==== LOG ====";
            foreach ($this->logentries as $logentry)
            {
                echo $logentry."\n";
            }
        }

        private function writeLog($text)
        {
            if ($this->logging)
            {
                $this->logentries[] = "-----".date("Y-m-d H:i:s")."-----".$text."-----";
            }
        }

        function algorithm($code)
        {
            # Reference:
            # http://www.iana.org/protocols
            # http://www.iana.org/assignments/dns-sec-alg-numbers/dns-sec-alg-numbers.xml
            switch ($code)
            {
                case 1:
                    return 'md5';
                case 2:
                    # Diffie-Helman
                    return 'dh';
                case 3:
                    return 'sha1';
                case 4:
                    return 'reserved';
                case 5:
                    return 'sha1';
                case 6:
                    return 'dsansec3sha1';
                case 7:
                    return 'rsasha1nsec3';
                case 8:
                    return 'sha256';
                case 9:
                    return 'reserved';
                case 10:
                    return 'sha512';
                case 11:
                    return 'reserved';
                case 12:
                    return 'gost';
                case 13:
                    return 'ecdsa256';
                case 14:
                    return 'ecdsa384';
                default:
                    return 'unknown algorithm';
            }
        }

        function registrynameservers($tld)
        {
            $ns = new dnsNameserver();
            return $ns->getNs($tld);
        }

        function base32encode($input, $padding = true) {

            $map = array(
                '0', '1', '2', '3', '4', '5', '6', '7', //  7
                '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', // 15
                'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', // 23
                'o', 'p', 'q', 'r', 's', 't', 'u', 'v', // 31
                '='  // padding char
            );

            if(empty($input)) return "";
            $input = str_split($input);
            $binaryString = "";
            for($i = 0; $i < count($input); $i++) {
                $binaryString .= str_pad(base_convert(ord($input[$i]), 10, 2), 8, '0', STR_PAD_LEFT);
            }
            $fiveBitBinaryArray = str_split($binaryString, 5);
            $base32 = "";
            $i=0;
            while($i < count($fiveBitBinaryArray)) {
                $base32 .= $map[base_convert(str_pad($fiveBitBinaryArray[$i], 5,'0'), 2, 10)];
                $i++;
            }
            if($padding && ($x = strlen($binaryString) % 40) != 0) {
                if($x == 8) $base32 .= str_repeat($map[32], 6);
                else if($x == 16) $base32 .= str_repeat($map[32], 4);
                else if($x == 24) $base32 .= str_repeat($map[32], 3);
                else if($x == 32) $base32 .= $map[32];
            }
            return $base32;
        }

        function nsec3hash($qname, $salt=null, $iterations='-')
        {
            //echo $this->nsec3hash('www.lunetkade.nl', 'AB', 1);
            $salt = pack('H*' , $salt); //hex2bin() in php 5.4
            $iterations = intval($iterations);
            $toHash='';

            $qparts = explode('.', strtolower($qname).'.');
            foreach ($qparts as $part)
            {
                $toHash .= chr(strlen($part)).$part;
            }

            //echo bin2hex($toHash.$salt)."\n";
            do
            {
                $toHash .= $salt;
                $toHash = sha1($toHash, true);
                $iterations--;
            }
            while ($iterations >= 0);
            return $this->base32encode($toHash);
        }

    }

}
