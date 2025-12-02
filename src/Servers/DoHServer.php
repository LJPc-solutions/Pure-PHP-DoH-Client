<?php

namespace LJPc\DoH\Servers;

use InvalidArgumentException;


abstract class DoHServer {
		protected string $url = '';

		/**
		 * @var string[] $ips
		 */
		protected array $ips = [];

		public function call( $dnsQuery ): ?string {
				$ch = curl_init();

				$headers = [ 'Accept: application/dns-udpwireformat', 'Content-type: application/dns-udpwireformat' ];

				curl_setopt( $ch, CURLOPT_URL, $this->url . "?dns=$dnsQuery" );
				curl_setopt( $ch, CURLOPT_USERAGENT, 'LJPc-PHP-DoH-Client' );
				curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
				curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
				curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
				if (count($this->ips) != 0) {
					$parsed = parse_url($this->url, PHP_URL_HOST);
					if ($parsed === false) {
						throw new InvalidArgumentException( 'URL cannot parse' );
					}
					$resolved = $parsed . ":443:";
					foreach ($this->ips as $key => $ip) {
						if ($key != 0) {
							$resolved = $resolved.",";
						}
						$resolved = $resolved.$ip;
					}
					curl_setopt($ch, CURLOPT_RESOLVE, [$resolved]);
				}

				$output = curl_exec( $ch );
				if ( $output === false ) {
						return null;
				}

				return $output;
		}
}
