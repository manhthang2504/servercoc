<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeployNewProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:new {project : the project folder name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploy new project';

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

        $repoFolder = config('app.repo_folder');
        if(!file_exists('/var/' . $repoFolder)) {
            throw new Exception("Repo folder does not exist. Please make repo folder, give permission to www-data group and run again.", 1);
        }

        Log::info("Start deploying project $project");
        mkdir("/var/$repoFolder/$project.git", 0775, true);
        shell_exec("cd /var/$repoFolder/$project.git && git init --bare");
        Log::info("Created /var/$repoFolder/$project.git");

        Log::info("Editing Git hook");
        $content = "#!/bin/sh".PHP_EOL;
        $content = $content . "git --work-tree=/var/www/$project --git-dir=/var/$repoFolder/$project.git checkout -f" .PHP_EOL;
        file_put_contents("/var/$repoFolder/$project.git/hooks/post-receive", $content);
        shell_exec("chmod +x /var/$repoFolder/$project.git/hooks/post-receive");
        
        Log::info("Make www folder: /var/www/$project");
        mkdir("/var/www/$project", 0775, true);
        shell_exec("chown -R www-data:www-data /var/www/$project");
        shell_exec("chmod -R 775 /var/www/$project");
        
        Log::info("Setup project $project completed!");

        return 0;
    }
}
