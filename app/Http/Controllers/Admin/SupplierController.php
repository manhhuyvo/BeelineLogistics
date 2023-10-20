<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Supplier\Meta as SupplierMeta;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Enums\ResponseMessageEnum;
use App\Enums\CurrencyAndCountryEnum;
use App\Enums\GeneralEnum;
use App\Enums\SupplierEnum;
use App\Enums\SupplierMetaEnum;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    /** Display the page for list of all suppliers */
    public function index(Request $request)
    {
        // Retrieve list of all first
        $allSuppliers = Supplier::with('account');

        // Validate the filter request
        $data = $request->all();
        if (!empty($data)){
            foreach($data as $key => $value) {
                if (empty($value) || $key == 'page' || $key == '_method') {
                    continue;
                }
                // Escape to prevent break
                $key = htmlspecialchars($key);
                $value = htmlspecialchars($value);

                // Add conditions
                $allSuppliers = $allSuppliers->where($key, 'like', "%$value%");
            }
        }

        // Then add filter into the query
        $allSuppliers = $allSuppliers->paginate($perpage = 50, $columns = ['*'], $pageName = 'page');
        $allSuppliers = $allSuppliers->appends(request()->except('page'));        
        $returnData = collect($allSuppliers)->only('data')->toArray();
        $paginationData = collect($allSuppliers)->except(['data'])->toArray();

        // Only cut the next and previous buttons if count > 7
        if (count($paginationData['links']) >= 7) {
            $paginationDataLinks = collect($paginationData['links'])->filter(function($each) {
                return !Str::contains($each['label'], ['Previous', 'Next']);
            });

            $paginationData['links'] = $paginationDataLinks;
        }

        return view('admin.supplier.list', [
            'suppliers' => $returnData,
            'pagination' => $paginationData,   
            'supplierStatusColors' => SupplierEnum::MAP_STATUSES_COLOR,         
            'supplierTypes' => SupplierEnum::MAP_TYPES,
            'supplierStatuses' => SupplierEnum::MAP_STATUSES,
            'request' => $data,
        ]);
    }

    /** Display page for create new supplier */
    public function create(Request $request)
    {
        return view('admin.supplier.create', [
            'countries' => CurrencyAndCountryEnum::MAP_COUNTRIES,
            'currencies' => CurrencyAndCountryEnum::MAP_CURRENCIES, 
            'supplierTypes' => SupplierEnum::MAP_TYPES,
            'supplierStatuses' => SupplierEnum::MAP_STATUSES,
        ]);
    }

    /** Handle request for create new supplier */
    public function store(Request $request)
    {
        // Validate the request coming
        $validation = $this->validateRequest($request);
                
        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->route('admin.supplier.create.form')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // We only want to take necessary fields
        $data = $this->formatRequestData($request);

        $newSupplier = new Supplier($data);
        if (!$newSupplier->save()) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_ADD_NEW_RECORD)->send();

            return redirect()->route('admin.supplier.create.form')->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        $responseData = viewResponseFormat()->success()->data($newSupplier->toArray())->message(ResponseMessageEnum::SUCCESS_ADD_NEW_RECORD)->send();

        return redirect()->route('admin.supplier.create.form')->with(['response' => $responseData]);
    }

    /** Display the page for create new supplier */
    public function show(Request $request, Supplier $supplier)
    {           
        $countryMeta = $supplier->getMeta(SupplierMetaEnum::META_AVAILABLE_COUNTRY);
        $serviceMeta = $supplier->getMeta(SupplierMetaEnum::META_AVAILABLE_SERVICE);

        return view('admin.supplier.show', [
            'supplier' => $supplier->toArray(),
            'supplierTypes' => SupplierEnum::MAP_TYPES,
            'supplierStatuses' => SupplierEnum::MAP_STATUSES,
            'supplierStatusColors' => SupplierEnum::MAP_STATUSES_COLOR,
            'countries' => CurrencyAndCountryEnum::MAP_COUNTRIES,
            'services' => SupplierEnum::MAP_SERVICES,
            'currentCountriesMeta' => $countryMeta ? $countryMeta->getValue() : [],
            'currentServicesMeta' => $serviceMeta ? $serviceMeta->getValue() : [],
        ]);
    }

    /** Display the page for update supplier details */
    public function edit(Request $request, Supplier $supplier)
    {    
        $countryMeta = $supplier->getMeta(SupplierMetaEnum::META_AVAILABLE_COUNTRY);
        $serviceMeta = $supplier->getMeta(SupplierMetaEnum::META_AVAILABLE_SERVICE);

        return view('admin.supplier.edit', [
            'supplier' => $supplier->toArray(),
            'supplierTypes' => SupplierEnum::MAP_TYPES,
            'supplierStatuses' => SupplierEnum::MAP_STATUSES,
            'supplierStatusColors' => SupplierEnum::MAP_STATUSES_COLOR,
            'countries' => CurrencyAndCountryEnum::MAP_COUNTRIES,
            'services' => SupplierEnum::MAP_SERVICES,
            'currentCountriesMeta' => $countryMeta ? $countryMeta->getValue() : [],
            'currentServicesMeta' => $serviceMeta ? $serviceMeta->getValue() : [],
        ]);
    }

    /** Handle request update supplier details */
    public function update(Request $request, Supplier $supplier)
    {
        // Validate the request coming
        $validation = $this->validateRequest($request);
        
        if ($validation->fails()) {
            $responseData = viewResponseFormat()->error()->data($validation->messages())->message(ResponseMessageEnum::FAILED_VALIDATE_INPUT)->send();

            return redirect()->route('admin.supplier.edit.form', ['supplier' => $supplier->id])->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        // We only want to take necessary fields
        $data = $this->formatRequestData($request);
        if (!$supplier->update($data)) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_UPDATE_RECORD)->send();

            return redirect()->route('admin.supplier.edit.form', ['supplier' => $supplier->id])->with([
                'response' => $responseData,
                'request' => $request->all(),
            ]);
        }

        $responseData = viewResponseFormat()->success()->data($supplier->toArray())->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();

        return redirect()->route('admin.supplier.show', ['supplier' => $supplier->id])->with(['response' => $responseData]);
    }    

    /** Handle request delete supplier */
    public function destroy(Request $request, Supplier $supplier)
    {         
        // Perform deletion
        if (!$supplier->delete()) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_DELETE_RECORD)->send();

            return redirect()->route('admin.supplier.list')->with(['response' => $responseData]);
        }

        $responseData = viewResponseFormat()->success()->data($supplier->toArray())->message(ResponseMessageEnum::SUCCESS_DELETE_RECORD)->send();

        return redirect()->route('admin.supplier.list')->with(['response' => $responseData]);
    }

    public function countryConfig(Request $request, Supplier $supplier)
    {
        $data = $request->only('countries');
        
        // If the list data 
        if (empty($data) || !is_array($data) || empty($data['countries'])) {
            // Get meta country of this supplier
            $countryMeta = $supplier->getMeta(SupplierMetaEnum::META_AVAILABLE_COUNTRY);
            if (!$countryMeta) {
                $responseData = viewResponseFormat()->success()->data($supplier->toArray())->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();
        
                return redirect()->back()->with(['response' => $responseData]);
            }

            // If this meta exist, then we delete it
            if (!$countryMeta->delete()) {
                $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_UPDATE_RECORD)->send();
    
                return redirect()->back()->with(['response' => $responseData]);
            }

            $responseData = viewResponseFormat()->success()->data($supplier->toArray())->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();
        
            return redirect()->back()->with(['response' => $responseData]);
        }

        // If the list was provided as an array and not empty, we validate
        $eligibleItems = collect($data['countries'])
                    ->filter(function($item) {
                        return in_array($item, array_keys(CurrencyAndCountryEnum::MAP_COUNTRIES));
                    })
                    ->toArray();

        //If the list is empty after validation, that means we should throw error back to FE
        if (empty($eligibleItems)) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_ITEMS_PROVIDED)->send();

            return redirect()->back()->with(['response' => $responseData]);
        }

        // Implode the items as a string and create record
        $value = implode(',', $eligibleItems);
        $newMeta = $supplier->createMeta(SupplierMetaEnum::META_AVAILABLE_COUNTRY, $value ?? '');
        if (!$newMeta) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_UPDATE_RECORD)->send();

            return redirect()->back()->with(['response' => $responseData]);
        }

        $responseData = viewResponseFormat()->success()->data($supplier->toArray())->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();
    
        return redirect()->back()->with(['response' => $responseData]);
    }

    public function serviceConfig(Request $request, Supplier $supplier)
    {
        $data = $request->only('services');
        
        // If the list data 
        if (empty($data) || !is_array($data) || empty($data['services'])) {
            // Get meta service of this supplier
            $serviceMeta = $supplier->getMeta(SupplierMetaEnum::META_AVAILABLE_SERVICE);
            if (!$serviceMeta) {
                $responseData = viewResponseFormat()->success()->data($supplier->toArray())->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();
        
                return redirect()->back()->with(['response' => $responseData]);
            }

            // If this meta exist, then we delete it
            if (!$serviceMeta->delete()) {
                $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_UPDATE_RECORD)->send();
    
                return redirect()->back()->with(['response' => $responseData]);
            }

            $responseData = viewResponseFormat()->success()->data($supplier->toArray())->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();
        
            return redirect()->back()->with(['response' => $responseData]);
        }

        // If the list was provided as an array and not empty, we validate
        $eligibleItems = collect($data['services'])
                    ->filter(function($item) {
                        return in_array($item, array_keys(SupplierEnum::MAP_SERVICES));
                    })
                    ->toArray();

        //If the list is empty after validation, that means we should throw error back to FE
        if (empty($eligibleItems)) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::INVALID_ITEMS_PROVIDED)->send();

            return redirect()->back()->with(['response' => $responseData]);
        }

        // Implode the items as a string and create record
        $value = implode(',', $eligibleItems);
        $newMeta = $supplier->createMeta(SupplierMetaEnum::META_AVAILABLE_SERVICE, $value ?? '');
        if (!$newMeta) {
            $responseData = viewResponseFormat()->error()->message(ResponseMessageEnum::FAILED_UPDATE_RECORD)->send();

            return redirect()->back()->with(['response' => $responseData]);
        }

        $responseData = viewResponseFormat()->success()->data($supplier->toArray())->message(ResponseMessageEnum::SUCCESS_UPDATE_RECORD)->send();
    
        return redirect()->back()->with(['response' => $responseData]);
    }

    /** Validate form request for store and update functions */
    private function validateRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "full_name" => ["required"],
            "phone" => ["required"],
            "address" => ["required"],
            "type" => ["required", "integer"],
            "status" => ["required", "integer"],
        ]);

        return $validator;
    }

    /** Format the data before saving to database */
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
        return collect($data)->only([
            'full_name',
            'phone',
            'address',
            'company',
            'status',
            'type',
            'note',
        ])->toArray();
    }
}
