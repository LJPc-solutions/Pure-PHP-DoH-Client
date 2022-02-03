<?php

namespace LJPc\DoH;

use LJPc\DoH\Types\Type;
use RuntimeException;

class DNS {
	private static ByteOperations $byteOperations;
	private static array $header = [];

	public static function query( string $domainName, Type $type ): DNSQueryResult {
		$dnsQuery  = DNSRequest::create( $domainName, $type );
		$rawAnswer = self::requestAnswer( $dnsQuery );

		self::$byteOperations = new ByteOperations( $rawAnswer );

		self::processHeader();

		//Query counter is not important for the answer
		if ( self::getHeader()['qdcount'] > 0 ) {
			for ( $i = 0; $i < self::getHeader()['qdcount']; $i ++ ) {
				$c = 1;
				while ( $c !== 0 ) {
					$c = hexdec( bin2hex( self::$byteOperations->getNextBytes( 1 ) ) );
				}
				self::$byteOperations->dismissBytes( 4 );
			}
		}

		$dnsQueryResults = new DNSQueryResult();

		$answerAmount = self::getHeader()['ancount'];
		for ( $i = 0; $i < $answerAmount; $i ++ ) {
			try {
				$dnsQueryResults->addAnswer( new DNSRecord( self::$byteOperations ) );
			} catch ( RuntimeException $e ) {
			}
		}

		$authorityResultAmount = self::getHeader()['nscount'];
		for ( $i = 0; $i < $authorityResultAmount; $i ++ ) {
			try {
				$dnsQueryResults->addAuthorityRecord( new DNSRecord( self::$byteOperations ) );
			} catch ( RuntimeException $e ) {
			}
		}

		$authorityResultAmount = self::getHeader()['arcount'];
		for ( $i = 0; $i < $authorityResultAmount; $i ++ ) {
			try {
				$dnsQueryResults->addAuthorityRecord( new DNSRecord( self::$byteOperations ) );
			} catch ( RuntimeException $e ) {
			}
		}

		return $dnsQueryResults;
	}

	private static function requestAnswer( string $dnsQuery ): ?string {
		$ch      = curl_init();
		$headers = [ 'Accept: application/dns-udpwireformat', 'Content-type: application/dns-udpwireformat' ];
		curl_setopt( $ch, CURLOPT_URL, "https://dns.google/dns-query?dns=$dnsQuery" );
		curl_setopt( $ch, CURLOPT_USERAGENT, 'LJPc-PHP-DoH-Client' );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

		$output = curl_exec( $ch );
		if ( $output === false ) {
			return null;
		}

		return $output;
	}

	private static function processHeader() {
		self::$header = unpack( "nid/nspec/nqdcount/nancount/nnscount/narcount", self::$byteOperations->getNextBytes( 12 ) );
	}

	private static function getHeader(): array {
		return self::$header;
	}
}
