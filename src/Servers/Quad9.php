<?php

namespace LJPc\DoH\Servers;

class Quad9 extends DoHServer {
		protected string $url = 'https://dns.quad9.net/dns-query';
		protected array $ips = array("9.9.9.9", "149.112.112.112", "2620:fe::fe", "2620:fe::9");
}
