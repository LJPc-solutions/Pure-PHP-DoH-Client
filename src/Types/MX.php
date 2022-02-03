<?php

namespace LJPc\DoH\Types;

use LJPc\DoH\ByteOperations;
use LJPc\DoH\DomainLabel;

class MX extends Type {
	use DomainLabel;

	protected int $typeId = 15;
	protected string $type = 'MX';

	public function decode( ByteOperations $byteOperations, array $ansHeader ): void {
		$this->extras = unpack( "npreference", $byteOperations->getNextBytes( 2 ) );
		$this->value  = $this->domainLabel( $byteOperations );
	}
}
