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
		return [
			'renderer' => [
				[
					[ 'data' ] => 'text',
					[ 'label' ] => Message::newFromKey( 'bs-tagcloud-tag-tagcloud-attr-renderer-option-text' )
				],
				[
					[ 'data' ] => 'list',
					[ 'label' ] => Message::newFromKey( 'bs-tagcloud-tag-tagcloud-attr-renderer-option-list' )
				],
				[
					[ 'data' ] => 'canvas3d',
					[ 'label' ] => Message::newFromKey( 'bs-tagcloud-tag-tagcloud-attr-renderer-option-canvas3d' )
				]
			],
			'store' => [
				[
					[ 'data' ] => 'category',
					[ 'label' ] => Message::newFromKey( 'bs-tagcloud-tag-tagcloud-attr-store-option-category' )
				],
				[
					[ 'data' ] => 'searchstats',
					[ 'label' ] => Message::newFromKey( 'bs-tagcloud-tag-tagcloud-attr-store-option-searchstats' )
				]
			],
			'width' => 100,
			'showcount' => true,
			'minsize' => 5,
			'maxsize' => 30,
			'exclude' => ''
		];
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
