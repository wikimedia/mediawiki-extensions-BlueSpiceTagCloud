<?php
/**
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, version 3.
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
class TagCloudListViewHandler extends TagCloudBaseViewHandler {

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

		$sBaseClass = "bs-categorytagcloud-listview";
		$sDivStyles = "style='"
			."float:{$this->aOptions['float']};"
		."'";

		$sTitle = empty($this->aOptions['title'])
			? ''
			: "<h3 class='$sBaseClass-title'>{$this->aOptions['title']}</h3>"
		;

		$sOut = "<div class='$sBaseClass' $sDivStyles>$sTitle<ul>";
		foreach( $this->aData as $sKey => $o ) {
			$sContent = $o->tagname;
			if( $this->aOptions['showcount'] ) {
				$sContent .= " ($o->count)";
			}
			
			if( !empty($o->link) ) {
				$sContent = XML::element('a', array(
					'href' => $o->link,
				), $sContent);
			}
			$sOut .= "<li>$sContent</li>";
		}
		$sOut .= "</ul></div>";
		
		return $sOut;
	}
}