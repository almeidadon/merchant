<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Login_attempts
 *
 * This model serves to watch on all attempts to login on the site
 * (to protect the site from brute-force attack to user database)
 *
 * @package	Tank_auth
 * @author	Ilya Konyukhov (http://konyukhov.com/soft/)
 */
class Login_attempts extends CI_Model
{
	private $table_name = 'login_attempts';

	function __construct()
	{
		parent::__construct();

		$ci =& get_instance();
		$ci->consumer = $this->load->database('consumer', TRUE); //Uses the consumer database defined in the database.php
		$this->table_name = $ci->config->item('db_table_prefix', 'tank_auth').$this->table_name;
	}

	/**
	 * Get number of attempts to login occured from given IP-address or login
	 *
	 * @param	string
	 * @param	string
	 * @return	int
	 */
	function get_attempts_num($ip_address, $login)
	{
		$this->consumer->select('1', FALSE);
		$this->consumer->where('ip_address', $ip_address);
		if (strlen($login) > 0) $this->consumer->or_where('login', $login);

		$qres = $this->consumer->get($this->table_name);
		return $qres->num_rows();
	}

	/**
	 * Increase number of attempts for given IP-address and login
	 *
	 * @param	string
	 * @param	string
	 * @return	void
	 */
	function increase_attempt($ip_address, $login)
	{
		$this->consumer->insert($this->table_name, array('ip_address' => $ip_address, 'login' => $login));
	}

	/**
	 * Clear all attempt records for given IP-address and login.
	 * Also purge obsolete login attempts (to keep DB clear).
	 *
	 * @param	string
	 * @param	string
	 * @param	int
	 * @return	void
	 */
	function clear_attempts($ip_address, $login, $expire_period = 86400)
	{
		$this->consumer->where(array('ip_address' => $ip_address, 'login' => $login));

		// Purge obsolete login attempts
		$this->consumer->or_where('UNIX_TIMESTAMP(time) <', time() - $expire_period);

		$this->consumer->delete($this->table_name);
	}
}

/* End of file login_attempts.php */
/* Location: ./application/models/auth/login_attempts.php */