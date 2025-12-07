<?php
use App\Helpers\CommonHelper;
use App\Helpers\SaleHelper;
?>
<style>
    p{margin:0;padding:0;font-size:13px;font-weight:500;}
    input.form-control.form-control2{margin:0!important;}
    .table-bordered > thead > tr > th,.table-bordered > tbody > tr > th,.table-bordered > tfoot > tr > th{vertical-align:inherit !important;text-align:left !important;padding: 7px 5px !important;}
    .totlas{display:flex;justify-content:right;gap:70px;background:#ddd;width:18%;float:right;padding-right:8px;}
    .totlas p{font-weight:bold;}
    .psds{display:flex;justify-content:right;gap:88px;}
    .psds p{font-weight:bold;}
    .userlittab > thead > tr > td,.userlittab > tbody > tr > td,.userlittab > tfoot > tr > td{padding:10px 5px !important;}
    .totlass{display:inline;background:transparent;margin-top:-25px;}
    .totlass h2{font-size:13px !important;}
</style>
@extends('layouts.default')
@section('content')
@include('select2')
@include('modal')

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="well_N">
        <div class="dp_sdw">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                    <?php CommonHelper::displayPrintButtonInView('printCashSaleVoucherDetail','','1');?>
                </div>
            </div>
            <div style="line-height:5px;">&nbsp;</div>
            <div class="row" >
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="printCashSaleVoucherDetail">
                                <div class="table-responsive">
                                    <table border="1" cellspacing="0" cellpadding="6" style="border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; font-size: 14px;">
                                    <thead style="background-color: #f2f2f2; text-align: left;">
                                        <tr>
                                        <th>System Code</th>
                                        <th>Accounts ID</th>
                                        <th>Acc level-1</th>
                                        <th>Acc level-2</th>
                                        <th>Acc level-3</th>
                                        <th>Accounts</th>
                                        <th>Type</th>
                                        <th>Nature</th>
                                        </tr>
                                    </thead>
                                        <tbody>
                                            <tr><td>1</td><td>10000</td><td>1</td><td>1</td><td>1</td><td>Cash in Hand</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>2</td><td>10000</td><td>1</td><td>1</td><td>2</td><td>Petty Cash</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>3</td><td>10000</td><td>1</td><td>1</td><td>3</td><td>Bank Margin Deposit</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>4</td><td>10000</td><td>1</td><td>1</td><td>4</td><td>Account Receivable Control Account</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>5</td><td>10000</td><td>1</td><td>1</td><td>5</td><td>Habib Metropolitan Bank - AM</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>6</td><td>10000</td><td>1</td><td>1</td><td>6</td><td>Cheque In Hand</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>7</td><td>10000</td><td>1</td><td>1</td><td>7</td><td>Bank Al-Falah</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>8</td><td>10000</td><td>1</td><td>1</td><td>8</td><td>Habib Metropolitan Bank</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>9</td><td>10000</td><td>1</td><td>1</td><td>9</td><td>Meezan Bank-Current</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>10</td><td>10000</td><td>1</td><td>1</td><td>10</td><td>Bank Al-Falah - AM</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>11</td><td>10000</td><td>1</td><td>1</td><td>11</td><td>Opening Balance of BUL</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>12</td><td>10000</td><td>1</td><td>1</td><td>12</td><td>Habib Metro - Les Cosmatiques</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>13</td><td>10000</td><td>1</td><td>1</td><td>13</td><td>RAK Bank - BUL</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>14</td><td>10000</td><td>1</td><td>1</td><td>14</td><td>Cash In Hand - Head Office</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>15</td><td>10000</td><td>1</td><td>1</td><td>15</td><td>Cash In Hand - Central</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>16</td><td>10000</td><td>1</td><td>1</td><td>16</td><td>Cash In Hand - North</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>17</td><td>10000</td><td>1</td><td>1</td><td>17</td><td>Cash In Hand - South</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>18</td><td>10000</td><td>1</td><td>1</td><td>18</td><td>MCB Bank.</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>19</td><td>10000</td><td>1</td><td>1</td><td>19</td><td>JS Bank - Les Cosmetiques</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>20</td><td>10000</td><td>1</td><td>1</td><td>20</td><td>Faysal Bank</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>21</td><td>10000</td><td>2</td><td>1</td><td>1</td><td>Loans &amp; Advances</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>22</td><td>10000</td><td>2</td><td>1</td><td>2</td><td>Employee Receivable</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>23</td><td>10000</td><td>2</td><td>1</td><td>3</td><td>Loan to Employee</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>24</td><td>10000</td><td>2</td><td>1</td><td>4</td><td>Advance to Employee</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>25</td><td>10000</td><td>2</td><td>1</td><td>5</td><td>Prepaid Insurance</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>26</td><td>10000</td><td>2</td><td>2</td><td>1</td><td>Sales Tax Receivable</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>27</td><td>10000</td><td>2</td><td>2</td><td>2</td><td>Sales Tax Import</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>28</td><td>10000</td><td>2</td><td>2</td><td>3</td><td>GST @ 25%</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>29</td><td>10000</td><td>2</td><td>2</td><td>4</td><td>SST @ 13%</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>30</td><td>10000</td><td>2</td><td>2</td><td>5</td><td>WHT on Vehicles</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>31</td><td>10000</td><td>2</td><td>2</td><td>6</td><td>WHT on Utilities</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>32</td><td>10000</td><td>2</td><td>2</td><td>7</td><td>WHT U/S 151</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>33</td><td>10000</td><td>2</td><td>2</td><td>8</td><td>File Bill</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>34</td><td>10000</td><td>2</td><td>2</td><td>9</td><td>Income Tax Import</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>35</td><td>10000</td><td>2</td><td>3</td><td>1</td><td>Security Deposit Account</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>36</td><td>10000</td><td>2</td><td>3</td><td>2</td><td>Land</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>37</td><td>10000</td><td>2</td><td>3</td><td>3</td><td>Building</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>38</td><td>10000</td><td>2</td><td>3</td><td>4</td><td>Machinery</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>39</td><td>10000</td><td>2</td><td>3</td><td>5</td><td>Computer Equipment</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>40</td><td>10000</td><td>2</td><td>3</td><td>6</td><td>Furniture &amp; Fixtures</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>41</td><td>10000</td><td>2</td><td>3</td><td>7</td><td>Vehicle</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>42</td><td>10000</td><td>2</td><td>3</td><td>8</td><td>Electric Appliance</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>43</td><td>10000</td><td>2</td><td>3</td><td>9</td><td>Office Equipment</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>44</td><td>10000</td><td>2</td><td>3</td><td>10</td><td>MIS Software</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>45</td><td>10000</td><td>2</td><td>3</td><td>11</td><td>Security Deposit Advance</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>46</td><td>10000</td><td>2</td><td>3</td><td>12</td><td>Advance Rent</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>47</td><td>10000</td><td>2</td><td>3</td><td>13</td><td>MCB Fleet Financing</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>48</td><td>10000</td><td>2</td><td>4</td><td>1</td><td>Inventory Quantity Adjustment</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>49</td><td>10000</td><td>2</td><td>4</td><td>2</td><td>Inventory Cost Adjustment</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>50</td><td>10000</td><td>2</td><td>4</td><td>3</td><td>Capital Work In Progress</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>51</td><td>10000</td><td>2</td><td>4</td><td>4</td><td>Stock In Transit</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>52</td><td>10000</td><td>2</td><td>4</td><td>5</td><td>Stock In Hand</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>53</td><td>10000</td><td>2</td><td>4</td><td>6</td><td>Stock Transfer</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>54</td><td>10000</td><td>2</td><td>5</td><td>1</td><td>Purchase Discount</td><td>Credit</td><td>Asset</td></tr>
                                            <tr><td>55</td><td>10000</td><td>2</td><td>6</td><td>1</td><td>Other Assets</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>56</td><td>10000</td><td>2</td><td>6</td><td>2</td><td>Stores Spare &amp; Loose Tools</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>57</td><td>10000</td><td>2</td><td>6</td><td>3</td><td>Other Receivable</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>58</td><td>10000</td><td>2</td><td>6</td><td>4</td><td>Loan to Mr. Essa</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>59</td><td>10000</td><td>2</td><td>7</td><td>1</td><td>Waseem Distributor</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>60</td><td>10000</td><td>2</td><td>7</td><td>2</td><td>LLB Pakistan</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>61</td><td>10000</td><td>2</td><td>7</td><td>3</td><td>Karachi Branch</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>62</td><td>10000</td><td>2</td><td>7</td><td>4</td><td>Lahore Branch</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>63</td><td>10000</td><td>2</td><td>7</td><td>5</td><td>Rawalpindi Branch</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>64</td><td>10000</td><td>2</td><td>7</td><td>6</td><td>PCP Receivable</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>65</td><td>10000</td><td>2</td><td>7</td><td>7</td><td>Limitless Brands-Receivable</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>66</td><td>10000</td><td>3</td><td>5</td><td>1</td><td>Advance against Supplies - Bourjois</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>67</td><td>10000</td><td>3</td><td>5</td><td>2</td><td>Advance against Supplies - Max Factor</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>69</td><td>10000</td><td>3</td><td>5</td><td>4</td><td>Advance against Supplies - Sally Hensen</td><td>Debit</td><td>Asset</td></tr>
                                            <tr><td>70</td><td>20000</td><td>1</td><td>1</td><td>1</td><td>Payroll Liabilities</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>71</td><td>20000</td><td>1</td><td>1</td><td>2</td><td>Sales Tax Payable</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>72</td><td>20000</td><td>1</td><td>1</td><td>3</td><td>Expense Liabilities</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>73</td><td>20000</td><td>1</td><td>1</td><td>4</td><td>Payroll Tax Payable</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>74</td><td>20000</td><td>1</td><td>1</td><td>5</td><td>Provident Fund Payable</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>75</td><td>20000</td><td>1</td><td>1</td><td>6</td><td>Income Tax Payable</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>76</td><td>20000</td><td>1</td><td>1</td><td>7</td><td>WHT Payable</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>77</td><td>20000</td><td>1</td><td>1</td><td>8</td><td>Salaries Payable</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>78</td><td>20000</td><td>1</td><td>1</td><td>9</td><td>Commission Payable</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>79</td><td>20000</td><td>1</td><td>1</td><td>10</td><td>Medical Payable</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>80</td><td>20000</td><td>1</td><td>1</td><td>11</td><td>BA Salaries Payable</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>81</td><td>20000</td><td>1</td><td>1</td><td>12</td><td>Bonus Payable</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>82</td><td>20000</td><td>1</td><td>1</td><td>13</td><td>Field Expense Payables</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>83</td><td>20000</td><td>1</td><td>1</td><td>14</td><td>Overtime Expense Payables</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>84</td><td>20000</td><td>1</td><td>1</td><td>15</td><td>Mobile Expense Payables</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>85</td><td>20000</td><td>1</td><td>1</td><td>16</td><td>Opening Balances- Financials</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>86</td><td>20000</td><td>1</td><td>1</td><td>17</td><td>SD Margin Payable</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>87</td><td>20000</td><td>1</td><td>1</td><td>18</td><td>EOBI Payable</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>88</td><td>20000</td><td>1</td><td>2</td><td>1</td><td>Provision for Bad Debts</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>89</td><td>20000</td><td>1</td><td>2</td><td>2</td><td>Provision for Expired/Damaged Stock</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>90</td><td>20000</td><td>1</td><td>2</td><td>3</td><td>Allowance of Dep - Building</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>91</td><td>20000</td><td>1</td><td>2</td><td>4</td><td>Allowance of Dep - Machinery</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>92</td><td>20000</td><td>1</td><td>2</td><td>5</td><td>Allowance of Dep - Computer Equipment</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>93</td><td>20000</td><td>1</td><td>2</td><td>6</td><td>Allowance of Dep - Furniture & Fixtures</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>94</td><td>20000</td><td>1</td><td>2</td><td>7</td><td>Allowance of Dep - Vehicles</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>95</td><td>20000</td><td>1</td><td>2</td><td>8</td><td>Allowance of Dep - Electric Appliances</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>96</td><td>20000</td><td>1</td><td>2</td><td>9</td><td>Allowance of Dep - Office Equipment</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>97</td><td>20000</td><td>1</td><td>3</td><td>1</td><td>Payable to COTY</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>98</td><td>20000</td><td>1</td><td>3</td><td>2</td><td>Payable to Coty Luxury</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>99</td><td>20000</td><td>1</td><td>3</td><td>3</td><td>Payable to Essence</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>100</td><td>20000</td><td>1</td><td>3</td><td>4</td><td>Payable to Tailored Perfumes</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>101</td><td>20000</td><td>1</td><td>3</td><td>5</td><td>Payable to Pana Dora</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>102</td><td>20000</td><td>1</td><td>3</td><td>6</td><td>Payable to PUIG</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>103</td><td>20000</td><td>1</td><td>4</td><td>1</td><td>Delivery Expense Payables</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>104</td><td>20000</td><td>1</td><td>4</td><td>2</td><td>Rent Expense Payable</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>105</td><td>20000</td><td>1</td><td>4</td><td>3</td><td>Other Payable</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>106</td><td>20000</td><td>1</td><td>4</td><td>4</td><td>Advance from Customer</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>107</td><td>20000</td><td>1</td><td>4</td><td>5</td><td>Employees Deposit Payable</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>108</td><td>20000</td><td>1</td><td>4</td><td>6</td><td>Legal & Professional Payable</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>109</td><td>20000</td><td>1</td><td>4</td><td>7</td><td>Import Commission payable</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>110</td><td>20000</td><td>1</td><td>4</td><td>8</td><td>Leopards Courier Payable</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>111</td><td>20000</td><td>1</td><td>4</td><td>9</td><td>Forwarding and Clearing Payable</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>112</td><td>20000</td><td>1</td><td>4</td><td>10</td><td>LES Cosmetiques Stock Payable</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>113</td><td>20000</td><td>1</td><td>4</td><td>11</td><td>Vehicle Insurance Payable</td><td>Debit</td><td>Liability</td></tr>
                                            <tr><td>114</td><td>20000</td><td>1</td><td>4</td><td>12</td><td>Deferred Tax</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>115</td><td>20000</td><td>1</td><td>5</td><td>1</td><td>Accrued Expenses</td><td>Debit</td><td>Liability</td></tr>
                                            <tr><td>116</td><td>20000</td><td>1</td><td>5</td><td>2</td><td>Accrued Interest</td><td>Debit</td><td>Liability</td></tr>
                                            <tr><td>117</td><td>20000</td><td>2</td><td>5</td><td>1</td><td>Loan From Mr. Essa</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>118</td><td>20000</td><td>2</td><td>5</td><td>2</td><td>Loan From Mr. Farhan</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>119</td><td>20000</td><td>2</td><td>5</td><td>3</td><td>Loan Payback Account</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>120</td><td>20000</td><td>2</td><td>5</td><td>4</td><td>Investor Account</td><td>Credit</td><td>Liability</td></tr>
                                            <tr><td>121</td><td>20000</td><td>2</td><td>5</td><td>5</td><td>Fleet Financing Payable</td><td>Debit</td><td>Liability</td></tr>
                                            <tr><td>122</td><td>30000</td><td>1</td><td>1</td><td>1</td><td>Capital</td><td>Credit</td><td>Capital</td></tr>
                                            <tr><td>123</td><td>30000</td><td>1</td><td>2</td><td>1</td><td>Drawing</td><td>Credit</td><td>Capital</td></tr>
                                            <tr><td>124</td><td>30000</td><td>1</td><td>2</td><td>1</td><td>Share Capital</td><td>Credit</td><td>Capital</td></tr>
                                            <tr><td>125</td><td>30000</td><td>2</td><td>1</td><td>2</td><td>Retained Earning</td><td>Credit</td><td>Capital</td></tr>
                                            <tr><td>126</td><td>50000</td><td>1</td><td>1</td><td>1</td><td>Provident Funds Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>127</td><td>50000</td><td>1</td><td>1</td><td>2</td><td>Salaries & Wages Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>128</td><td>50000</td><td>1</td><td>2</td><td>3</td><td>Field Expenses Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>129</td><td>50000</td><td>1</td><td>1</td><td>4</td><td>Employee Insurance Claims Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>130</td><td>50000</td><td>1</td><td>1</td><td>5</td><td>Leave Encashment Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>131</td><td>50000</td><td>1</td><td>1</td><td>6</td><td>Medical Expense Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>132</td><td>50000</td><td>1</td><td>1</td><td>7</td><td>Bonus Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>133</td><td>50000</td><td>1</td><td>1</td><td>8</td><td>EOBI Expense</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>134</td><td>50000</td><td>1</td><td>1</td><td>9</td><td>Import Duty</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>135</td><td>50000</td><td>1</td><td>1</td><td>10</td><td>Fees / Training Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>136</td><td>50000</td><td>1</td><td>1</td><td>11</td><td>Health Insurance Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>137</td><td>50000</td><td>1</td><td>9</td><td>12</td><td>Office Equipment Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>138</td><td>50000</td><td>1</td><td>9</td><td>13</td><td>Generator Repair Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>139</td><td>50000</td><td>1</td><td>1</td><td>14</td><td>IT Service Charges Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>140</td><td>50000</td><td>1</td><td>1</td><td>15</td><td>Janitorial Expense Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>141</td><td>50000</td><td>1</td><td>11</td><td>16</td><td>Forwarding & Clearing</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>142</td><td>50000</td><td>1</td><td>1</td><td>17</td><td>Overtime Expense Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>143</td><td>50000</td><td>1</td><td>1</td><td>18</td><td>Vehicle Rent Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>144</td><td>50000</td><td>1</td><td>1</td><td>19</td><td>Vehicle Repair Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>145</td><td>50000</td><td>1</td><td>6</td><td>20</td><td>Electricity Expense Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>146</td><td>50000</td><td>1</td><td>6</td><td>21</td><td>Water Expense Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>147</td><td>50000</td><td>1</td><td>6</td><td>22</td><td>Gas Expense Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>148</td><td>50000</td><td>1</td><td>14</td><td>23</td><td>Depreciation Expense Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>149</td><td>50000</td><td>1</td><td>14</td><td>24</td><td>Depreciation - Administration Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>150</td><td>50000</td><td>1</td><td>14</td><td>25</td><td>Stationary Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>151</td><td>50000</td><td>1</td><td>6</td><td>26</td><td>Internet Charges Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>152</td><td>50000</td><td>1</td><td>6</td><td>27</td><td>Telephone Expense Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>153</td><td>50000</td><td>1</td><td>6</td><td>28</td><td>Mobile Expense Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>154</td><td>50000</td><td>1</td><td>9</td><td>29</td><td>Office Repair & Maintainance Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>155</td><td>50000</td><td>1</td><td>14</td><td>30</td><td>Oil for Generator Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>156</td><td>50000</td><td>1</td><td>14</td><td>31</td><td>Dinner/Lunch Expense</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>157</td><td>50000</td><td>1</td><td>11</td><td>32</td><td>Freight Charges</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>158</td><td>50000</td><td>1</td><td>14</td><td>33</td><td>Other Entertainment Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>159</td><td>50000</td><td>1</td><td>14</td><td>34</td><td>Miscellaneous Expense Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>160</td><td>50000</td><td>1</td><td>14</td><td>35</td><td>Entertainment Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>161</td><td>50000</td><td>1</td><td>14</td><td>36</td><td>Printing Material Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>162</td><td>50000</td><td>1</td><td>5</td><td>37</td><td>Domestic Travel Expense Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>163</td><td>50000</td><td>1</td><td>5</td><td>38</td><td>International Travel Expense Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>164</td><td>50000</td><td>1</td><td>14</td><td>39</td><td>Software Expense</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>165</td><td>50000</td><td>1</td><td>12</td><td>40</td><td>Legal & Professional Admin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>166</td><td>50000</td><td>1</td><td>14</td><td>41</td><td>Non Posting entry</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>167</td><td>50000</td><td>1</td><td>4</td><td>42</td><td>Marketing expense claims</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>168</td><td>50000</td><td>1</td><td>14</td><td>43</td><td>Other Expenses</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>169</td><td>50000</td><td>1</td><td>14</td><td>44</td><td>Misc Expense</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>170</td><td>50000</td><td>2</td><td>13</td><td>1</td><td>Purchases</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>171</td><td>50000</td><td>2</td><td>13</td><td>2</td><td>Purchase Return</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>172</td><td>50000</td><td>2</td><td>13</td><td>3</td><td>Purchase Adjustment</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>173</td><td>50000</td><td>2</td><td>13</td><td>4</td><td>Purchase other Expense</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>174</td><td>50000</td><td>2</td><td>13</td><td>5</td><td>Purchase Discount</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>175</td><td>50000</td><td>3</td><td>1</td><td>1</td><td>Salaries & Wages FF</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>176</td><td>50000</td><td>3</td><td>2</td><td>2</td><td>Field Expenses FF</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>177</td><td>50000</td><td>3</td><td>1</td><td>3</td><td>Overtime Expense FF</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>178</td><td>50000</td><td>3</td><td>1</td><td>4</td><td>Medical Expense FF</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>179</td><td>50000</td><td>3</td><td>1</td><td>5</td><td>Leave Encashment FF</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>180</td><td>50000</td><td>3</td><td>1</td><td>6</td><td>Bonus FF</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>181</td><td>50000</td><td>3</td><td>3</td><td>7</td><td>Commission & Incentive FF</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>182</td><td>50000</td><td>3</td><td>1</td><td>8</td><td>Provident Fund FF</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>183</td><td>50000</td><td>3</td><td>14</td><td>9</td><td>Depreciation - Field Force</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>184</td><td>50000</td><td>3</td><td>5</td><td>10</td><td>Domestic Travelling Expenses FF</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>185</td><td>50000</td><td>3</td><td>8</td><td>11</td><td>Sales Team Meeting Expense FF</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>186</td><td>50000</td><td>3</td><td>9</td><td>12</td><td>Vehicle Repair FF</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>187</td><td>50000</td><td>3</td><td>9</td><td>13</td><td>Office Equipment Repair FF</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>188</td><td>50000</td><td>3</td><td>10</td><td>14</td><td>Rent FF</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>189</td><td>50000</td><td>3</td><td>1</td><td>15</td><td>Vehicle Rent FF</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>190</td><td>50000</td><td>3</td><td>6</td><td>16</td><td>Telephone Expense FF</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>191</td><td>50000</td><td>3</td><td>6</td><td>17</td><td>Mobile Expense FF</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>192</td><td>50000</td><td>4</td><td>5</td><td>1</td><td>Delivery Expenses</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>193</td><td>50000</td><td>4</td><td>7</td><td>2</td><td>Lapourd Courier Service</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>194</td><td>50000</td><td>4</td><td>2</td><td>3</td><td>Fuel</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>195</td><td>50000</td><td>4</td><td>1</td><td>4</td><td>Staff Wages</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>196</td><td>50000</td><td>4</td><td>5</td><td>5</td><td>Travelling & Conyence</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>197</td><td>50000</td><td>4</td><td>1</td><td>6</td><td>Janitorial Expense</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>198</td><td>50000</td><td>4</td><td>7</td><td>7</td><td>Extra Supply</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>199</td><td>50000</td><td>4</td><td>7</td><td>8</td><td>Freight On Sale</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>200</td><td>50000</td><td>4</td><td>7</td><td>9</td><td>W/sale labor</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>201</td><td>50000</td><td>4</td><td>14</td><td>10</td><td>Misc.</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>202</td><td>50000</td><td>4</td><td>1</td><td>11</td><td>Salaries & Wages</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>203</td><td>50000</td><td>4</td><td>6</td><td>12</td><td>Mobile Expense</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>204</td><td>50000</td><td>4</td><td>14</td><td>13</td><td>Stationary</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>205</td><td>50000</td><td>4</td><td>9</td><td>14</td><td>Repair & Maintenence branch</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>206</td><td>50000</td><td>4</td><td>1</td><td>15</td><td>Vechical Rent</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>207</td><td>50000</td><td>4</td><td>10</td><td>16</td><td>Warehouse Rent</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>208</td><td>50000</td><td>4</td><td>14</td><td>17</td><td>Entertainment - Operations</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>209</td><td>50000</td><td>4</td><td>6</td><td>18</td><td>Electricity Expense</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>210</td><td>50000</td><td>4</td><td>9</td><td>19</td><td>Vehicle Maintainance</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>211</td><td>50000</td><td>4</td><td>6</td><td>20</td><td>Utility Bills</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>212</td><td>50000</td><td>4</td><td>1</td><td>21</td><td>Provident Fund</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>213</td><td>50000</td><td>4</td><td>2</td><td>22</td><td>Field Expenses</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>214</td><td>50000</td><td>4</td><td>14</td><td>23</td><td>Tea Expense</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>215</td><td>50000</td><td>4</td><td>14</td><td>24</td><td>Depreciation - Operations</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>216</td><td>50000</td><td>4</td><td>7</td><td>25</td><td>Loading / Unloading</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>217</td><td>50000</td><td>4</td><td>14</td><td>26</td><td>Suspence Account</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>218</td><td>50000</td><td>4</td><td>7</td><td>27</td><td>Sample Expenses</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>219</td><td>50000</td><td>4</td><td>6</td><td>28</td><td>Internet Expense</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>220</td><td>50000</td><td>4</td><td>7</td><td>29</td><td>Packing Material</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>221</td><td>50000</td><td>4</td><td>7</td><td>30</td><td>Courier Charges</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>222</td><td>50000</td><td>4</td><td>10</td><td>31</td><td>Rent Expense</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>223</td><td>50000</td><td>4</td><td>9</td><td>32</td><td>R & M Computers</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>224</td><td>50000</td><td>4</td><td>9</td><td>33</td><td>R & M Electricity</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>225</td><td>50000</td><td>4</td><td>1</td><td>34</td><td>Overtime Expense-Operations</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>226</td><td>50000</td><td>5</td><td>11</td><td>1</td><td>Anti Dumping Duty</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>227</td><td>50000</td><td>5</td><td>11</td><td>2</td><td>Additional Custom Duty</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>228</td><td>50000</td><td>5</td><td>11</td><td>3</td><td>Demurrage</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>229</td><td>50000</td><td>5</td><td>11</td><td>4</td><td>Customs Duty</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>230</td><td>50000</td><td>6</td><td>12</td><td>1</td><td>Income Tax</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>231</td><td>50000</td><td>6</td><td>1</td><td>2</td><td>Gain/Loss on Disposal of PPE</td><td>Debit/credit</td><td>Expense</td></tr>
                                            <tr><td>232</td><td>50000</td><td>6</td><td>12</td><td>3</td><td>Sales Tax</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>233</td><td>50000</td><td>6</td><td>15</td><td>4</td><td>Income from Investment</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>234</td><td>50000</td><td>6</td><td>15</td><td>5</td><td>Profit from saving account</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>235</td><td>50000</td><td>6</td><td>12</td><td>6</td><td>Bank Charges</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>236</td><td>50000</td><td>6</td><td>15</td><td>7</td><td>SD Margin</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>237</td><td>50000</td><td>6</td><td>14</td><td>8</td><td>Vehicle Insurance Expense</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>238</td><td>50000</td><td>6</td><td>14</td><td>9</td><td>Foreign Exchange Gain/Loss</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>239</td><td>50000</td><td>6</td><td>14</td><td>10</td><td>Bad Debt Expense</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>240</td><td>50000</td><td>6</td><td>14</td><td>11</td><td>Damage Stock</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>241</td><td>50000</td><td>6</td><td>14</td><td>12</td><td>Expired Stock</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>242</td><td>50000</td><td>6</td><td>14</td><td>13</td><td>Stock shortage</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>243</td><td>50000</td><td>6</td><td>7</td><td>14</td><td>Stock issue as sample</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>244</td><td>50000</td><td>6</td><td>14</td><td>15</td><td>Loss on disposal of PPE</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>245</td><td>50000</td><td>6</td><td>14</td><td>16</td><td>Donation & Charity</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>246</td><td>50000</td><td>7</td><td>4</td><td>1</td><td>Off Invoice Discount A&P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>247</td><td>50000</td><td>7</td><td>1</td><td>2</td><td>Provident Fund A&P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>248</td><td>50000</td><td>7</td><td>2</td><td>3</td><td>Field Expenses A&P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>249</td><td>50000</td><td>7</td><td>5</td><td>4</td><td>Transportation A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>250</td><td>50000</td><td>7</td><td>11</td><td>5</td><td>Import Duty - A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>251</td><td>50000</td><td>7</td><td>11</td><td>6</td><td>Freight A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>252</td><td>50000</td><td>7</td><td>8</td><td>7</td><td>BA Meeting Expense A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>253</td><td>50000</td><td>7</td><td>8</td><td>8</td><td>BA Uniform A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>254</td><td>50000</td><td>7</td><td>8</td><td>9</td><td>Cleaning Material A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>255</td><td>50000</td><td>7</td><td>4</td><td>10</td><td>Other Promotion - Bourjois A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>256</td><td>50000</td><td>7</td><td>4</td><td>11</td><td>Other Promotion - Max Factor A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>257</td><td>50000</td><td>7</td><td>1</td><td>12</td><td>Mobile Expense - A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>258</td><td>50000</td><td>7</td><td>1</td><td>13</td><td>Health Insurance - A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>259</td><td>50000</td><td>7</td><td>4</td><td>14</td><td>A&amp;P Claim - Rimmel A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>260</td><td>50000</td><td>7</td><td>4</td><td>15</td><td>A&amp;P Claim - Sally Hensen A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>261</td><td>50000</td><td>7</td><td>4</td><td>16</td><td>A&amp;P Claim - Bourjois A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>262</td><td>50000</td><td>7</td><td>4</td><td>17</td><td>A&amp;P Claim - Max Factor A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>263</td><td>50000</td><td>7</td><td>1</td><td>18</td><td>Medical Expense - A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>264</td><td>50000</td><td>7</td><td>4</td><td>19</td><td>Other Promotions A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>265</td><td>50000</td><td>7</td><td>5</td><td>20</td><td>Domestic Travel Expense A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>266</td><td>50000</td><td>7</td><td>5</td><td>21</td><td>International Travel Expense A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>267</td><td>50000</td><td>7</td><td>8</td><td>22</td><td>BA Grooming A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>268</td><td>50000</td><td>7</td><td>10</td><td>23</td><td>Store Opening A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>269</td><td>50000</td><td>7</td><td>1</td><td>24</td><td>BA Salaries A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>270</td><td>50000</td><td>7</td><td>10</td><td>25</td><td>Rent Expenses A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>271</td><td>50000</td><td>7</td><td>10</td><td>26</td><td>Tarde Marketing A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>272</td><td>50000</td><td>7</td><td>4</td><td>27</td><td>Other Promotions - Rimmel A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>273</td><td>50000</td><td>7</td><td>1</td><td>28</td><td>Salaries and Wages - A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>274</td><td>50000</td><td>7</td><td>11</td><td>29</td><td>Forwarding and Clearing - A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>275</td><td>50000</td><td>7</td><td>4</td><td>30</td><td>Rate Difference A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>276</td><td>50000</td><td>7</td><td>4</td><td>31</td><td>Other Promotions - Adidas A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>277</td><td>50000</td><td>7</td><td>1</td><td>32</td><td>Vehicle Rent A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>278</td><td>50000</td><td>7</td><td>4</td><td>33</td><td>Other Promotions - Perfumes A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>279</td><td>50000</td><td>7</td><td>4</td><td>34</td><td>Other Promotions - Coty Luxury A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>280</td><td>50000</td><td>7</td><td>4</td><td>35</td><td>Other Promotions - Essence A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>281</td><td>50000</td><td>7</td><td>3</td><td>36</td><td>Commission and Incentive A&amp;P</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>282</td><td>50000</td><td>7</td><td>8</td><td>37</td><td>A&amp;P budget</td><td>Debit</td><td>Expense</td></tr>
                                            <tr><td>283</td><td>40000</td><td>1</td><td>1</td><td>1</td><td>Other Income</td><td>Credit</td><td>Revenue</td></tr>
                                            <tr><td>284</td><td>40000</td><td>1</td><td>1</td><td>2</td><td>Sales</td><td>Credit</td><td>Revenue</td></tr>
                                            <tr><td>285</td><td>40000</td><td>1</td><td>1</td><td>3</td><td>Sale Freight Expense</td><td>Credit</td><td>Revenue</td></tr>
                                            <tr><td>286</td><td>40000</td><td>1</td><td>1</td><td>4</td><td>Sale other Expense</td><td>Credit</td><td>Revenue</td></tr>
                                            <tr><td>287</td><td>40000</td><td>1</td><td>1</td><td>5</td><td>Sale Adjustment</td><td>Credit</td><td>Revenue</td></tr>
                                            <tr><td>288</td><td>40000</td><td>2</td><td>1</td><td>6</td><td>Sale Discount</td><td>Debit</td><td>Revenue</td></tr>
                                            <tr><td>289</td><td>40000</td><td>2</td><td>1</td><td>7</td><td>Extra discount</td><td>Credit</td><td>Revenue</td></tr>
                                            <tr><td>290</td><td>40000</td><td>3</td><td>1</td><td>8</td><td>Sale Return</td><td>Credit</td><td>Revenue</td></tr>
                                            <tr><td>291</td><td>40000</td><td>3</td><td>1</td><td>8</td><td>Import Saving</td><td>Credit</td><td>Other Income</td></tr>
                                            <tr><td>292</td><td>60000</td><td>1</td><td>1</td><td>2</td><td>Cost of Goods Sold</td><td>Debit</td><td>COGS</td></tr>
                                            <tr><td>293</td><td>60000</td><td>1</td><td>1</td><td>6</td><td>Incremental purchase cost</td><td>Debit</td><td>Expense</td></tr>
                                        </tbody>
                                    </table>
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