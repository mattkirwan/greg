<?php

namespace Greg\Console;

use Greg\Console\Command;
use Symfony\Component\Console\Application;

class GregApplication extends Application
{
	public function __construct()
	{
		parent::__construct();

		$this->addCommands(array(
			new Command\Build(),
		));
	}
}