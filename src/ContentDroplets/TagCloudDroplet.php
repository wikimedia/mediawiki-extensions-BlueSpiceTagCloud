<?php

namespace BlueSpice\TagCloud\ContentDroplets;

use MediaWiki\Extension\ContentDroplets\Droplet\TagDroplet;
use Message;
use RawMessage;

class TagCloudDroplet extends TagDroplet {

	/**
	 * @inheritDoc
	 */
	public function getName(): Message {
		return new RawMessage( 'TagCloud' );
	}

	/**
	 * @inheritDoc
	 */
	public function getDescription(): Message {
		return new RawMessage( "TagCloud description" );
	}

	/**
	 * @inheritDoc
	 */
	public function getIcon(): string {
		return 'tag';
	}

	/**
	 * @inheritDoc
	 */
	public function getRLModule(): string {
		return 'ext.bluespice.tagcloud.visualEditorPlugin';
	}

	/**
	 * @return array
	 */
	public function getCategories(): array {
		return [ 'lists', 'visualization', 'data' ];
	}

	/**
	 *
	 * @return string
	 */
	protected function getTagName(): string {
		return 'bs:tagcloud';
	}

	/**
	 * @return array
	 */
	protected function getAttributes(): array {
		return [];
	}

	/**
	 * @return bool
	 */
	protected function hasContent(): bool {
		return false;
	}

	/**
	 * @return string|null
	 */
	public function getVeCommand(): ?string {
		return 'tagCloudCommand';
	}

}
