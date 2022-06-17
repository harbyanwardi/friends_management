<?php
/**
 * File Permission.php
 *
 * @author Tuan Duong <bacduong@gmail.com>
 * @package Laravue
 * @version 1.0
 */

namespace App\Http\Models;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Permission
 *
 * @package App\Laravue\Models
 */
class Friends extends Model
{
    public $guard_name = 'api';

    /**
     * To exclude permission management from the list
     *
     * @param $query
     * @return Builder
     */
    protected $table = "friends_list";
    protected $guarded = ['friends_list_id'];
    public $timestamps = false;

    public function getListFriend($user_id)
    {
        $data = array();
        $ReqQuery = Friends::query();
       
        $ReqQuery->join('users as from', 'from.id', '=', 'friends_list.friends_user_id')
            ->where('user_id',$user_id)
            ->where('status',0) //friend active
            ->select(
                'from.email as email'
                
            );
        
        $Req = $ReqQuery->get();
        for ($i = 0; $i < count($Req); $i++) {
            $data[$i] = $Req[$i]->email;
        }
        return $data;
    }

    public function getListFriendBetween($user1,$user2)
    {
        $data = array();

        $Req = DB::select("
        SELECT T3.email 
        FROM ( SELECT * FROM `friends_list` WHERE user_id = $user1 and status = 0) as T1 
        JOIN ( SELECT * FROM `friends_list` WHERE user_id = $user2 and status = 0) as T2 
        ON T1.friends_user_id = T2.friends_user_id 
        JOIN users as T3 ON T3.id = T1.friends_user_id;
            
        ");
        

        for ($i = 0; $i < count($Req); $i++) {
            $data[$i] = $Req[$i]->email;
        }
        return $data;
    }
}
