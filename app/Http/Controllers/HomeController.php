<?php

namespace App\Http\Controllers;

use Excel;
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
        $total = \App\Customer::count();

        $jobs = \App\Job::all();

        return view('home', compact('total', 'jobs'));
    }

    public function export()
    {
        $range = request('range');

        $store = request('store');

        $dates = explode(' - ', $range);

        $filename = 'YEOUTH_CUSTOMERS_' . $store . '_' . str_replace(' - ', '_', $range);

        $customers = \App\Customer::whereBetween('shipping_purchase_date', [\Carbon\Carbon::parse($dates[0])->toDateTimeString(), \Carbon\Carbon::parse($dates[1])->toDateTimeString()])->whereStore($store)->get();

        if ($customers->count() == 0) {
            return redirect()->back()->with(['status' => 'No customers exported. please try again..']);
        }

        Excel::create($filename, function($excel) use($customers){
            $excel->setTitle('Customer List');

            $excel->sheet('Sheet 1', function($sheet) use($customers) {
                $sheet->fromArray($customers);
            });
        })->export('csv');
    }
}
