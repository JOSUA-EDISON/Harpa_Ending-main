<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings for RajaOngkir and Midtrans
        $this->seedDefaultSettings();
    }

    /**
     * Seed default settings
     */
    private function seedDefaultSettings(): void
    {
        $settings = [
            [
                'key' => 'rajaongkir_api_key',
                'value' => env('RAJAONGKIR_API_KEY', ''),
                'description' => 'RajaOngkir API Key'
            ],
            [
                'key' => 'rajaongkir_package',
                'value' => env('RAJAONGKIR_PACKAGE', 'starter'),
                'description' => 'RajaOngkir Package (starter, basic, pro)'
            ],
            [
                'key' => 'midtrans_merchant_id',
                'value' => env('MIDTRANS_MERCHANT_ID', ''),
                'description' => 'Midtrans Merchant ID'
            ],
            [
                'key' => 'midtrans_client_key',
                'value' => env('MIDTRANS_CLIENT_KEY', ''),
                'description' => 'Midtrans Client Key'
            ],
            [
                'key' => 'midtrans_server_key',
                'value' => env('MIDTRANS_SERVER_KEY', ''),
                'description' => 'Midtrans Server Key'
            ],
            [
                'key' => 'midtrans_is_production',
                'value' => env('MIDTRANS_IS_PRODUCTION', 'false'),
                'description' => 'Midtrans Production Mode (true/false)'
            ],
        ];

        $now = now();

        DB::table('settings')->insert(
            array_map(function ($setting) use ($now) {
                return array_merge($setting, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }, $settings)
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
