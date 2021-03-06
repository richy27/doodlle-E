<?php
/**
 * @version: $Id: spsection.php 3133 2013-02-11 15:46:39Z Radek Suski $
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

 * $Date: 2013-02-11 16:46:39 +0100 (Mon, 11 Feb 2013) $
 * $Revision: 3133 $
 * $Author: Radek Suski $
 * $HeadURL: file:///opt/svn/SobiPro/Component/branches/SobiPro-1.1/Site/lib/cms/joomla16/elements/spsection.php $
 */

//JLoader::import( 'joomla.html.parameter.element' );
require_once dirname( __FILE__ ) . '/../../joomla_common/elements/spsection.php';

class JFormFieldSPSection extends JFormField
{
	protected $type = 'spsection';

	protected function getInput()
	{
		return str_replace( 'urlparams', 'jform[request]', JElementSPSection::getInstance()->fetchElement( preg_replace( '/^sp/', null, $this->type ) ) );
	}
}
