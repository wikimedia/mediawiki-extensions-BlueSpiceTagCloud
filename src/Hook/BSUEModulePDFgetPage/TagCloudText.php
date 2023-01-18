<?php

namespace BlueSpice\TagCloud\Hook\BSUEModulePDFgetPage;

use BlueSpice\UEModulePDF\Hook\BSUEModulePDFgetPage;

/**
 * The tag cloud of type 'text' has different font-sizes in the style attribute of the
 * li elements. This creates weird appearance in the pdf. Here we remove the
 * style attributes to get a nice list.
 */
class TagCloudText extends BSUEModulePDFgetPage {

	/**
	 *
	 */
	public function doProcess() {
		$items = $this->DOMXPath->query(
			"//ul[contains(@class, 'bs-tagcloud') and contains(@class, 'text')]/li"
		);

		foreach ( $items as $item ) {
			if ( $item->hasAttribute( 'style' ) ) {
				$item->removeAttribute( 'style' );
			}
		}
	}
}
