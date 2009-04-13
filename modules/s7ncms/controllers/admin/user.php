<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * S7Ncms - www.s7n.de
 *
 * Copyright (c) 2007-2009, Eduard Baun <eduard at baun.de>
 * All rights reserved.
 *
 * See license.txt for full text and disclaimer
 *
 * @author Eduard Baun <eduard at baun.de>
 * @copyright Eduard Baun, 2007-2009
 * @version $Id$
 */
class User_Controller extends Administration_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->template->tasks = array(
			array('admin/user/newuser', 'New User')
		);

		$this->head->title->append('User');
		$this->template->title = html::anchor('admin/user', 'User').' | ';
	}
	
	public function index()
	{
		$this->template->content = View::factory('user/index', array('users' => ORM::factory('user')->find_all()));
		
		$this->head->title->append('All Users');
		$this->template->title .= 'All Users';
	}
	
	public function newuser()
	{
		$fields = array
		(
			'username' => '',
			'email' => '',
			'password' => '',
			'password_confirm' => '',
		);

		$errors = $fields;
			
		if ($_POST)
		{
			$user = ORM::factory('user');
			
			if ($user->validate($_POST, TRUE))
			{
				$user->add(ORM::factory('role', 'login'));
				$user->add(ORM::factory('role', 'admin'));
				$user->save();
				
				message::info('User created successfully', 'admin/user');
			}
			else
			{
				$fields = arr::overwrite($fields, $_POST->safe_array());
				$errors = arr::overwrite($errors, $_POST->errors());
			}
		}
		
		$this->template->content = View::factory('user/newuser', array('fields' => $fields, 'errors' => $errors));
		
		$this->head->title->append('New User');
		$this->template->title .= 'New User';
	}
	
	public function edit($id)
	{
		$fields = array
		(
			'username' => '',
			'email' => '',
			'password' => '',
			'password_confirm' => '',
		);

		$errors = $fields;
		
		$user = ORM::factory('user', (int) $id);
		
		if ( ! $user->loaded)
			Event::run('system.404');
			
		if ($_POST)
		{
			if ($user->validate_edit($_POST))
			{
				$user->email = $_POST['email'];
				if (!empty($_POST['password']))
					$user->password = $_POST['password'];
				
				$user->save();
				
				message::info('User edited successfully', 'admin/user');
			}
			else
			{
				$fields = arr::overwrite($fields, $_POST->as_array());
				$errors = arr::overwrite($errors, $_POST->errors());
			}
		}
		else
		{
			$fields = arr::overwrite($fields, ORM::factory('user', (int) $id)->as_array());
		}
		
		$this->template->content = View::factory('user/edit', array('user' => $user, 'fields' => $fields, 'errors' => $errors));

		$this->head->title->append('Edit User');
		$this->template->title .= 'Edit User';
	}
	
	public function delete($id)
	{
		$user = ORM::factory('user', (int) $id);
		
		if ($user->loaded)
		{
			if ($user->id === Auth::instance()->get_user()->id)
				message::error('You can\'t delete yourself', 'admin/user');
			
			$user->remove(ORM::factory('role', 'login'));
			$user->remove(ORM::factory('role', 'admin'));
			$user->delete();
			
			message::info('User deleted successfully', 'admin/user');
		}
		
		message::error('Invalid ID', 'admin/user');
	}

}
