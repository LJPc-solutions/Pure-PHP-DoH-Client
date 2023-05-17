<?php

namespace LJPc\DoH\Types;

use LJPc\DoH\ByteOperations;
use LJPc\DoH\DomainLabel;

final class TLSA extends Type {
		use DomainLabel;

		protected int $typeId = 52;
		protected string $type = 'TLSA';

		public function decode( ByteOperations $byteOperations, array $ansHeader ): void {
				$length = $byteOperations->getByteCounter();

				$this->extras = unpack( "cusage/cselector/ctype", $byteOperations->getNextBytes( 3 ) );
				$length       = $byteOperations->getByteCounter() - $length;
				$this->value  = bin2hex( $byteOperations->getNextBytes( $ansHeader['length'] - $length ) );
		}
}
