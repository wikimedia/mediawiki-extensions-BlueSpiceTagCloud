<?php

/**
 * TagCloud extension for BlueSpice
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
 * @package    BlueSpiceTagCloud
 * @subpackage TagCloud
 * @copyright  Copyright (C) 2016 Hallo Welt! GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License v3
 * @filesource
 */

/**
 * Base class for TagCloud extension
 * @package BlueSpice_Extensions
 * @subpackage TagCloud
 */
class TagCloud extends BsExtensionMW {
	protected static $aHandlers = array(
		'categories' => array( 'class' => 'TagCloudCategoryHandler' ),
	);
	protected static $sDefaultHandlerType = 'categories';
	protected static $aViewHandlers = array(
		'text' => array( 'class' => 'TagCloudTextViewHandler' ),
		'list' => array( 'class' => 'TagCloudListViewHandler' ),
		'canvas3d' => [ 'class' => 'TagCloudCanvas3DViewHandler' ],
	);
	protected static $sDefaultViewHandlerType = 'text';
	protected static $bRegister = false;
	protected static $aAddModuleScripts = array();
	protected static $aAddModuleStyles = array();

	/**
	 * Initialization of TagCloud extension
	 */
	protected function initExt() {
		$this->setHook( 'ParserFirstCallInit' );
		$this->setHook( 'BeforePageDisplay' );
	}

	protected static function runRegisterHandlers( $bForceReload = false ) {
		if( static::$bRegister && !$bForceReload ) {
			return true;
		}

		$b = Hooks::run( 'BSTagCloudRegisterHandlers', array(
			&self::$aHandlers,
			&self::$sDefaultHandlerType,
			&self::$aViewHandlers,
			&self::$sDefaultViewHandlerType,
		));

		return $b ? static::$bRegister = true : $b;
	}

	public static function isRegisteredHandler( $sType, $sHVarName = 'aHandlers' ) {
		if( !static::runRegisterHandlers() ) {
			return false;
		}
		$aHandlers = self::$$sHVarName;
		return isset( $aHandlers[$sType] );
	}

	public static function getRegisteredHandler( $sType, $sHVarName = 'aHandlers' ) {
		if( !self::isRegisteredHandler($sType, $sHVarName) ) {
			return false;
		}
		$aHandlers = self::$$sHVarName;
		return $aHandlers[$sType];
	}

	protected function callHandlerMethod( $sHandlerType, $aHandler, $sMethod, $aArgs ) {
		if( !isset($aHandler['class']) ) {
			throw new BsException(
				__METHOD__." - Handler $sHandlerType has no class."
			);
		}
		$sCallable = "{$aHandler['class']}::$sMethod";

		if( !is_callable($sCallable) ) {
			throw new BsException(
				__METHOD__." - Handler $sHandlerType: $sCallable is not callable."
			);
		}

		return call_user_func_array( $sCallable, $aArgs );
	}
	
	public function collectData( $sHandlerType, $aHandler, $aOptions, $oTitle = null, $aData = array() ) {
		return $this->callHandlerMethod(
			$sHandlerType,
			$aHandler,
			'collectData',
			array( $aOptions, $aData, $oTitle )
		);
	}

	public static function addModuleStyles( $sModule ) {
		self::$aAddModuleStyles[] = $sModule; 
	}

	public static function addModuleScripts( $sModule ) {
		self::$aAddModuleScripts[] = $sModule; 
	}

	/**
	 * Registers a tag "bs:tagcloud" with the parser.
	 * @param Parser $oParser MediaWiki parser object
	 * @return bool true to allow other hooked methods to be executed. Always true.
	 */
	public function onParserFirstCallInit( &$oParser ) {
		$oParser->setHook( 'bs:tagcloud', array( $this, 'onTagTagCloud' ) );

		return true;
	}

	/**
	 * 
	 * @param OutputPage $oOutputPage
	 * @param Skin $oSkin
	 */
	public static function onBeforePageDisplay( &$oOutputPage, &$oSkin ) {
		$aModuleStyles = self::$aAddModuleStyles;
		$aModuleScripts = self::$aAddModuleScripts;
		if( !empty($aModuleStyles) ) {
			$aModuleStyles = array_unique( $aModuleStyles );
			$oOutputPage->addModuleStyles( $aModuleStyles );
		}
		if( !empty($aModuleScripts) ) {
			$aModuleScripts = array_unique( $aModuleScripts );
			$oOutputPage->addModuleScripts( $aModuleScripts );
		}
		return true;
	}

	/**
	 * Renders the TagCloud tag. Called by parser function.
	 * @param string $sInput Inner HTML of TagCloud tag. Not used.
	 * @param array $aArgs List of tag attributes.
	 * @param Parser $oParser MediaWiki parser object
	 * @return string HTML output that is to be displayed.
	 */
	public function onTagTagCloud( $sInput, $aArgs, $oParser ) {
		$oParser->disableCache();
		$oErrorListView = new ViewTagErrorList( $this );

		$aHandlers = array('type' => '', 'viewtype' => 'View');
		foreach( $aHandlers as $sKey => $sType ) {
			$sStatic = "sDefault{$sType}HandlerType";
			$aArgs[$sKey] = BsCore::sanitizeArrayEntry(
				$aArgs,
				$sKey,
				self::$$sStatic,
				BsPARAMTYPE::STRING
			);
		}

		$aHandlers = array(
			'type' => 'aHandlers',
			'viewtype' => 'aViewHandlers'
		);
		foreach( $aHandlers as $sKey => $sHandlerVarName ) {
			if( !$aHandler = self::getRegisteredHandler( $aArgs[$sKey], $sHandlerVarName ) ) {
				$oErrorListView->addItem(
					new ViewTagError( wfMessage( 'bs-tagcloud-error-tagtype' )
						->params( $sKey )
						->parse()
					)
				);
				continue;
			}
			$this->callHandlerMethod(
				$aArgs[$sKey],
				$aHandler,
				'sanitizeArrayEntry',
				array( $sInput, &$aArgs, $oParser, $oErrorListView ),
				$sHandlerVarName
			);
			$this->callHandlerMethod(
				$aArgs[$sKey],
				$aHandler,
				'validateArrayEntry',
				array( $sInput, &$aArgs, $oParser, $oErrorListView ),
				$sHandlerVarName
			);
		}
		if( $oErrorListView->hasEntries() ) {
			return $oErrorListView->execute();
		}
		$aData = $this->collectData(
			$aArgs['type'],
			self::getRegisteredHandler( $aArgs['type'] ),
			$aArgs,
			$oParser->getTitle()
		);

		$aViewHandler = self::getRegisteredHandler( $aArgs['viewtype'], 'aViewHandlers' );
		$oTagCloudView = new $aViewHandler['class'](
			$aArgs,
			$aData,
			$oParser->getTitle()
		);

		return $oTagCloudView->execute();
	}
}