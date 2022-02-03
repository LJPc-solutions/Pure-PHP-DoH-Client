<?php

namespace LJPc\DoH\Types;

use LJPc\DoH\ByteOperations;
use LJPc\DoH\DomainLabel;

class CNAME extends DomainAnswerType {
	protected int $typeId = 5;
	protected string $type = 'CNAME';
}
