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
abstract class TagCloudBaseViewHandler implements TagCloudValidator {
	protected $aOptions = array();
	protected $oTitle = null;
	protected $aData = array();
	protected $iSmallestCount = 0;
	protected $iBiggestCount = 0;

	public static function sanitizeArrayEntry( $sInput, &$aArgs, Parser $oParser, $oErrorListView ) {
		$aArgs['showcount'] = BsCore::sanitizeArrayEntry(
			$aArgs,
			'showcount',
			false,
			BsPARAMTYPE::BOOL
		);
		$aArgs['float'] = BsCore::sanitizeArrayEntry(
			$aArgs,
			'float',
			'none',
			BsPARAMTYPE::STRING
		);
		$aArgs['width'] = BsCore::sanitizeArrayEntry(
			$aArgs,
			'width',
			300,
			BsPARAMTYPE::INT
		);
		$aArgs['widthinpercent'] = BsCore::sanitizeArrayEntry(
			$aArgs,
			'widthinpercent',
			false,
			BsPARAMTYPE::BOOL
		);
		$aArgs['title'] = BsCore::sanitizeArrayEntry(
			$aArgs,
			'title',
			'',
			BsPARAMTYPE::STRING
		);
	}

	public static function validateArrayEntry( $sInput, &$aArgs, Parser $oParser, $oErrorListView ) {
		$oValidationResult = BsValidator::isValid(
			'PositiveInteger',
			$aArgs['width'],
			array( 'fullResponse' => true )
		);
		if ( $oValidationResult->getErrorCode() ) {
			$oErrorListView->addItem(new ViewTagError(
				$oValidationResult->getI18N()
			));
		}
	}

	public function __construct( $aOptions, $aData = array(), Title $oTitle = null ) {
		$this->aOptions = $aOptions;
		$this->oTitle = $oTitle;
		$this->aData = $aData;
	}

	protected function addModuleScipts( $sModule ) {
		TagCloud::addModuleScripts( $sModule );
	}

	protected function addModuleStyles( $sModule ) {
		TagCloud::addModuleStyles( $sModule );
	}

	public function execute() {
		return ':)';
	}

	public function calculateCount( $aData ) {
		if( empty($aData) ) {
			return 0;
		}
		usort( $aData, function($oA, $oB) {
			if( $oA->count === $oB->count ) {
				return 0;
			}
			return $oA->count < $oB->count ? -1 : 1;
		});
		$this->iSmallestCount = (int) $aData[0]->count;
		$this->iBiggestCount = (int) $aData[count($aData)-1]->count;
	}

	public function getTagSizeLogarithmic( $iCount, $mincount, $maxcount, $minsize, $maxsize, $tresholds ) {
		if( !is_int($tresholds) || $tresholds<2 ) {
			$tresholds = $maxsize-$minsize;
			$treshold = 1;
		} else {
			$treshold = ($maxsize-$minsize)/($tresholds-1);
		}
			$a = $tresholds*log($iCount - $mincount+2)/log($maxcount - $mincount+2)-1;
		return round($minsize+round($a)*$treshold);
	}
}
