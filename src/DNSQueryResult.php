<?php

namespace LJPc\DoH;

use JsonSerializable;

class DNSQueryResult implements JsonSerializable {
	private array $answers = [];
	private array $authorityRecords = [];
	private array $additionalRecords = [];

	/**
	 * @return array
	 */
	public function getAnswers(): array {
		return $this->answers;
	}

	/**
	 * @param  array  $answers
	 */
	public function setAnswers( array $answers ): void {
		$this->answers = $answers;
	}

	public function addAnswer( DNSRecord $record ) {
		$this->answers[] = $record;
	}

	/**
	 * @return array
	 */
	public function getAuthorityRecords(): array {
		return $this->authorityRecords;
	}

	/**
	 * @param  array  $authorityRecords
	 */
	public function setAuthorityRecords( array $authorityRecords ): void {
		$this->authorityRecords = $authorityRecords;
	}

	public function addAuthorityRecord( DNSRecord $record ): void {
		$this->authorityRecords[] = $record;
	}

	/**
	 * @return array
	 */
	public function getAdditionalRecords(): array {
		return $this->additionalRecords;
	}

	/**
	 * @param  array  $additionalRecords
	 */
	public function setAdditionalRecords( array $additionalRecords ): void {
		$this->additionalRecords = $additionalRecords;
	}

	public function addAdditionalRecord( DNSRecord $record ): void {
		$this->additionalRecords[] = $record;
	}

	public function jsonSerialize() {
		return [
			'answers'           => $this->answers,
			'authorityRecords'  => $this->authorityRecords,
			'additionalRecords' => $this->additionalRecords,
		];
	}
}
