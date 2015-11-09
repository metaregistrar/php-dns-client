<?php
namespace Metaregistrar\DNS {
    // Reference http://www.iana.org/assignments/dns-parameters/dns-parameters.xhtml
    class dnsTypes
    {
        var $types_by_id;
        var $types_by_name;

        private function AddType($id, $name)
        {
            $this->types_by_id[$id] = $name;
            $this->types_by_name[$name] = $id;
        }

        function __construct()
        {
            $this->types_by_id = array();
            $this->types_by_name = array();

            $this->AddType(1, "A"); // RFC1035
            $this->AddType(2, "NS"); // RFC1035
            $this->AddType(5, "CNAME"); // RFC1035
            $this->AddType(6, "SOA"); // RFC1035 RFC2308
            $this->AddType(12, "PTR"); // RFC1035
            $this->AddType(13, "HINFO");
            $this->AddType(14, "MINFO");
            $this->AddType(15, "MX"); // RFC1035 RFC7505
            $this->AddType(16, "TXT"); // RFC1035
            $this->AddType(17, "RP"); // RFC1183
            $this->AddType(18, "AFSDB"); // RFC1183 RFC5864
            $this->AddType(19, "X25"); // RFC1183
            $this->AddType(20, "ISDN"); // RFC1183
            $this->AddType(21, "RT"); // RFC1183
            $this->AddType(22, "NSAP"); // RFC1706
            $this->AddType(23, "NSAP-PTR"); // RFC1348 RFC1637 RFC1706
            $this->AddType(24, "SIG"); // RFC4034 RFC3755 RFC2535 RFC2536 RFC2537 RFC3008 RFC3110
            $this->AddType(25, "KEY"); // RFC2930 RFC4034 RFC2535 RFC2536 RFC2537 RFC3008 RFC3110
            $this->AddType(26, "PX"); // RFC2136
            $this->AddType(27, "GPOS"); // RFC1712
            $this->AddType(28, "AAAA"); // RFC3596
            $this->AddType(29, "LOC"); // RFC1876
            $this->AddType(31, "EID");
            $this->AddType(32, "NIMLOC");
            $this->AddType(33, "SRV"); // RFC2782
            $this->AddType(34, "ATMA");
            $this->AddType(35, "NAPTR"); // RFC3403
            $this->AddType(36, "KX"); // RFC2230
            $this->AddType(37, "CERT"); // RFC4398
            $this->AddType(39, "DNAME"); // RFC2672
            $this->AddType(40, "SINK");
            $this->AddType(41, "OPT"); // RFC6891 RFC3658
            $this->AddType(42, "APL");
            $this->AddType(43, "DS"); // RFC4034 RFC3658
            $this->AddType(44, "SSHFP"); // RFC4255
            $this->AddType(45, "IPSECKEY"); // RFC4025
            $this->AddType(46, "RRSIG"); // RFC4034 RFC3755
            $this->AddType(47, "NSEC"); // RFC4034 RFC3755
            $this->AddType(48, "DNSKEY"); // RFC4034 RFC3755
            $this->AddType(49, "DHCID"); // RFC4701
            $this->AddType(50, "NSEC3"); // RFC5155
            $this->AddType(51, "NSEC3PARAM"); // RFC5155
            $this->AddType(52, "TLSA"); // RFC6698
            $this->AddType(55, "HIP"); // RFC5205
            $this->AddType(56, "NINFO");
            $this->AddType(57, "RKEY");
            $this->AddType(58, "TALINK");
            $this->AddType(59, "CDS"); // RFC7344
            $this->AddType(60, "CDNSKEY"); // RFC7344
            $this->AddType(61, "OPENPGPKEY"); // internet draft
            $this->AddType(62, "CSYNC"); // RFC7477
            $this->AddType(99, "SPF"); // RFC4408 RFC7208
            $this->AddType(100, "UNIFO"); // IANA Reserved
            $this->AddType(101, "UID"); // IANA Reserved
            $this->AddType(102, "GID"); // IANA Reserved
            $this->AddType(103, "UNSPEC"); // IANA Reserved
            $this->AddType(104, "NID"); // RFC6742
            $this->AddType(105, "L32"); // RFC6742
            $this->AddType(106, "L64"); // RFC6742
            $this->AddType(107, "LP"); // RFC6742
            $this->AddType(108, "EUI48"); // RFC7043
            $this->AddType(109, "EUI64"); // RFC7043
            $this->AddType(249, "TKEY"); // RFC2930
            $this->AddType(250, "TSIG"); // RFC2845
            $this->AddType(251, "IXFR"); // RFC1995
            $this->AddType(252, "AXFR"); // RFC1035 RFC5936
            $this->AddType(253, "MAILB"); // RFC1035
            $this->AddType(254, "MAILA"); // RFC1035
            $this->AddType(255, "ANY"); // RFC1035 RFC6895
            $this->AddType(256, "URI"); // RFC7553
            $this->AddType(257, "CAA"); // RFC6844
            $this->AddType(32768, "TA");
            $this->AddType(32769, "DLV");
            $this->AddType(65534, "TYPE65534"); // Eurid uses this one?
        }

        function GetByName($name)
        {
            if (isset($this->types_by_name[$name])) {
                return $this->types_by_name[$name];
            } else {
                throw new dnsException("Invalid name $name specified on GetByName");
            }

        }

        function GetById($id)
        {
            if (isset($this->types_by_id[$id])) {
                return $this->types_by_id[$id];
            } else {
                throw new dnsException("Invalid id $id on GetById");
            }
        }
    }
}