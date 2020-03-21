<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class ImportBarangays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:barangays {--province=} {--city=} {--file=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports barangays from a CSV file in storage/app/csv/barangays/province-city.csv';

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
     * @return mixed
     */
    public function handle()
    {   
        $province = $this->option('province');
        $city = $this->option('city');
        $file = $this->option('file'); // filename.csv
  
        $province_id = DB::table('provinces')
            ->where('name', $province)
            ->value('id');

        $city_id = DB::table('cities')
            ->where('name', $city)
            ->value('id');

        if (!$province_id) {
            echo "Province does not exist. Creating now..\n";
            $province_id = DB::table('provinces')->insertGetId(['name' => $province]);
        }

        if (!$city_id) {
            echo "City does not exist. Creating now..\n";
            $city_id = DB::table('cities')->insertGetId(['name' => $city, 'province_id' => $province_id]);
        }

        $handle = fopen(storage_path('app/csv/barangays/' . $file), "r");
        $header = true;

        $barangay_count = DB::table('barangays')
            ->where('city_id', $city_id)
            ->count();

        if ($barangay_count === 0) {
            $count = 0;
            echo "Now importing barangays..";
            while ($line = fgetcsv($handle, 1000, ",")) {
                DB::table('barangays')->insert([
                    'city_id' => $city_id,
                    'name' => $line[0]
                ]);

                echo $line[0] . "\n";
                $count += 1;
            }
            echo "Done importing {$count} barangays.";
        } else {
            echo "There are already existing barangays for the selected city.\n";
        }
    }
}
