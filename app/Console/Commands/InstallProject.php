<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class InstallProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:project {project : Project folder name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Laravel based project in server';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $project = $this->argument('project');

        Log::info("Start initialization script for project $project");
        if(!file_exists("/var/www/$project")) {
            throw new Exception("Project does not exist.", 1);
        } 
        Log::info("Copy .env file");
        shell_exec("cd /var/www/$project");
        // shell_exec("cp .env.example .env");

        Log::info("Generate key for project $project");
        shell_exec("php artisan key:generate");

        Log::info("Install composer package for project $project");
        shell_exec("composer install");
        shell_exec("php artisan storage:link");

        Log::info("Migrate databases for $project");
        shell_exec("php artisan migrate");
        // create database
        // run migration
        // - Setup Nginx config for new project
        return 0;
    }
}
