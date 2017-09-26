<?php
/**
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License v2 or later
 * @filesource
 */
class TagCloudTextViewHandler extends TagCloudBaseViewHandler {
	protected static $iMaxFontSize = 24;
	protected static $iMinFontSize = 8;

	public static function sanitizeArrayEntry( $sInput, &$aArgs, Parser $oParser, $oErrorListView ) {
		parent::sanitizeArrayEntry( $sInput, $aArgs, $oParser, $oErrorListView );
		$aArgs['maxfontsize'] = BsCore::sanitizeArrayEntry(
			$aArgs,
			'maxfontsize',
			self::$iMaxFontSize,
			BsPARAMTYPE::INT
		);
		$aArgs['minfontsize'] = BsCore::sanitizeArrayEntry(
			$aArgs,
			'minfontsize',
			self::$iMinFontSize,
			BsPARAMTYPE::INT
		);
		$aArgs['noborder'] = BsCore::sanitizeArrayEntry(
			$aArgs,
			'noborder',
			false,
			BsPARAMTYPE::BOOL
		);
		$aArgs['color'] = BsCore::sanitizeArrayEntry(
			$aArgs,
			'color',
			'',
			BsPARAMTYPE::STRING
		);
	}
	public static function validateArrayEntry( $sInput, &$aArgs, Parser $oParser, $oErrorListView ) {
		parent::validateArrayEntry( $sInput, $aArgs, $oParser, $oErrorListView );
		$oValidationResult = BsValidator::isValid(
			'PositiveInteger',
			$aArgs['minfontsize'],
			array( 'fullResponse' => true )
		);
		if ( $oValidationResult->getErrorCode() ) {
			$oErrorListView->addItem(new ViewTagError(
				$oValidationResult->getI18N()
			));
		}
		$oValidationResult = BsValidator::isValid(
			'PositiveInteger',
			$aArgs['minfontsize'],
			array( 'fullResponse' => true )
		);
		if ( $oValidationResult->getErrorCode() ) {
			$oErrorListView->addItem(new ViewTagError(
				$oValidationResult->getI18N()
			));
		}
	}
	public function __construct( $aOptions, $aData = array(), Title $oTitle = null ) {
		parent::__construct( $aOptions, $aData, $oTitle );
		$this->calculateCount( $aData );
	}

	public function execute() {
		if( empty($this->aData) ) {
			return '';
		}
		$this->addModuleStyles( 'ext.bluespice.tagCloud.textView' );

		$sBaseClass = "bs-tagcloud-textview";
		shuffle( $this->aData );

		$sUnit = $this->aOptions['widthinpercent']
			? "%"
			: "px"
		;
		$sDivStyles = "style='"
			."width:{$this->aOptions['width']}$sUnit;"
			."float:{$this->aOptions['float']};"
		."'";

		$sTitle = empty($this->aOptions['title'])
			? ''
			: "<span class='$sBaseClass-title'>{$this->aOptions['title']}</span>"
		;

		$sCloudClass = $sBaseClass;
		if( !empty( $this->aOptions['noborder'] ) ) {
				$sCloudClass .= " bs-tagcloud-textview-noborder";
		}

		$sOut = "<div class='$sCloudClass' $sDivStyles>$sTitle<ul>";
		foreach( $this->aData as $o ) {
			$sContent = $o->tagname;
			if( $this->aOptions['showcount'] ) {
				$sContent .= " ($o->count)";
			}
			$iFontSize = $this->getTagSizeLogarithmic(
				$o->count,
				$this->iSmallestCount,
				$this->iBiggestCount,
				$this->aOptions['minfontsize'],
				$this->aOptions['maxfontsize'],
				0
			);
			$sFontSize = "font-size:{$iFontSize}px !important;";
			$sAnchorStyle = $sFontSize;
			if( !empty( $this->aOptions['color'] ) ) {
				$sAnchorStyle .= " color:{$this->aOptions['color']} !important";
			}

			if( !empty($o->link) ) {
				$sContent = XML::element('a', array(
					'href' => $o->link,
					'style' => $sAnchorStyle
				), $sContent);
				$sFontSize = '';
			}
			$sOut .= "<li style='$sFontSize'>$sContent</li>";
		}
		$sOut .= "</ul></div>";

		return $sOut;
	}
}
