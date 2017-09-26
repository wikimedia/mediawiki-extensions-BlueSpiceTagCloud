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
class TagCloudCategoryHandler extends TagCloudBaseHandler {
	public static function sanitizeArrayEntry( $sInput, &$aArgs, Parser $oParser, $oErrorListView ) {
		parent::sanitizeArrayEntry( $sInput, $aArgs, $oParser, $oErrorListView );
		if ( !empty( $aArgs['exclude'] ) ) {
			$aArgs['exclude'] = explode( ',', $aArgs['exclude'] );
		}
		$aArgs['exclude'] = BsCore::sanitizeArrayEntry(
			$aArgs,
			'exclude',
			array(),
			BsPARAMTYPE::ARRAY_STRING
		);
	}
	public static function validateArrayEntry( $sInput, &$aArgs, Parser $oParser, $oErrorListView ) {
		parent::validateArrayEntry( $sInput, $aArgs, $oParser, $oErrorListView );
	}

	public static function collectData( $aOptions, $aData = array(), Title $oTitle = null ) {
		$oDBr = wfGetDB( DB_SLAVE );
		$aTables = array(
			'categorylinks'
		);
		$aFields = array(
			'cl_to',
			'count' => 'COUNT(cl_to)'
		);
		$aConditions = array();
		if( !empty($aOptions['exclude']) ) {
			foreach( $aOptions['exclude'] as $iKey => $sCategory ) {
				$oTitle = Title::newFromText( $sCategory );
				if ( !$oTitle ) {
					continue;
				}
				$aOptions['exclude'][$iKey] = str_replace( ' ', '_', $oTitle->getText() );
			}
			$sExcludes = implode( "','", $aOptions['exclude'] );
			$aConditions[] = "cl_to NOT IN ('$sExcludes')";
		}
		$aQueryOptions = array(
			'GROUP BY' => 'cl_to',
			'LIMIT' => $aOptions['count'],
			'ORDER BY' => 'COUNT(cl_to) DESC',
		);

		$oRes = $oDBr->select(
			$aTables,
			$aFields,
			$aConditions,
			__METHOD__,
			$aQueryOptions
		);

		if(!$oRes) {
			return array();
		}

		foreach( $oRes as $oRow ) {
			if( !$oCategoryTitle = Title::newFromText($oRow->cl_to, NS_CATEGORY) ) {
				continue;
			}
			$aData[$oRow->cl_to] = (object) array(
				'tagname' => $oCategoryTitle->getText(),
				'count' => $oRow->count,
				'link' => $oCategoryTitle->getFullURL(),
			);
		}
		return $aData;
	}
}
