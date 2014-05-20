<?php

namespace Greg\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class Build extends Command
{

	protected $fs;

    const TEMPLATE_DIR = 'templates/default/files/';
    const TEMPLATE_PARTIALS_DIR = 'templates/default/partials/';
    const TEMPLATE_CLASS_NAME = 'Package';
    const TARGET_TEMPLATE_DIR = '/home/mattkirwan/Projects/work/lv-shipping-api/api/src/Api/';

	protected function configure()
	{
		$this->fs = new Filesystem();

        $this
            ->setName('build')
            ->setDescription('Greg, please build a controller.')
            ->addArgument(
            	'controllers',
            	InputArgument::IS_ARRAY | InputArgument::REQUIRED,
            	'The name of the controllers you would like Greg to build.'
            );
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
        $controllers = $input->getArgument('controllers');

        if(!$this->fs->exists(self::TEMPLATE_DIR))
        {
            echo 'TEMPLATE DIR DOESNT EXIST';
            exit;
        }

        if(!$this->fs->exists(self::TARGET_TEMPLATE_DIR))
        {
        	echo 'TARGET TEMPLATE DIR DOESNT EXIST';
            exit;
        }

        $this->fs->mirror(self::TEMPLATE_DIR, self::TARGET_TEMPLATE_DIR);

        $template = new \RecursiveDirectoryIterator(self::TARGET_TEMPLATE_DIR.self::TEMPLATE_CLASS_NAME);

        $iter = new \RecursiveIteratorIterator($template, \RecursiveIteratorIterator::SELF_FIRST);


        if($controllers)
        {
        	foreach($controllers as $controller)
        	{
        		
        		$controllerName = strstr($controller, '[', true);
        		
                foreach($iter as $name => $object)
                {
                    if(!$iter->isDot())
                    {
                        $old_path = $name;
                        $new_path = str_replace(self::TEMPLATE_CLASS_NAME, ucfirst($controllerName), $old_path);
                        

                        if($iter->isDir())
                        {
                            $this->fs->mkdir(self::TARGET_TEMPLATE_DIR.ucfirst($controllerName));
                            $this->fs->mkdir($new_path);
                            continue;
                        }
                        else
                        {
                            $this->fs->rename($old_path, $new_path, true);
                            $new_content = str_replace(
                                array(
                                    'package',
                                    'Package'),
                                array(
                                    strtolower($controllerName),
                                    ucfirst($controllerName)
                                ),
                                file_get_contents($new_path)
                            );

                            if(!file_put_contents($new_path, $new_content))
                            {
                                $output->writeLn('Failed to create file: '.$new_path);
                            }
                            else
                            {
                                $output->writeLn('Created file: '.$new_path);

                                preg_match_all("/([crudl])/", strstr($controller, '[', false), $matches);
                                $methodNames = $matches[0];

                                $new_controller_methods = '';

                                $partials = '';

                                foreach($methodNames as $method)
                                {
                                    switch($method)
                                    {
                                        case 'c':
                                            $partials .= file_get_contents(self::TEMPLATE_PARTIALS_DIR."create");
                                        break;
                                        case 'r':
                                            $partials .= file_get_contents(self::TEMPLATE_PARTIALS_DIR."read");
                                        break;
                                        case 'u':
                                            $partials .= file_get_contents(self::TEMPLATE_PARTIALS_DIR."update");
                                        break;
                                        case 'd':
                                            $partials .= file_get_contents(self::TEMPLATE_PARTIALS_DIR."delete");
                                        break;
                                        case 'l':
                                            $partials .= file_get_contents(self::TEMPLATE_PARTIALS_DIR."list");
                                        break;
                                    }
                                }

                                $new_file_with_partials = str_replace('{{methods}}', $partials, file_get_contents($new_path));

                                file_put_contents($new_path, $new_file_with_partials);
                            }
                        }
                    }
                }
        	}
        }  
	}
}