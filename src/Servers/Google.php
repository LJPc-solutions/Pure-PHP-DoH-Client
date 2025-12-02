<?php

namespace LJPc\DoH\Servers;

class Google extends DoHServer {
		protected string $url = 'https://dns.google/dns-query';
		protected array $ips = array("8.8.8.8", "8.8.4.4", "2001:4860:4860::8888", "2001:4860:4860::8844");
}
