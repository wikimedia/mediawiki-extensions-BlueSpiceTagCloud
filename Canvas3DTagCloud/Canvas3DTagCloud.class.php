<?php

/**
 * Canvas3DTagCloud extension for BlueSpice
 *
 * Plugin Used: http://www.goat1000.com/tagcanvas.php
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
 * @package    BlueSpiceCanvas3DTagCloud
 * @subpackage Canvas3DTagCloud
 * @copyright  Copyright (C) 2016 Hallo Welt! GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License v3
 * @filesource
 */

/**
 * Base class for Canvas3DTagCloud extension
 * @package BlueSpice_Extensions
 * @subpackage Canvas3DTagCloud
 */
class Canvas3DTagCloud extends BsExtensionMW {

	/**
	 * Initialization of Canvas3DTagCloud extension
	 */
	protected function initExt() {
		wfProfileIn('BS::' . __METHOD__);

		$this->setHook( 'BSTagCloudRegisterHandlers' );

		wfProfileOut('BS::' . __METHOD__);
	}

	public function onBSTagCloudRegisterHandlers( &$aHandlers,
			&$sDefaultHandlerType, &$aViewHandlers, &$sDefaultViewHandlerType ){
		return $aViewHandlers['canvas3d'] = array(
			'class' => 'TagCloudCanvas3DViewHandler',
		);
	}
}