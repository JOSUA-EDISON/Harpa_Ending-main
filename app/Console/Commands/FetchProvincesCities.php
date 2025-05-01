<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kavist\RajaOngkir\Facades\RajaOngkir;
use App\Models\Province;
use App\Models\City;
use Illuminate\Support\Facades\Log;

class FetchProvincesCities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipping:fetch-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch provinces and cities data from RajaOngkir API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to fetch shipping data from RajaOngkir...');

        try {
            // Fetch provinces
            $this->fetchProvinces();

            // Fetch cities
            $this->fetchCities();

            $this->info('Provinces and cities data fetched successfully!');
            return 0;
        } catch (\Exception $e) {
            $this->error('Error fetching data: ' . $e->getMessage());
            Log::error('Error fetching RajaOngkir data: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Fetch provinces data from RajaOngkir API
     */
    private function fetchProvinces()
    {
        $this->info('Fetching provinces data...');

        $provincesData = RajaOngkir::provinsi()->all();
        $bar = $this->output->createProgressBar(count($provincesData));
        $bar->start();

        $insertedCount = 0;

        foreach ($provincesData as $province) {
            Province::updateOrCreate(
                ['province_id' => $province['province_id']],
                [
                    'name' => $province['province'],
                ]
            );

            $insertedCount++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Inserted/updated {$insertedCount} provinces.");
    }

    /**
     * Fetch cities data from RajaOngkir API
     */
    private function fetchCities()
    {
        $this->info('Fetching cities data...');

        $citiesData = RajaOngkir::kota()->all();
        $bar = $this->output->createProgressBar(count($citiesData));
        $bar->start();

        $insertedCount = 0;

        foreach ($citiesData as $city) {
            City::updateOrCreate(
                ['city_id' => $city['city_id']],
                [
                    'province_id' => $city['province_id'],
                    'name' => $city['city_name'],
                    'type' => $city['type'],
                ]
            );

            $insertedCount++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Inserted/updated {$insertedCount} cities.");
    }
}
