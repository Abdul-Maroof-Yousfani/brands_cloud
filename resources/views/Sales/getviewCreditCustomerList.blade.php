<?php
use App\Helpers\ReuseableCode;
use App\Helpers\CommonHelper;
$delete = ReuseableCode::check_rights(219);
$edit = ReuseableCode::check_rights(499);
$view = ReuseableCode::check_rights(498);
$createAccount = ReuseableCode::check_rights(220);
?>
<table class="userlittab table table-bordered sf-table-list">
    <thead>
        <tr>
            <th class="text-center col-xs-4">Customer Name</th>
            <th class="text-center col-xs-1">Phone No</th>
            <th class="text-center">Address</th>
            <th class="text-center">Territory</th>
            <th class="text-center">Contact Person</th>
            <th class="text-center col-xs-1">Shipping Type</th>
            <th class="text-center col-xs-1">Opening Balance</th>
            <th class="text-center">Opening Balance Date
            </th>
            <th class="text-center">Status</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody id="data">
        @foreach ($customers as $key => $row)
            <tr id="<?php echo $row->id; ?>">
                <td class="text-center"><?php echo $row['name']; ?></td>
                <td class="text-center"><?php echo $row['phone_1']; ?></td>
                <td class="text-center"><?php echo $row['address']; ?></td>
                <td class="text-center"><?php echo CommonHelper::territory_name($row['territory_id'],2)?></td>
                <td class="text-center"><?php echo $row['contact_person']; ?></td>
                <td class="text-center"><?php echo $row['company_shipping_type']; ?></td>
                <td class="text-center"><?php echo $row['opening_balance']; ?></td>
                <td class="text-center"><?php echo $row['opening_balance_date']; ?></td>
                <td class="text-center"><?php echo $row['status'] == 1 ? 'Approved' : 'Pending'; ?></td>

                <td class="text-center hidden-print printListBtn">
                    <div class="dropdown">
                        <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown"
                            aria-expanded="false">...</button>
                        <ul class="dropdown-menu">
                            <li><?php if($view == true):?>
                                <a class="btn btn-sm btn-success"
                                    onclick="showDetailModelOneParamerter('sales/viewCustomer/<?php echo $row->id; ?>')"><i
                                        class="fa fa-eye" aria-hidden="true"></i> View</a>
                                    <?php endif;?>      
                                <?php if($row->acc_id == 0):?>
                                <?php if($createAccount == true):?>
                                <span id="ShowHide<?php echo $row->id; ?>">
                                    <button type="button" class="btn btn-sm btn-primary" id="Btn<?php echo $row->id; ?>"
                                        onclick="CreateAccount('<?php echo $row->acc_id; ?>','<?php echo $row->name; ?>','<?php echo $row->id; ?>')">
                                        Create Account</button>
                                </span>
                                <?php endif;?>
                                <?php else:?>
                                <?php endif;?>
                                  <?php if($edit == true):?>
                                <a href="<?php echo url('/'); ?>/sales/editCustomerForm/<?php echo $row->id; ?>?m=1"
                                    class="btn btn-sm btn-warning "><i class="fa fa-pencil" aria-hidden="true"></i>
                                    Edit</a>
                                     <?php endif;?>
                                <?php if($delete == true):?>
                                <button type="button" onclick="CustomerDelete('<?php echo $row->id; ?>')"
                                    class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i>
                                    Delete</button>
                                <?php endif;?>
                                <?php if($row->status == 0):?>
                                <a href="<?php echo url('/'); ?>/sales/approveCustomer/<?php echo $row->id; ?>?m=1"
                                    class="btn btn-sm btn-warning "><i class="fa fa-pencil" aria-hidden="true"></i>
                                    Approve</a>
                                <?php endif;?>
                            </li>
                        </ul>
                    </div>



                </td>
            </tr>
        @endforeach
    </tbody>
</table>
 <div class="row d-flex" id="paginationLinks">
     <div class="col-md-6">
         <p> Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }}
             entries</p>
     </div>
     <div class="col-md-6 text-right">
         <div id="">
             {{ $customers->links() }}
         </div>
     </div>
 </div>
