<?php

namespace App\Http\Controllers;

use Excel;
use DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobs = \App\Job::all();

        return view('home', compact('jobs'));
    }

    public function customersPerMonth()
    {
        $jobs = \App\Job::all();

        return view('customers', compact('jobs'));
    }

    public function salesPerMonth()
    {
        $jobs = \App\Job::all();

        return view('sales', compact('jobs'));
    }

    public function export()
    {
        $range = request('range');

        $store = request('store');

        $dates = explode(' - ', $range);

        $type = request('type');

        $filename = 'YEOUTH_CUSTOMERS_' . $store . '_' . str_replace(' - ', '_', $range);

        $headers = ['name', 'shipping_name', 'shipping_phone', 'shipping_address_2', 'shipping_address_3', 'shipping_district', 'shipping_county', 'shipping_city', 'shipping_state_or_region', 'shipping_postal_code', 'shipping_country_code', 'shipping_amount', 'shipping_order_count', 'shipping_purchase_date'];

        $customers = \App\Customer::whereBetween('shipping_purchase_date', [\Carbon\Carbon::parse($dates[0], 'PST')->timezone('UTC')->toDateTimeString(), \Carbon\Carbon::parse($dates[1], 'PST')->timezone('UTC')->toDateTimeString()])->whereStore($store)->get($headers);

        if ($type == 'All') {
            $customers = $customers->unique('name');
        } else if ($type == 'Single') {
            $temp = $customers->unique('name');

            $temp2 = $customers->diffKeys($temp);

            $temp2Array = collect($temp2->toArray())->flatten(1);

            $customers = $temp->filter(function ($value, $key) use($temp2Array) {
                return ! $temp2Array->contains($value->name);
            })->unique('name');
        } else {
            $temp = $customers->unique('name');

            $customers = $customers->diffKeys($temp)->unique('name');
        }

        if ($customers->count() == 0) {
            return redirect()->back()->with(['status' => 'No customers exported. please try again..']);
        }

        Excel::create($filename, function($excel) use($customers, $headers){
            $excel->setTitle('Customer List');

            $excel->sheet('Sheet 1', function($sheet) use($customers, $headers) {
                $sheet->appendRow($headers);
                
                foreach ($customers->chunk(500) as $customer) {
                    foreach ($customer as $data) {
                        $sheet->appendRow($data->toArray());
                    }
                }
            });
        })->export('csv');
    }

    public function orders($amount = null)
    {
        if (request('amount') == true) {
            return [
                'us' => \App\Customer::whereStore('US')->sum('shipping_amount'),
                'ca' => \App\Customer::whereStore('CA')->sum('shipping_amount'),
                'uk' => \App\Customer::whereStore('UK')->sum('shipping_amount')
            ];
        }

        return [
            'us' => \App\Customer::whereStore('US')->sum('shipping_order_count'),
            'ca' => \App\Customer::whereStore('CA')->sum('shipping_order_count'),
            'uk' => \App\Customer::whereStore('UK')->sum('shipping_order_count')
        ];
    }

    public function customers()
    {
        return [
            'us' => \DB::table('customers')->select(\DB::raw('count(*) as count'))->where('store', 'US')->groupBy(\DB::raw('name'))->get('name', 'store')->count(),
            'ca' => \DB::table('customers')->select(\DB::raw('count(*) as count'))->where('store', 'CA')->groupBy(\DB::raw('name'))->get('name', 'store')->count(),
            'uk' => \DB::table('customers')->select(\DB::raw('count(*) as count'))->where('store', 'UK')->groupBy(\DB::raw('name'))->get('name', 'store')->count(),
        ];
    }

    public function customersByMonth()
    {
        $data = \App\Customer::selectRaw('YEAR(shipping_purchase_date) as year, MONTH(shipping_purchase_date) as month, sum(shipping_order_count) as total')
            ->whereStore(request('store'))->groupBy('year', 'month')->orderBy('year', 'ASC')->orderBy('month', 'ASC')->get();

        $result = [];

        foreach ($data as $row) {
            $result[date('M, Y', mktime(0, 0, 0, $row->month, 1, $row->year))] = $row->total;
        }

        return $result;
    }

    public function salesByMonth()
    {
        $data = \App\Customer::selectRaw('YEAR(shipping_purchase_date) as year, MONTH(shipping_purchase_date) as month, sum(shipping_amount) as total')
            ->whereStore(request('store'))->groupBy('year', 'month')->orderBy('year', 'ASC')->orderBy('month', 'ASC')->get();

        $result = [];

        foreach ($data as $row) {
            $result[date('M, Y', mktime(0, 0, 0, $row->month, 1, $row->year))] = $row->total;
        }

        return $result;
    }
}
