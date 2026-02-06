<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryNote extends Model
{
    protected $connection =  'mysql2';
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $table = 'delivery_note';
    //protected $fillable = ['code','parent_code','level1','level2','level3','level4','level5','level6','level7','name','status','branch_id','username','date','time','action','trail_id','operational'];
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected static function boot() {
        parent::boot();

        static::created(function($delivery_note) {
            \App\Helpers\CommonHelper::createNotification("Delivery Note with " . $delivery_note->gd_no . " is created by " . auth()->user()->name, "Delivery Note");
        });

        static::updating(function($delivery_note) {
            $userName = auth()->user()->name ?? 'System';

            // isDirty() is safe on ALL versions of Laravel 5.x
            if($delivery_note->isDirty("status") && $delivery_note->status == 1) {
                \App\Helpers\CommonHelper::createNotification(
                    "Delivery Note with " . $delivery_note->gd_no . " is approved by " . $userName, 
                    "Delivery Note"
                );
            } else if($delivery_note->isDirty("status") && $delivery_note->status == 2){
                // This fires if ANY column changed, but NOT to status 1
                \App\Helpers\CommonHelper::createNotification(
                    "Delivery Note with " . $delivery_note->gd_no . " is deleted by " . $userName, 
                    "Delivery Note"
                );
            } else {
                \App\Helpers\CommonHelper::createNotification(
                    "Delivery Note with " . $delivery_note->gd_no . " is updated by " . $userName, 
                    "Delivery Note"
                );
            }
        });

        static::deleted(function($delivery_note) {
            \App\Helpers\CommonHelper::createNotification("Delivery Note with " . $delivery_note->gd_no . " is deleted by " . auth()->user()->name, "Delivery Note");
        });
    }

    public function customer() {
        return $this->belongsTo(Customer::class, "buyers_id");
    }
}
