<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Models\Friends;
use App\Http\Models\Friends_Request;
use App\Http\Models\User;
use Illuminate\Http\Request;

class FriendReqController extends Controller
{
    public function index(Request $request)
    {
        $searchParams = $request->all();
        $User = User::all();
        return response()->json(['user' => $User], 200);
    }

    public function store(Request $request, $action)
    {
        $request->validate([
            'requestor' => 'required|email',
            'to' => 'required|email'
        ], [
            'requestor.required' => 'Requestor is required',
            'to.required' => 'To is required',
            'requestor.emai' => 'Requestor must email type',
            'to.email' => 'To must email type',

        ]);
        $data = $request->json()->all();
        $req = User::where('email', $data['requestor'])->first();
        if ($req) {
            $req_id = $req->id;
        } else {
            return response()->json(['error' => 'Email Requestor not found'], 400);
        }

        $to = User::where('email', $data['to'])->first();
        if ($to) {
            $to_id = $to->id;
        } else {
            return response()->json(['error' => 'Email To not found'], 400);
        }

        switch ($action) {
            case ('send'):
                $cekexist = Friends_Request::where('user_req_id', $req_id) //cek apakah sudah pernah add
                    ->where('user_to_id', $to_id)
                    ->where('is_accept', 0)
                    ->first();
                if (empty($cekexist)) {
                    $friend_req = Friends_Request::create([
                        'user_req_id' => $req_id,
                        'user_to_id' => $to_id,
                        'is_accept' => 0 //request send
                    ]);
                    return response()->json(['success' => "True"], 201);
                } else {
                    return response()->json(['message' => "You've added friends before, wait until the user accepts"], 200);
                }

                break;

            case ('approve'):
                $cekexist = Friends_Request::where('user_req_id', $req_id) //cek apakah sudah di approve
                    ->where('user_to_id', $to_id)
                    //->where('is_accept', 1)
                    ->first();
                if ($cekexist->is_accept == 0) {
                    $data = array(
                        'is_accept' => 1 //approve
                    );
                    $upd = Friends_Request::where('user_req_id', $req_id)->where('user_to_id', $to_id)
                        ->update(
                            $data
                        );
                    //add to friend list
                    $friend_req = Friends::create([
                        'user_id' => $to_id,
                        'friends_user_id' => $req_id,
                        'status' => 0 //friends
                    ]);

                    return response()->json(['success' => "True"], 201);
                } elseif ($cekexist->is_accept == 2) {
                    return response()->json(['message' => "Sorry, you've reject friendship before"], 400);
                } else {
                    return response()->json(['message' => "Sorry, you've received friendship before"], 400);
                }


                break;
            case ('reject'):
                $cekexist = Friends_Request::where('user_req_id', $req_id) //cek apakah sudah di reject
                    ->where('user_to_id', $to_id)
                    //->where('is_accept', 2)
                    ->first();
                if ($cekexist->is_accept == 0) {
                    $data = array(
                        'is_accept' => 2 //approve
                    );
                    $upd = Friends_Request::where('user_req_id', $req_id)->where('user_to_id', $to_id)
                        ->update(
                            $data
                        );

                    return response()->json(['success' => "True"], 201);
                } elseif ($cekexist->is_accept == 1) { 
                    return response()->json(['message' => "Sorry, you've approve friendship before"], 400);
                } else {
                    return response()->json(['message' => "Sorry, you've reject friendship before"], 400);
                }




                break;

            default:

                return response()->json(['error' => "Something went wrong"], 400);
        }






        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 403);
        // } else {
        //     $params = $request->all();
        //     $Company = Company::create([
        //         'sbu_id' => $params['sbu_id'],
        //         'company_code' => $params['company_code'],
        //     ]);
        //     //$role = Role::findByName($params['role']);

        //     return response()->json(['message' => "Success Create Company"], 201);
        // }
    }

    public function list(Request $request)
    {
        $data = $request->json()->all();
        $usermodel = new User();
        $user = $usermodel->cekEmail($data['email']);
        if($user) {
            $friend_req = new Friends_Request();
            $list = $friend_req->getListFriendReq($user->id);
            return response()->json(['requests' => $list], 200);

        } else {
            return response()->json(['error' => 'Email not found'], 400);
        }
        
    }

    
}
