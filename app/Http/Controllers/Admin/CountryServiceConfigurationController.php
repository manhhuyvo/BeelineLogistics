<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\CurrencyAndCountryEnum;
use App\Enums\GeneralEnum;
use App\Models\CountryServiceConfiguration;
use Illuminate\Support\Facades\DB;

class CountryServiceConfigurationController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        // If currently there is no configs for a country, then we create one
        foreach (CurrencyAndCountryEnum::MAP_COUNTRIES as $country => $name) {
            foreach (GeneralEnum::MAP_SERVICES as $service => $serviceName) {
                $config = CountryServiceConfiguration::where('country', $country)
                            ->where('service', $service)
                            ->first();
                
                if (!$config) {
                    $config = new CountryServiceConfiguration([
                        'default_supplier_id' => 0,
                        'country' => $country,
                        'service' => $service,
                    ]);
                    $config->save();

                    $data[] = $config;
                }
            }
        }
        
        // Then we get the list of all configurations and format it
        $allConfigs = CountryServiceConfiguration::all();
        $allConfigs = collect($allConfigs)
                    ->groupBy('country')
                    ->map(function ($row) {
                        return collect($row)->mapWithKeys(function($record, $i) {
                                return [$record['service'] => $record['default_supplier_id']];
                            })
                            ->toArray();
                    })
                    ->toArray();

        return view('admin.country-service-config.index', [
            'countries' => CurrencyAndCountryEnum::MAP_COUNTRIES,
            'services' => GeneralEnum::MAP_SERVICES,
            'suppliersList' => getFormattedSuppliersList(),
            'configsList' => $allConfigs,
        ]);
    }

    public function update(Request $request)
    {
        dd($request->all());
    }
}
