<?php

namespace App;

use Sonnenglas\AmazonMws\AmazonOrderList;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Customer extends Model
{
    protected $guarded = ['id'];

    public function fetch($store = 'US', $dateStart = null)
    {
    	$job = \App\Job::firstOrNew(['store' => $store]);

    	$today = \Carbon\Carbon::today('UTC');

    	$count = 0;

    	$success = false;

    	if ($dateStart) {
    		$start = \Carbon\Carbon::parse($dateStart, 'UTC');
    	} else {
    		if (!$job->last_run) {
    			$start = \Carbon\Carbon::today('UTC')->subDays(2);
    		} else {
    			$start = \Carbon\Carbon::parse($job->last_run, 'UTC');
    		}
    	}
        
        $end = \Carbon\Carbon::parse($start->toDateTimeString(), 'UTC')->addDay();

        $startHour = $today->diffInHours($start);
        $endHour = $today->diffInHours($end);

        if ($end->diffInDays($today) >= 2) {
        	$amazonOrders = new AmazonOrderList($store);
	        $amazonOrders->setLimits('Created', "- $startHour hours", "- $endHour hours");
	        $amazonOrders->setOrderStatusFilter(['Shipped']);
	        $amazonOrders->setUseToken();
	        $amazonOrders->fetchOrders();
	        $orders = $amazonOrders->getList();

	        foreach ($orders as $order) {
	        	try {
	        		$customer = \App\Customer::firstOrNew([
		                'name' => $order->getBuyerName(),
		                'shipping_purchase_date' => \Carbon\Carbon::parse($order->getPurchaseDate())->toDateTimeString()
		            ]);
		            
		            $customer->name = $order->getBuyerName();
		            $customer->store = $store;
		            $customer->shipping_purchase_date = \Carbon\Carbon::parse($order->getPurchaseDate())->toDateTimeString();
		            $customer->shipping_name = $order->getShippingAddress()['Name'];
		            $customer->shipping_address_1 = $order->getShippingAddress()['AddressLine1'];
		            $customer->shipping_address_2 = $order->getShippingAddress()['AddressLine2'];
		            $customer->shipping_address_3 = $order->getShippingAddress()['AddressLine3'];
		            $customer->shipping_city = $order->getShippingAddress()['City'];
		            $customer->shipping_county = $order->getShippingAddress()['County'];
		            $customer->shipping_district = $order->getShippingAddress()['District'];
		            $customer->shipping_state_or_region = $order->getShippingAddress()['StateOrRegion'];
		            $customer->shipping_postal_code = $order->getShippingAddress()['PostalCode'];
		            $customer->shipping_country_code = $order->getShippingAddress()['CountryCode'];
		            $customer->shipping_phone = $order->getShippingAddress()['Phone'];
		            $customer->shipping_amount = $order->getOrderTotal()['Amount'];
		            $customer->shipping_order_count = $order->getNumberOfItemsShipped();

		            $customer->save();

		            $count++;
		        } catch (Exception $e) {
		        	Log::info('Showing user profile for user: '. $e);
		        }
	        }

	        $success = true;
        }

        $job->store = $store;
        $job->fetch_record_count = $count;
        $job->successful = $success;
        $job->last_run = $success ? $end : $start->toDateTimeString();
        $job->save();
    }
}
