<?php

namespace LJPc\DoH\Servers;

class CloudFlare extends DoHServer {
		protected string $url = 'https://cloudflare-dns.com/dns-query';
		protected array $ips = array("1.1.1.1", "1.0.0.1", "2606:4700:4700::1111", "2606:4700:4700::1001");
}
