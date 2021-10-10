<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;

class Qiniu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qiniu:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $disk = \Storage::disk('qiniu');
        $newFileName  = md5( time().rand(1000,9999));


//        $filePath='/public/storage/head/image001.jpg';
//        $filename = $disk->putFile($newFileName,base_path().$filePath);
//        $this->info($filename);
//        $img_url = $disk->url($filename);
//        $this->info($img_url);

        $this->info(json_encode($disk->makeDirectory('head')));
        return 0;
    }
}
