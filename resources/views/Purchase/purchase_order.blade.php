@extends('layouts.default')
@section('content')
    <?php
    use App\Helpers\CommonHelper;
    use App\Helpers\SalesHelper;
    $counter = 1;
    $total_amount = 0;
    ?>
    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Selling</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; View Sale Quotation</h3>
                </li>
            </ul>
        </div>3
    </div>
    <div class="row" >
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw" id="printReport">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 "></div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right hidden-print">
                                            <h1><?php CommonHelper::displayPrintButtonInView('printReport','','1');?></h1>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6 ">
                                            <div class="premier_igm2">
                                                 <img src="{{asset('public/assets/img/premier_igm-2.png')}}" alt="">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                                            <div class="premier_igmcol">
                                                 <div class="row">
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-5">
                                                        <div class="hgeaads">
                                                            <h2 class="adas">Address:</h2>
                                                            <h2>Contact #:</h2>
                                                            <h2>Fax:</h2>
                                                            <h2>Email:</h2>
                                                            <h2>Website:</h2>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-7">
                                                        <div class="ptaad">
                                                            <p>43-E, Block-6, P.E.C.H.S Behind FedEx, Off.
                                                            Razi Road, Sharah-e-Faisal
                                                            Karachi, 75400
                                                            PAK(Pakistan)</p>
                                                            <p>+92-21-34397771-75</p>
                                                            <p>+92-21-34397779</p>
                                                            <p>sales@premiercables.net</p>
                                                            <p>www.premiercables.net</p>
                                                        </div>
                                                    </div>


                                                 </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slist_te">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="headquid4">
                                                    <h2 class="subHeadingLabelClass"><u>PURCHASE ORDER</u></h2>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>

                                    <div class="slist_te">
                                        <div class="row">
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                                <div class="headquid_aprk6">
                                                    <h2 class="subHeadingLabelClass">NANTONG XINGCHEN SYNTHETIC MATERIAL CO., LTD.</h2>
                                                </div>
                                                <div class="headquid_apr23">
                                                    <h2>118 jianggang Rd, NETDA Nantong, Jiangsu 226017 China China</h2>
                                                    <h2>arsalan@vppl.pk</h2>
                                                    <div class="sub">
                                                        <h2 class="subHeadingLabelClass">Subject: PURCHASE OF PBT BLUESTAR 1230 HR</h2>
                                                        <h2 class="subHeadingLabelClass">ATTN: Muhammad Arsalan Mahmood</h2>
                                                    </div>

                                                </div>
                                                <div class="headquid_apr233">
                                                    <h2>Dear Sir/ Madam,</h2>
                                                    <p>With reference to to your email received on 21-12-23 , we are hereby issuing the following purchase order</p>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">

                                                <div class="premier_igmcol">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-5 ">
                                                            <div class="hgeaads2">
                                                                <h2>PO #:</h2>
                                                                <h2>PO Date:</h2>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-7 ">
                                                            <div class="ptaad">
                                                                <p>PO-004795-1</p>
                                                                <p>21 December, 2023</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr style="border:1px solid #000;">

                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-5 ">
                                                            <div class="hgeaads2">
                                                    
                                                                <h2>Delivery Terms:</h2>
                                                                <h2>Delivery Mode:</h2>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-7 ">
                                                            <div class="ptaad">
                                                                <p>CNF Karachi</p>
                                                                <p>FCL SEA SHIPMENT</p>
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
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <table class="userlittab2 table table-bordered sf-table-list2">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">COMMODITY DESCRIPTION</th>
                                                    <th class="text-center">HS CODE</th>
                                                    <th class="text-center">Unit</th>
                                                    <th class="text-center">TOTAL QTY</th>
                                                    <th class="text-center">CUR.</th>
                                                    <th class="text-center">UNIT RATE</th>
                                                    <th class="text-center">UNIT RATE</th>
                                                </tr>
                                                </thead>
                                                
                                                <tbody id="data">
                                                
                                                    <tr>
                                                        <td class="text-left">POLYBUTYLENE</td>
                                                        <td class="text-center">3907.7000</td>
                                                        <td class="text-center">Kg</td>
                                                        <td class="text-center">36,000.00</td>
                                                        <td class="text-center">USD</td>
                                                        <td class="text-center">1.4200</td>
                                                        <td class="text-center">51,120.00</td>
                                                    
                                                    </tr>
                                                
                                                </tbody>
                                                <thead>
                                                <tr>
                                                    <th class="text-center"></th>
                                                    <th class="text-center"></th>
                                                    <th class="text-center"></th>
                                                    <th class="text-center"></th>
                                                    <th class="text-center"></th>
                                                    <th class="text-center">TOTAL</th>
                                                    <th class="text-center">51,120.00</th>
                                                </tr>
                                                </thead>
                                            </table>
                                            <div class="tabhead">
                                                <h2><span>Amount In Words:</span> *** Fifty One Thousand One Hundred Twenty and 00/100</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slist_te">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="headquid_aprks tecs">
                                    <h2 class="subHeadingLabelClass">TERMS & CONDITIONS</h2>
                                </div>
                                <div class="headquid_apr2">
                                    <h2 class="subHeadingLabelClass">1.	Payment: Within Thirty (30) days from the date of delivery.</h2>
                                    <h2 class="subHeadingLabelClass">2.	DELIVERY DESTINATIONS: Hyderabad to Sukkur.</h2>
                                    <h2 class="subHeadingLabelClass">3.	DELIVERY LENGTH: 100 Meters.</h2>
                                    <h2 class="subHeadingLabelClass">4.	O CLOSURE TIMELINE: The Quantities mutually agreed to close the said project by or before 02 months. Prolonged remaining quantities may be subject to revision of price and delivery terms.</h2>
                                    <h2 class="subHeadingLabelClass">5.	Delivery/Unloading: Delivery at desired location will be manufacturer’s responsibility. However, unloading will be of customer’s responsibility, customer have to make arrangements at their own.</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slist_te">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="headquid_aprks">
                                    <h2 class="subHeadingLabelClass">Kindly issue PI so that LC to be established.</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slist_te">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="headquid_aprk3">
                                    <h2 class="subHeadingLabelClass">Best Regards,</h2>
                                    <h2 class="subHeadingLabelClass">for Premier Cables (PVT) Limited</h2>
                                    <div class="akk">
                                        <h2 class="subHeadingLabelClass">FUZAIL AHMED</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection