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
class Settings_Controller extends Administration_Controller {

	public function index()
	{
		$themes = array();
		if ($dh = opendir(THEMEPATH))
		{
			while(($theme = readdir($dh)) !== FALSE)
			{
				$path = THEMEPATH.$theme.'/theme.xml';
				if (is_file($path))
				{
					$xml = simplexml_load_file($path);
					$themes[$theme] = (string) $xml->name;
				}
			}
		}

		$this->head->title->append(__('Settings'));

		$this->template->title = __('Settings');
	    $this->template->content = View::factory('settings/settings', array(
    		'site_title' => config::get('s7n.site_title'),
	    	'theme' => config::get('s7n.theme'),
	    	'themes' => $themes
	    ));
	}

    public function save()
	{
        if($_POST)
		{
			// Site Title
			config::set('s7n.site_title', $this->input->post('site_title'));

			// Site Title
			config::set('s7n.theme', $this->input->post('theme'));

			message::info(__('Settings edited successfully'), 'admin/settings');
        }

        url::redirect('admin/settings');
    }

}