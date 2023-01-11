<?php

?>


<div class="card" wire:init="loadSalesData">

    <div wire:loading>

        <div class="d-flex justify-content-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>


    <?php

    $currency_display = '';
    $show_period_range = '';
    $show_period_dates_display = '';
    $selected_product_id = false;
    $selected_category_id = false;

    if (isset($filters['productId'])) {
        $selected_product_id = $filters['productId'];
    }
    if (isset($filters['categoryId'])) {
        $selected_category_id = $filters['categoryId'];
    }

    if (isset($filters['currency'])) {
        $currency_display = $filters['currency'];
    }
    if (isset($filters['from'])) {
        $show_period_dates_display = 'From ' . $filters['from'];
    }
    if (isset($filters['to'])) {
        $show_period_dates_display .= ' to ' . $filters['to'];
    }

    if (isset($view['show_period_range'])) {
        $show_period_range = ucfirst($view['show_period_range']);
    }
    ?>

    <div class="card-body">
        {!! json_encode($filters, JSON_PRETTY_PRINT) !!}
        {!! json_encode($view, JSON_PRETTY_PRINT) !!}


        <label for="start_date">Start date:</label>

        <input type="date" id="start_date" wire:model="filters.from"/>

        <label for="end_date">End date:</label>

        <input type="date" id="end_date" wire:model="filters.to"/>


    </div>
    <div class="col-12 col-sm-6 col-md-3 col-lg-3 mb-4 js-order-product-filter">
        <label class="d-block">
            Product
        </label>

        <div class="mb-3 mb-md-0">

 



            @livewire('admin-filter-item-product', ['selectedItem'=>$selected_product_id])
        </div>

    </div>

    <div class="col-12 col-sm-6 col-md-3 col-lg-3 mb-4 js-order-product-filter">


        <div class="mb-3 mb-md-0">

            <label for="end_date">Category:</label>

            <input type="text" wire:model="filters.categoryId"/>
        </div>

    </div>

    <?php if(isset($view['supported_currencies'])): ?>
    <div class="card-body">
        <?php foreach ($view['supported_currencies'] as $currency): ?>
        <?php
        $class = 'btn-outline-secondary';

        if (isset($filters['currency']) and $filters['currency'] == $currency['currency']) {
            $class = 'btn-primary';
        }
        ?>
        <button class="btn <?php print $class ?>"
                wire:click="changeCurrency('<?php print $currency['currency'] ?>')"><?php print $currency['currency'] ?></button>
        <?php endforeach; ?>

    </div>
    <?php endif; ?>

    <?php if(isset($view['supported_period_ranges'])): ?>
    <div class="card-body">
        <?php foreach ($view['supported_period_ranges'] as $supported_period_range): ?>
        <?php
        $class = 'btn-outline-secondary';

        if (isset($view['show_period_range']) and $view['show_period_range'] == $supported_period_range) {
            $class = 'btn-primary';
        }
        ?>
        <button class="btn <?php print $class ?>"
                wire:click="changePeriodDateRangeType('<?php print $supported_period_range ?>')"><?php print $supported_period_range ?></button>
        <?php endforeach; ?>

    </div>
    <?php endif; ?>


    <div class="card py-3 mb-3">
        <div class="card-body py-3">
            <div class="row g-0">


                <?php if(isset($data['orders_total_amount'])): ?>
                <div class="col-6 col-md-4 border-200 border-bottom border-end pb-4">
                    <h6 class="pb-1 text-700">Amount (<?php print $currency_display ?>)</h6>
                    <p class="font-sans-serif lh-1 mb-1 fs-2"><?php print currency_format($data['orders_total_amount'], $currency); ?> </p>
                </div>
                <?php endif; ?>

                <?php if(isset($data['orders_total_count'])): ?>
                <div class="col-6 col-md-4 border-200 border-bottom border-end pb-4">
                    <h6 class="pb-1 text-700">Orders count </h6>
                    <p class="font-sans-serif lh-1 mb-1 fs-2"><?php print $data['orders_total_count']; ?> </p>
                </div>
                <?php endif; ?>
                <?php if(isset($data['orders_total_items_count'])): ?>
                <div class="col-6 col-md-4 border-200 border-bottom border-end pb-4">
                    <h6 class="pb-1 text-700">Products sold </h6>
                    <p class="font-sans-serif lh-1 mb-1 fs-2"><?php print $data['orders_total_items_count']; ?> </p>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <?php if(isset($data['orders_data']) and $data['orders_data']): ?>
    <?php $rand = uniqid(); ?>
    <?php $chart_id = 'js_sales_stats_' . $rand; ?>

    <div class="row" id="sales<?php print $chart_id ?>"></div>
    <div class="row" id="amount<?php print $chart_id ?>"></div>

    <div class="row w-100" id="echart_sales_<?php print $chart_id ?>"></div>
    <div class="row w-100" id="echart_sales_amount_<?php print $chart_id ?>"></div>


    <div class="card py-3 mb-3">
        <div class="card-body py-3">


            <?php
            $sales_numbers = [];
            $sales_numbers_amount = [];
            if (isset($data['orders_data'])) {
                $orders_data = $data['orders_data'];
                foreach ($orders_data as $item) {
                    $sales_numbers[$item['date']] = $item['count'];
                    $sales_numbers_amount[$item['date']] = $item['amount_rounded'];
                }
            }

            ?>

            <script>
                function initJsSalesChart<?php print $chart_id ?>() {
                    var chartEl = document.getElementById("sales<?php print $chart_id ?>");
                    if (typeof chartEl !== 'undefined' && chartEl !== null) {
                        if ($(chartEl).hasClass('chart-js-render-ready')) {
                            return;
                        }
                    }
                    $(chartEl).addClass('chart-js-render-ready')

                    // echarts
                    var chartDom = document.getElementById('echart_sales_<?php print $chart_id ?>');
                    var salesChart = echarts.init(chartDom, null, {
                        //   width: "100%",
                        height: 400
                    })

                    var salesChartTooltipOptions = {
                        show: true,
                        position: 'top',
                        //  confine: true,
                        textStyle: {
                            overflow: 'breakAll',
                            width: 40,
                        },
                    };

                    salesChart.setOption({
                        legend: {},
                        tooltip: salesChartTooltipOptions,
                        xAxis: {
                            type: "category",
                            data: <?php print json_encode(array_keys($sales_numbers)) ?>
                        },
                        yAxis: {
                            type: "value"
                        },
                        series: [
                            {
                                name: '<?php print $show_period_range ?> Orders',
                                data: <?php print json_encode(array_values($sales_numbers)) ?>,
                                type: "line",
                                smooth: false,
                                lineStyle: {color: '#26be6b'}

                            }
                        ]
                    });


                    window.onresize = function () {
                        salesChart.resize();
                    };


                    // echarts sales amount
                    var chartDom = document.getElementById('echart_sales_amount_<?php print $chart_id ?>');


                    var salesAmountChart = echarts.init(chartDom, null, {
                        //   width: "100%",
                        height: 400
                    })


                    salesAmountChart.setOption({
                        legend: {},
                        tooltip: salesChartTooltipOptions,
                        xAxis: {
                            type: "category",
                            data: <?php print json_encode(array_keys($sales_numbers)) ?>
                        },
                        yAxis: {
                            type: "value"
                        },
                        series: [
                            {
                                name: '<?php print $show_period_range ?> Sales (<?php print $currency_display ?>)',
                                data: <?php print json_encode(array_values($sales_numbers_amount)) ?>,
                                type: 'bar'
                            }
                        ]
                    });


                    window.onresize = function () {
                        salesAmountChart.resize();
                    };


                }

                initJsSalesChart<?php print $chart_id ?>()
            </script>
        </div>


        <?php endif; ?>


        <?php if(isset($data['orders_best_selling_products']) and $data['orders_best_selling_products']): ?>
        <div class="card py-3 mb-3">

            <div class="card-body py-3">

                <h5 class="card-title">Most sold products</h5>
                <h6 class="card-subtitle mb-2 text-muted"><?php print $show_period_dates_display ?></h6>


                <div class="row g-0">
                    <table class="table table-responsive">
                        <thead>
                        <tr>
                            <th scope="col">Product Id</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Orders</th>

                            <th scope="col">Amount (<?php print $currency_display ?>)</th>
                        </tr>
                        </thead>
                        <tbody>


                        <?php foreach ($data['orders_best_selling_products'] as $orders_best_selling_product): ?>
                        <?php
                        $content = app()->content_repository->getById($orders_best_selling_product['content_id']);

                        if (!isset($content['title'])) {
                            continue;
                        }
                        ?>
                        <tr>
                            <th scope="row"><?php print $content['id'] ?></th>
                            <td><?php print $content['title'] ?></td>
                            <td><?php print $orders_best_selling_product['orders_count'] ?></td>
                            <td><?php print $orders_best_selling_product['orders_amount_rounded'] ?></td>
                            <td>
                                <button class="btn <?php print $class ?>"
                                        wire:click="setFilter('productId','<?php print $content['id'] ?>')">View</button>


                                <?php if($selected_product_id == $content['id']): ?>
                                <button class="btn btn-outline-danger"
                                        wire:click="setFilter('productId','')">Clear</button>
                                <?php endif; ?>


                                </td>
                        </tr>

                        <?php endforeach; ?>

                        </tbody>
                    </table>


                </div>
            </div>
        </div>

        <?php endif; ?>








        <?php if(isset($data['orders_best_selling_categories']) and $data['orders_best_selling_categories']): ?>
        <div class="card py-3 mb-3">

            <div class="card-body py-3">

                <h5 class="card-title">Most sold in categories</h5>
                <h6 class="card-subtitle mb-2 text-muted"><?php print $show_period_dates_display ?></h6>


                <div class="row g-0">
                    <table class="table table-responsive">
                        <thead>
                        <tr>
                            <th scope="col">Category name</th>
                            <th scope="col">Orders</th>

                            <th scope="col">Amount (<?php print $currency_display ?>)</th>
                        </tr>
                        </thead>
                        <tbody>


                        <?php foreach ($data['orders_best_selling_categories'] as $category): ?>
                        <?php

                        if (!isset($category['title'])) {
                            continue;
                        }
                        ?>
                        <tr>
                            <th scope="row"><?php print $category['title'] ?></th>
                            <td><?php print $category['orders_count'] ?></td>
                            <td><?php print $category['orders_amount_rounded'] ?></td>
                            <td> </td>

                            <td>
                                <button class="btn <?php print $class ?>"
                                        wire:click="setFilter('categoryId','<?php print $category['id'] ?>')">View</button>


                                <?php if($selected_category_id == $category['id']): ?>
                                <button class="btn btn-outline-danger"
                                        wire:click="setFilter('categoryId','')">Clear</button>
                                <?php endif; ?>


                            </td>



                        </tr>

                        <?php endforeach; ?>

                        </tbody>
                    </table>


                </div>
            </div>
        </div>

        <?php endif; ?>


        {!! json_encode($filters, JSON_PRETTY_PRINT) !!}
        <pre>
         {!! print_r($data, JSON_PRETTY_PRINT) !!}
        </pre>
    </div>

</div>














