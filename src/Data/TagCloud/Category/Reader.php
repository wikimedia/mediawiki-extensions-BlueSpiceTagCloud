<?php

namespace BlueSpice\TagCloud\Data\TagCloud\Category;

use BlueSpice\Data\DatabaseReader;
use BlueSpice\TagCloud\Data\TagCloud\Schema;
use MediaWiki\MediaWikiServices;

class Reader extends DatabaseReader {
	/**
	 *
	 * @param \LoadBalancer $loadBalancer
	 * @param \IContextSource|null $context
	 */
	public function __construct( $loadBalancer, \IContextSource $context = null ) {
		parent::__construct( $loadBalancer, $context, $context->getConfig() );
	}

	/**
	 *
	 * @param type $params
	 * @return PrimaryDataProvider
	 */
	protected function makePrimaryDataProvider( $params ) {
		return new PrimaryDataProvider( $this->db, $this->context );
	}

	/**
	 *
	 * @return SecondaryDataProvider
	 */
	protected function makeSecondaryDataProvider() {
		return new SecondaryDataProvider(
			MediaWikiServices::getInstance()->getLinkRenderer(),
			$this->context
		);
	}

	/**
	 *
	 * @return Schema
	 */
	public function getSchema() {
		return new Schema();
	}

}
