<?php

namespace LJPc\DoH\Servers;

class AdGuard extends DoHServer {
		protected string $url = 'https://dns.adguard.com/dns-query';
		protected array $ips = array("94.140.14.14", "94.140.15.15", "2a10:50c0::ad1:ff", "2a10:50c0::ad2:ff");
}
