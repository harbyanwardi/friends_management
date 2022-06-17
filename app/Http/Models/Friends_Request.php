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
class Friends_Request extends Model
{
    public $guard_name = 'api';

    /**
     * To exclude permission management from the list
     *
     * @param $query
     * @return Builder
     */
    protected $table = "friends_request";
    protected $guarded = ['friends_request_id'];
    public $timestamps = false;

    public function getListFriendReq($to_id)
    {
        $ReqQuery = Friends_Request::query();
       
        $ReqQuery->join('users as from', 'from.id', '=', 'friends_request.user_req_id')
            ->where('user_to_id',$to_id)
            ->select(
                'from.email as requestor',
                DB::raw('(CASE 
                        WHEN is_accept = "0" THEN "pending"
                        WHEN is_accept = "1" THEN "approve"
                        WHEN is_accept = "2" THEN "reject"
                        END) AS status')
                
            );
        

        return $ReqQuery->get();
    }
}
