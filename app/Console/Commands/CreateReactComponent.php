<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateReactComponent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:react-component {classname} {--admin=false} {--create-store=false} {--path=} {--main=true}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a React component, a store and an action file for the component.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $classname = $this->argument('classname');
        $is_admin = ($this->option('admin') == 'true');
        $is_main = ($this->option('main') == 'true');
        $create_store = ($this->option('create-store') == 'true');

        $folder_name = empty($this->option('path')) ? snake_case($classname) : $this->option('path');
        $class_locator = str_replace('_', '-', $folder_name);

        if($is_main)
		{
			$react_class_template = file_get_contents(getcwd() . '/file_templates/MainReactClass');
		}
		else
		{
			$react_class_template = file_get_contents(getcwd() . '/file_templates/SubReactClass');
		}

        $react_file_output = str_replace('{ClassName}', $classname, $react_class_template);
        $react_file_output = str_replace('{ClassLocator}', $class_locator, $react_file_output);

        if($is_main)
		{
			$actions_file_output = str_replace('{ClassName}', $classname, file_get_contents(getcwd() . '/file_templates/ReactActions'));
		}

        // Create file for class
		$folder_path = getcwd() . '/resources/assets/js/' . (!$is_admin ? 'frontend' : 'admin') . '/components/' . $folder_name;
        if(!file_exists($folder_path))
		{
			mkdir($folder_path);
		}

        file_put_contents(getcwd() . '/resources/assets/js/' . (!$is_admin ? 'frontend' : 'admin') . '/components/' .
			$folder_name . '/' . $classname . '.js', $react_file_output);

		if($is_main and !empty($actions_file_output))
		{
			// Create actions file
			file_put_contents(getcwd() . '/resources/assets/js/' .
				(!$is_admin ? 'frontend' : 'admin') . '/actions/' . $classname . 'Actions.js', $actions_file_output);
		}

		// Create store
		if($create_store)
		{
			$folder_path = getcwd() . '/resources/assets/js/' . (!$is_admin ? 'frontend' : 'admin') . '/stores/' . $folder_name;
			if(!file_exists($folder_path))
			{
				mkdir($folder_path);
			}

			$store_output = str_replace('{ClassName}', $classname . 'Store',
				file_get_contents(getcwd() . '/file_templates/ReactStore'));

			file_put_contents($folder_path . '/' . $classname . 'Store.js', $store_output);
		}
	}
}
