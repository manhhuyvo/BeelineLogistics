<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\CurrencyAndCountryEnum;
use App\Enums\GeneralEnum;
use App\Enums\ResponseMessageEnum;
use App\Models\CountryServiceConfiguration;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CountryServiceConfigurationController extends Controller
{
    /** Display list of configurations for edit */
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

    /** Handle request for updating default configuration of country and service */
    public function update(Request $request)
    {
        // Validate the request coming
        $validation = $this->validateRequest($request);
                
        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->back()->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // We only want to take necessary fields
        $data = $this->formatRequestData($request);
        $country = Arr::pull($data, 'country');

        // Start Database transaction
        DB::beginTransaction();

        foreach ($data as $field => $value) {   
            if ($field == 'country') {
                continue;
            }         

            $config = CountryServiceConfiguration::where('country', $country)
            ->where('service', $field)
            ->first();

            if (!$config) {
                $config = new CountryServiceConfiguration([
                    'default_supplier_id' => $value,
                    'country' => $country,
                    'service' => $field,
                ]);
            } else {
                $config->default_supplier_id = $value;
            }

            if (!$config->save()) {
                // Rollback if failed
                DB::rollBack();
                $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_UPDATE_RECORD)->send();
    
                return redirect()->back()->with([
                    'response' => $responseData,
                    'request' => $request->all(),
                ]);
            }
        }

        // If we come to this part, that means all the data have been updated successfully
        DB::commit();
        $responseData = viewResponseFormat()->success()->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();

        return redirect()->back()->with(['response' => $responseData]);
    }

    /** Validate the request for updating default configuration of country and service */
    private function validateRequest(Request $request)
    {
        $neededKeys = Arr::collapse([['country'], array_keys(GeneralEnum::MAP_SERVICES)]);
        $data = Arr::only($request->all(), $neededKeys);

        $validatedKeys = collect(GeneralEnum::MAP_SERVICES)
                    ->map(function($service) {
                        return ["required", "exists:App\Models\Supplier,id"];
                    })
                    ->toArray();

        $validatedKeys = array_merge(
            [
                'country' => ["required", Rule::in(array_keys(CurrencyAndCountryEnum::MAP_COUNTRIES))]
            ],
            $validatedKeys,
        );

        $validator = Validator::make($data, $validatedKeys);

        return $validator;
    }

    /** Format the request for action */
    private function formatRequestData(Request $request)
    {
        $data = $request->all();
        //Avoid null values
        foreach ($data as $key => $value) {
            if ($value) {
                continue;
            }
            
            $data[$key] = "";
        }
        $neededKeys = Arr::collapse([['country'], array_keys(GeneralEnum::MAP_SERVICES)]);

        return Arr::only($data, $neededKeys);
    }
}
