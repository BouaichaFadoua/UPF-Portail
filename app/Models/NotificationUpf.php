<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationUpf extends Model {
    use HasFactory;
    protected $table    = 'notifications_upf';
    protected $fillable = ['user_id','titre','message','lien','lu','type'];
    protected $casts    = ['lu' => 'boolean'];

    public function user() { return $this->belongsTo(User::class); }
}
