<?php
/**
 * @version: $Id: helper.php 3489 2013-06-28 07:51:07Z Radek Suski $
 * @package: SobiPro Library
 * @author
 * Name: Sigrid Suski & Radek Suski, Sigsiu.NET GmbH
 * Email: sobi[at]sigsiu.net
 * Url: http://www.Sigsiu.NET
 * @copyright Copyright (C) 2006 - 2013 Sigsiu.NET GmbH (http://www.sigsiu.net). All rights reserved.
 * @license GNU/LGPL Version 3
 * This program is free software: you can redistribute it and/or modify it under the terms of the GNU Lesser General Public License version 3 as published by the Free Software Foundation, and under the additional terms according section 7 of GPL v3.
 * See http://www.gnu.org/licenses/lgpl.html and http://sobipro.sigsiu.net/licenses.
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * $Date: 2013-06-28 09:51:07 +0200 (Fri, 28 Jun 2013) $
 * $Revision: 3489 $
 * $Author: Radek Suski $
 * $HeadURL: file:///opt/svn/SobiPro/Component/branches/SobiPro-1.1/Site/lib/cms/joomla16/base/helper.php $
 */

defined( 'SOBIPRO' ) || exit( 'Restricted access' );
require_once dirname( __FILE__ ) . '/../../joomla_common/base/helper.php';
/**
 * @author Radek Suski
 * @version 1.0
 * @created 08-Jul-2008 9:43:25 AM
 */
class SPCMSHelper extends SPJoomlaCMSHelper
{
	public static function & getInstance()
	{
		static $SPCMSHelper = false;
		if ( !$SPCMSHelper || !( $SPCMSHelper instanceof self ) ) {
			$SPCMSHelper = new self();
		}
		return $SPCMSHelper;
	}

	/**
	 * Return min or recommend Joomla! version
	 * @param $recommended
	 * @return unknown_type
	 */
	public static function minCmsVersion( $recommended = false )
	{
		return $recommended ? array( 'major' => 2, 'minor' => 5, 'build' => 11 ) : array( 'major' => 2, 'minor' => 5, 'build' => 0 );
	}

	/**
	 * Returns Joomla! version
	 * @param bool $version
	 * @return array
	 */
	public static function cmsVersion( $version = null )
	{
		if ( ( $version ) && !( in_array( $version, array( 'Joomla 1.6', 'Joomla 1.7', 'Joomla 2.5' ) ) ) ) {
			return 'Joomla 2.5';
		}
		$version = new JVersion();
		$v = explode( '.', $version->RELEASE );
		return array( 'major' => $v[ 0 ], 'minor' => $v[ 1 ], 'build' => $version->DEV_LEVEL, 'rev' => 0 );
	}

	/**
	 * Returns specified Joomla! configuration setting
	 * @param string $setting
	 * @return string
	 */
	public static function cmsSetting( $setting )
	{
		static $cfg;
		if ( !$cfg ) {
			$cfg = new JConfig(); // was ein Unsinn der da macht
		}
		switch ( $setting ) {
			case 'charset':
				$r = JFactory::getDocument()->getCharset();
				break;
			default:
				$r = isset( $cfg->$setting ) ? $cfg->$setting : false;
				break;
		}
		return $r;
	}

	/**
	 * @param $name
	 * @param $active
	 * @param $nouser
	 * @param $javascript
	 * @param $order
	 * @param $reg
	 * @return unknown_type
	 */
	public static function userSelect( $name, $active, $nouser = 0, $javascript = null, $order = 'name', $reg = 0 )
	{
		require_once 'userform.php';
		$s = array( 'id' => str_replace( '.', '_', $name ), 'name' => $name, 'value' => $active );
		$f = new SPFormFieldUser();
		$f->setup( $s );
		return $f->input;
	}

	public function getLanguages()
	{
		static $return = array();
		if ( !( count( $return ) ) ) {
			$langs = JLanguageHelper::getLanguages();
			$return = array();
			foreach ( $langs as $lang ) {
				$return[ $lang->lang_code ] = $lang->sef;
			}
		}
		return $return;
	}
}
