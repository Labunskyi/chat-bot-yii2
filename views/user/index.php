<?php
/**
 * @var $this yii\web\View
 */

$this->title = Yii::t('app', 'Dashboard');
$this->params['breadcrumbs'][] = Yii::t('app', 'Dashboard');
?>
<div class="row">
    <div class="col-xl-6 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Revenue</h4>
                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="card-content collapse show">
                <div class="card-body pt-0">
                    <div class="row mb-1">
                        <div class="col-6 col-md-4">
                            <h5>Current week</h5>
                            <h2 class="danger">$82,124</h2>
                        </div>
                        <div class="col-6 col-md-4">
                            <h5>Previous week</h5>
                            <h2 class="text-muted">$52,502</h2>
                        </div>
                    </div>
                    <div class="chartjs"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                        <canvas id="thisYearRevenue" width="734" style="position: absolute; display: block; width: 734px; height: 367px;" height="367" class="chartjs-render-monitor"></canvas>
                        <canvas id="lastYearRevenue" width="734" height="367" class="chartjs-render-monitor" style="display: block; width: 734px; height: 367px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-12">
        <div class="row">
            <div class="col-lg-6 col-12">
                <div class="card pull-up">
                    <div class="card-header bg-hexagons">
                        <h4 class="card-title">Hit Rate
                            <span class="danger">-12%</span>
                        </h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show bg-hexagons">
                        <div class="card-body pt-0">
                            <div class="chartjs"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                                <canvas id="hit-rate-doughnut" height="303" width="331" class="chartjs-render-monitor" style="display: block; width: 331px; height: 303px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-12">
                <div class="card pull-up">
                    <div class="card-content collapse show bg-gradient-directional-danger ">
                        <div class="card-body bg-hexagons-danger">
                            <h4 class="card-title white">Deals
                                <span class="white">-55%</span>
                                <span class="float-right">
                          <span class="white">152</span>
                          <span class="red lighten-4">/200</span>
                        </span>
                            </h4>
                            <div class="chartjs"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                                <canvas id="deals-doughnut" height="303" width="331" class="chartjs-render-monitor" style="display: block; width: 331px; height: 303px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-12">
                <div class="card pull-up">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="media-body text-left">
                                    <h6 class="text-muted">Order Value </h6>
                                    <h3>$ 88,568</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="icon-trophy success font-large-2 float-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-12">
                <div class="card pull-up">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="media-body text-left">
                                    <h6 class="text-muted">Calls</h6>
                                    <h3>3,568</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="icon-call-in danger font-large-2 float-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>