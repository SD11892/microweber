<?php

namespace MicroweberPackages\Order\Repositories;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use MicroweberPackages\Order\Models\Order;
use MicroweberPackages\Repository\Repositories\AbstractRepository;

class OrderRepository extends AbstractRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public $model = Order::class;

    public function getOrderCurrencies($params = [])
    {
        $orders = $this->getDefaultQueryForStats($params);
        $orders->groupBy('currency');
        $orders->select('currency', DB::raw('count(*) as orders_count'));

        $data = $orders->get();
        if ($data) {
            $data = $data->toArray();
            return $data;
        }
    }

    public function getOrdersTotalSumForPeriod($params = [])
    {

        $orders = $this->getDefaultQueryForStats($params);

        $sum = $orders->sum('amount');
        if ($sum) {
            return ceil($sum);
        }
        return 0;
    }
    public function getOrdersCountForPeriod($params = [])
    {

        $orders = $this->getDefaultQueryForStats($params);

        $sum = $orders->count('id');
        if ($sum) {
            return intval($sum);
        }
        return 0;
    }
    public function getBestSellingProductsForPeriod($params = [])
    {
        $orders = $this->getDefaultQueryForStats($params);
        $orders->where('cart.rel_type', 'content');
        $orders->join('cart', 'cart.order_id', '=', 'cart_orders.id');
        $orders->join('content', 'cart.rel_id', '=', 'content.id');
        $orders->select('cart.rel_id as content_id',
            DB::raw("count(cart.rel_id) as orders_count"),
            DB::raw("sum(cart_orders.amount) as orders_amount")
        );

        $orders->groupBy('cart.rel_id');
        $orders->orderBy('orders_count', 'desc');

        $data = $orders->get();
        if ($data) {
            $data = $data->toArray();

            if (!empty($data)) {
                array_walk($data, function (&$a, $b) {
                    if (isset($a['orders_amount'])) {
                        $a['orders_amount_rounded'] = ceil($a['orders_amount']);
                    }
                });

            }


            return $data;
        }



    }
    public function getOrderItemsCountForPeriod($params = [])
    {
 // todo  finish the query
      $orders = $this->getDefaultQueryForStats($params);

        $orders->joinRelationship('cart');
        $orders->where('cart.rel_type', 'content');
       // $orders->select(DB::raw('COUNT( cart.rel_id ) as "count"') );
       // $orders->groupBy('cart.rel_id');
      //  $sum =   $orders->count('cart.order_id');
        $sum =   $orders->count('cart.order_id' );
      //  dd($orders->toSql(),$sum);
//
//
//
//
//
//        $data = $orders->groupBy('rel_id')
//          //  ->orderBy('date', 'desc')
//            ->where('rel_type', 'content')
//            ->get([
//                 DB::raw('COUNT( * ) as "count"')
//            ])->toArray();
//
//        dd($data);
//
//
//
//      //  $orders->select(DB::raw('COUNT( * ) as "count"') );
//        $orders->where('rel_type', 'content');
//        $orders->groupBy('rel_id');
//        $sum = $orders-> count('id');
//      //  $sum = $orders->sum('count');
        if ($sum) {
            return intval($sum);
        }
        return 0;
    }
    public function getOrdersCountGroupedByDate($params = [])
    {

        $groupByFields = 'date';
        if(isset($params['period_group'])){
            switch ($params['period_group']) {
                case 'daily':
                    $groupByFields = 'date';
                    break;
                case 'weekly':
                    $groupByFields = 'date_year_month_week';
                    break;
                case 'monthly':
                    $groupByFields = 'date_year_month';
                    break;
                case 'yearly':
                    $groupByFields = 'date_year';
                    break;
            }
         }

        $orders = $this->getDefaultQueryForStats($params);

        $orders->groupBy($groupByFields);

        $orders->orderBy('date', 'desc');

        $data =  $orders->get([
            DB::raw('sum( amount ) as amount'),
            DB::raw('YEAR( created_at ) as date_year'),
            DB::raw('MONTH( created_at ) as date_month'),
            DB::raw('DATE( created_at ) as date'),
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') date_year_month"),
            DB::raw("DATE_FORMAT(created_at, '%Y-%m-%u') date_year_month_week"),
            DB::raw("DATE_FORMAT(created_at, '%Y %M Week %u') date_year_month_week_display"),
            DB::raw('COUNT( * ) as "count"'),

        ])->toArray();



        if (!empty($data)) {
            array_walk($data, function (&$a, $b) {
                if (isset($a['amount'])) {
                    $a['amount_rounded'] = ceil($a['amount']);
                }
            });

        }

        return $data;
    }

    public function getDefaultQueryForStats($params)
    {

        $orders = $this->getModel()->newQuery();
        $params = array_merge($params, [
                   'isPaid' => 1,
                  'isCompleted' => 1,
        ]);
        $dateSting = '';
        if (isset($params['from']) and $params['from']) {
            $params['from'] = Carbon::parse(strtotime($params['from']))->format('Y-m-d') . ' 00:00:01';
            $dateSting = $params['from'];
        }
        if (isset($params['to']) and $params['to']) {
            $params['to'] = Carbon::parse(strtotime($params['to']))->format('Y-m-d') . ' 23:59:59';
            $dateSting .= ',' . $params['to'];
        }
        if (isset($params['limit']) and $params['limit']) {
            $orders->limit(intval($params['limit']));
        }
        if ($dateSting) {
            $params['dateBetween'] = $dateSting;
        }

        $orders->filter(
            $params
        );
        return $orders;
    }
}
