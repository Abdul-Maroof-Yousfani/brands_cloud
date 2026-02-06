<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesTaxInvoice extends Model{
    protected $connection =  'mysql2';

    protected $table = 'sales_tax_invoice';
    //protected $fillable = ['code','parent_code','level1','level2','level3','level4','level5','level6','level7','name','status','branch_id','username','date','time','action','trail_id','operational'];
    protected $primaryKey = 'id';
    public $timestamps = false;

     protected static function boot() {
        parent::boot();

        $type = "Sale Tax Invoice";
        $reference_number = "gi_no";
        static::created(function($st_invoice) use ($type, $reference_number) {
            \App\Helpers\CommonHelper::createNotification($type . " with " . $st_invoice->{$reference_number} . " is created by " . auth()->user()->name, $type . "");
        });

        static::updating(function($st_invoice) use ($type, $reference_number) {
            $userName = auth()->user()->name ?? 'System';

            // isDirty() is safe on ALL versions of Laravel 5.x
            if($st_invoice->isDirty("si_status") && $st_invoice->si_status == 3) {
                \App\Helpers\CommonHelper::createNotification(
                    $type . " with " . $st_invoice->{$reference_number} . " is approved by " . $userName, 
                    $type . ""
                );
            } else if($st_invoice->isDirty("si_status") && $st_invoice->status == 2){
                // This fires if ANY column changed, but NOT to status 1
                \App\Helpers\CommonHelper::createNotification(
                    $type . " with " . $st_invoice->{$reference_number} . " is deleted by " . $userName, 
                    $type . ""
                );
            } else {
                \App\Helpers\CommonHelper::createNotification(
                    $type . " with " . $st_invoice->{$reference_number} . " is updated by " . $userName, 
                    $type . ""
                );
            }
        });

        static::deleted(function($st_invoice) use ($type, $reference_number) {
            \App\Helpers\CommonHelper::createNotification($type . " with " . $st_invoice->{$reference_number} . " is deleted by " . auth()->user()->name, $type . "");
        });
    }


        public function currencyRelation()
        {
            return $this->belongsTo(Currency::class, 'currency');
        }
}
