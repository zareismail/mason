<?php

namespace Zareismail\Mason\Cypress\Fragments;

use Zareismail\Cypress\Fragment;   

class Blank extends Fragment
{       
	public function title($request)
	{
		return static::fragment()->getUrl('test');
	}
}
