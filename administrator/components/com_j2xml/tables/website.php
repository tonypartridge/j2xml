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
 * Website table
 *
 * @version 3.7.192
 * @since 1.5.3
 */
class J2XMLTableWebsite extends JTable
{

	function __construct (&$_db)
	{
		JLog::add(new JLogEntry(__METHOD__, JLog::DEBUG, 'com_j2xml'));

		$this->checked_out_time = $_db->getNullDate();
		parent::__construct('#__j2xml_websites', 'id', $_db);
	}

	/**
	 * Method to set the publishing state for a row or list of rows in the
	 * database
	 * table.
	 * The method respects checked out rows by other users and will attempt
	 * to checkin rows that it can after adjustments are made.
	 *
	 * @param
	 *        	mixed An optional array of primary key values to update. If
	 *        	not
	 *        	set the instance property value is used.
	 * @param
	 *        	integer The publishing state. eg. [0 = unpublished, 1 =
	 *        	published]
	 * @param
	 *        	integer The user id of the user performing the operation.
	 * @return boolean True on success.
	 * @since 2.5.85
	 */
	public function publish ($pks = null, $state = 1, $userId = 0)
	{
		JLog::add(new JLogEntry(__METHOD__, JLog::DEBUG, 'com_j2xml'));

		// Initialise variables.
		$k = $this->_tbl_key;

		// Sanitize input.
		Joomla\Utilities\ArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state = (int) $state;

		// If there are no primary keys set check to see if the instance key is
		// set.
		if (empty($pks))
		{
			if ($this->$k)
			{
				$pks = array(
						$this->$k
				);
			}
			// Nothing to set publishing state on, return false.
			else
			{
				throw new Exception(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		// Build the WHERE clause for the primary keys.
		$where = $k . '=' . implode(' OR ' . $k . '=', $pks);

		// Determine if there is checkin support for the table.
		if (! property_exists($this, 'checked_out') || ! property_exists($this, 'checked_out_time'))
		{
			$checkin = '';
		}
		else
		{
			$checkin = ' AND (checked_out = 0 OR checked_out = ' . (int) $userId . ')';
		}

		// Update the publishing state for rows with the given primary keys.
		$this->_db->setQuery(
				'UPDATE ' . $this->_db->quoteName($this->_tbl) . ' SET ' . $this->_db->quoteName('state') . ' = ' . (int) $state . ' WHERE (' . $where .
						 ')' . $checkin);
		$this->_db->query();

		// Check for a database error.
		if ($this->_db->getErrorNum())
		{
			throw new Exception($this->_db->getErrorMsg());
			return false;
		}

		// If checkin is supported and all rows were adjusted, check them in.
		if ($checkin && (count($pks) == $this->_db->getAffectedRows()))
		{
			// Checkin the rows.
			foreach ($pks as $pk)
			{
				$this->checkin($pk);
			}
		}

		// If the JTable instance value is in the list of primary keys that were
		// set, set the instance.
		if (in_array($this->$k, $pks))
		{
			$this->state = $state;
		}

		$this->setError('');
		return true;
	}
}
