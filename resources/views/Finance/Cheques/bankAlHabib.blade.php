@extends('layouts.default')

<style type="text/css">
        #mySidenav {display:none;}
         body{padding: 0;margin: 0;overflow-x:hidden;font-family: "calibri"; }
         .pos-rel {position: relative !important;width: 800px !important;height: 352px !important;}
         .cheq-img img {width: 800px !important; height: 352px !important;}
         .date {position: absolute !important;top: 42px !important;right: 24px !important;font-size: 21px !important;letter-spacing: 7px !important;}
         .pay {position: absolute !important;top: 112px !important;left: 81px !important;font-size: 17px !important;font-weight: 600 !important;}
         .wordrupees {position: absolute !important;top: 142px !important;left: 106px !important;font-size: 17px !important;font-weight: 600 !important;}
         .numericrupees {position: absolute ;top: 149px !important;right: 111px !important;font-size: 17px !important;font-weight: 600 !important;}
</style>   

<div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <span class="subHeadingLabelClass">Cheques View</span>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                        <button class="btn btn-primary" onclick="printView('PrintPanel','','1')" style="">
                                            <span class="glyphicon glyphicon-print"></span> &nbsp; Print
                                        </button>
                                       
                                    </div>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pos-rel" id="PrintPanel">
                                            <div class="cheq-img">
                                            <!-- <img src="{{ URL::asset('assets/img/cheques/alhabib.jpg') }}"> -->
                                            </div>
                                            <div class="date">{{date('dmY', strtotime($date))}}</div>
               <!-- <div class="date">2 5 0 5 2 0 2 4</div> -->

                                            <div class="pay">{{ $to }}</div>
                                            <div class="wordrupees">{{ $amount_word }} only</div>
                                            <div class="numericrupees">{{ $amount }}</div>
                                        </div>
                                    </div>


                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
var date_tt  = document.querySelector('.date').innerText
var date_new_tt="" ;

for (let index = 0; index < date_tt.length; index++) {

    date_new_tt += date_tt[index];
    if(index < date_tt.length)
    {
        date_new_tt += " ";
    }


}

document.querySelector('.date').innerText = date_new_tt

</script>
