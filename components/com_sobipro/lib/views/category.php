<?php
/**
 * @version: $Id: category.php 3397 2013-05-08 10:06:53Z Radek Suski $
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

 * $Date: 2013-05-08 12:06:53 +0200 (Wed, 08 May 2013) $
 * $Revision: 3397 $
 * $Author: Radek Suski $
 * $HeadURL: file:///opt/svn/SobiPro/Component/branches/SobiPro-1.1/Site/lib/views/category.php $
 */

defined( 'SOBIPRO' ) || exit( 'Restricted access' );

SPLoader::loadView( 'section' );

/**
 * @author Radek Suski
 * @version 1.0
 * @created 10-Jan-2009 5:15:43 PM
 */
class SPCategoryView extends SPSectionView implements SPView
{
	public function chooser()
	{
		$pid = $this->get( 'category.parent' );
		$path = null;
		if( !$pid ) {
			$pid = SPRequest::sid();
		}
		$this->assign( $pid, 'parent' );
		$id = $this->get( 'category.id' );
		$id = $id ? $id : $pid;
		if( $id ) {
			$path = $this->parentPath( $id );
		}
		$this->assign( $path, 'parent_path' );
		$this->assign( Sobi::Url( array( 'task' => 'category.parents', 'out' => 'json', 'format' => 'raw' ), true ), 'parent_ajax_url' );
		/* @TODO  */
		$tpl = str_replace( implode( '/', array( 'usr', 'templates', 'category' ) ), 'views/tpl/', $this->_template.'.php' );
		Sobi::Trigger( 'Display', $this->name(), array( &$this ) );
		include( $tpl );
		Sobi::Trigger( 'AfterDisplay', $this->name() );
	}

	public function icon()
	{
		/* @TODO  */
		$tpl = str_replace( implode( DS, array( 'usr', 'templates', 'category' ) ), DS.'views'.DS .'tpl'.DS, $this->_template.'.php' );
		include( $tpl );
	}
}
