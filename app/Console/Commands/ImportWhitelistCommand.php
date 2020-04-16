<?php

namespace App\Console\Commands;

use App\Whitelist;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use SplFileObject;

class ImportWhitelistCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whitelist:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'importa CPF\'s com permissão de cadastro';

    private $fileLocation;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->fileLocation = __DIR__ . '/../../../database/import/whitelist.csv';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $file = new SplFileObject($this->fileLocation);
        $file->setFlags(SplFileObject::READ_CSV);
        $list = [];
        foreach ($file as $key => $row) {
            if($key === 0) continue;
            list($cpf) = $row;
            $list[] = ['cpf' => $cpf, 'created_at' => now(), 'updated_at' => now()];
            echo $cpf . PHP_EOL;
        }
        DB::transaction(function () use ($list){
            Whitelist::truncate();
            Whitelist::insert($list);
        });

    }
}
