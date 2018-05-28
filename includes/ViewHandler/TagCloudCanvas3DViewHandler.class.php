<?php
/**
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, version 3
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
 * This file is part of BlueSpice MediaWiki
 * For further information visit http://bluespice.com
 *
 * @author     Patric Wirth <wirth@hallowelt.com>
 * @package    BluespiceTagCloud
 * @copyright  Copyright (C) 2016 Hallo Welt! GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License v3
 * @filesource
 */
class TagCloudCanvas3DViewHandler extends TagCloudBaseViewHandler {
	protected $iMaxFontSize = 24;
	protected $iMinFontSize = 8;
	protected static $aCanvasIDs = array();

	public static function sanitizeArrayEntry( $sInput, &$aArgs, Parser $oParser, $oErrorListView ) {
		parent::sanitizeArrayEntry( $sInput, $aArgs, $oParser, $oErrorListView );
	}
	public static function validateArrayEntry( $sInput, &$aArgs, Parser $oParser, $oErrorListView ) {
		parent::validateArrayEntry( $sInput, $aArgs, $oParser, $oErrorListView );
	}
	public function __construct( $aOptions, $aData = array(), Title $oTitle = null ) {
		parent::__construct( $aOptions, $aData, $oTitle );
		$this->calculateCount( $aData );
	}

	public function execute() {
		if( empty($this->aData) ) {
			return '';
		}
		$this->addModuleScipts( 'ext.bluespice.canvas3DTagCloud.canvas3DView' );

		$sDivStyles = "style='"
			."width:{$this->aOptions['width']}px;"
			."float:{$this->aOptions['float']};"
		."'";

		$sID = $this->getNewCanvasID( 'bs-tagcloud3dcanvas-3dcanvas-' );
		$sOut = "<div class='bs-tagcloud-3dcanvasview' $sDivStyles>";
		$sOut .= "<Canvas class='bs-tagcloud3dcanvas-3dcanvas' id='$sID' $sDivStyles />";
		$sOut .= "<ul id='$sID-tags'>";
		foreach( $this->aData as $sKey => $o ) {
			$sContent = $o->tagname;
			$iFontSize = $this->getTagSizeLogarithmic(
				$o->count,
				$this->iSmallestCount,
				$this->iBiggestCount,
				$this->iMinFontSize,
				$this->iMaxFontSize,
				0
			);

			$sDataWeight = "data-weight='$iFontSize'";
			if( $this->aOptions['showcount'] ) {
				$sContent .= " ($o->count)";
			}
			
			if( !empty($o->link) ) {
				$sContent = XML::element('a', array(
					'href' => $o->link,
					'data-weight' => $iFontSize,
				), $sContent);
				$sDataWeight = '';
			}
			$sOut .= "<li $sDataWeight>$sContent</li>";
		}
		$sOut .= "</ul>";
		$sOut .= "</div>";
		
		return $sOut;
	}

	protected function getNewCanvasID( $sPrefix ) {
		$aIDs = self::$aCanvasIDs;
		if( empty($aIDs) ) {
			self::$aCanvasIDs[0] = $sPrefix . (string) 0;
			return self::$aCanvasIDs[0];
		}
		$iID = count($aIDs) -1;
		self::$aCanvasIDs[$iID] = $sPrefix . (string) $iID;
	}
}