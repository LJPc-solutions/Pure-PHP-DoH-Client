<?php

namespace LJPc\DoH;

use JsonSerializable;
use RuntimeException;
use Throwable;

class DNSRecord implements JsonSerializable {
	use DomainLabel;

	public string $domainName;
	public int $ttl = 0;
	public string $type = '';
	public array $extras = [];
	public string $value = '';
	private ByteOperations $byteOperations;

	/**
	 * @throws DNSException
	 */
	public function __construct( ByteOperations $byteOperations ) {
		try {
			$this->byteOperations = $byteOperations;

			$this->domainName = $this->domainLabel( $this->byteOperations );
			$ansHeader        = @unpack( "ntype/nclass/Nttl/nlength", $this->byteOperations->getNextBytes( 10 ) );
			if ( ! isset( $ansHeader['ttl'] ) ) {
				throw new RuntimeException( 'Invalid DNS response' );
			}
			$this->ttl = $ansHeader['ttl'];

			$type       = DNSType::getById( $ansHeader['type'] );
			$this->type = $type->getType();
			$type->decode( $this->byteOperations, $ansHeader );

			$this->extras = $type->getExtras();
			$this->value  = $type->getValue();
		} catch ( Throwable $e ) {
			//Rarely a nameservers returns invalid data, so we just ignore it
			throw new DNSException( 'Invalid DNS response' );
		}
	}

	public function jsonSerialize(): array {
		return [
			'domainName' => $this->domainName,
			'ttl'        => $this->ttl,
			'type'       => $this->type,
			'extras'     => $this->extras,
			'value'      => $this->value,
		];
	}
}
