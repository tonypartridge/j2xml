<?php
/**
 * @package		J2XML
 * @subpackage	com_j2xml
 *
 * @author		Helios Ciancio <info (at) eshiol (dot) it>
 * @link		http://www.eshiol.it
 * @copyright	Copyright (C) 2010 - 2019 Helios Ciancio. All Rights Reserved
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL v3
 * J2XML is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// No direct access
defined('_JEXEC') or die();

jimport('joomla.application.component.controllerform');

/**
 * Website controller class.
 *
 * @version 3.7.192
 * @since 1.5.3
 */
class J2XMLControllerWebsite extends JControllerForm
{

	/**
	 *
	 * @var string The prefix to use with controller messages.
	 * @since 1.6
	 */
	protected $text_prefix = 'COM_J2XML_WEBSITE';
}
