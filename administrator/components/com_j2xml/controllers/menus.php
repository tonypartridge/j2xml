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

// no direct access
defined('_JEXEC') or die();

/**
 * Menus controller class.
 *
 * @version 3.7.192
 * @since 3.6.164
 */
class J2XMLControllerMenus extends JControllerLegacy
{

	function __construct ($default = array())
	{
		parent::__construct();
	}

	public function display ($cachable = false, $urlparams = false)
	{
		$this->input->set('view', 'menus');
		parent::display($cachable, $urlparams);
	}

	/**
	 * Export menus in XML format
	 */
	function export ()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');

		$cid = $this->input->post->get('cid', array(
				0
		), 'array');
		$ids = 'cid=' . implode(',', $cid);
		$this->setRedirect('index.php?option=com_j2xml&task=menus.display&format=raw&' . $ids);
	}
}