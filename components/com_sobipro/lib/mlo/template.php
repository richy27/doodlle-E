<?php
/**
 * @version: $Id: template.php 3149 2013-02-15 16:26:23Z Radek Suski $
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

 * $Date: 2013-02-15 17:26:23 +0100 (Fri, 15 Feb 2013) $
 * $Revision: 3149 $
 * $Author: Radek Suski $
 * $HeadURL: file:///opt/svn/SobiPro/Component/branches/SobiPro-1.1/Site/lib/mlo/template.php $
 */

defined( 'SOBIPRO' ) || exit( 'Restricted access' );

/**
 * @author Radek Suski
 * @version 1.0
 * @created 28-Oct-2009 09:08:04 AM
 */
interface SPTemplate
{
	public function display();

	/**
	 * @var array $data
	 */
	public function setData( $data );

	/**
	 * @var string $template
	 */
	public function setTemplate( $template );

	/**
	 * @var SPFrontView $proxy
	 */
	public function setProxy( &$proxy );

	/**
	 * @param string $type the listing type to set
	 */
	public function setType( $type );
}
