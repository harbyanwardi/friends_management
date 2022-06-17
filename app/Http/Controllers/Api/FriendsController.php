<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Models\Friends;
use App\Http\Models\User;
use Illuminate\Http\Request;

class FriendsController extends Controller
{
    public function list(Request $request)
    {
        $data = $request->json()->all();
        $usermodel = new User();
        $user = $usermodel->cekEmail($data['email']);
        if ($user) {
            $userfriend = new Friends();
            $list = $userfriend->getListFriend($user->id);
            return response()->json(['friends' => $list], 200);
        } else {
            return response()->json(['error' => 'Email not found'], 400);
        }
    }

    public function listbetween(Request $request)
    {
        $data = $request->json()->all();
        $email1 = $data['friends'][0];
        $email2 = $data['friends'][1];
        $usermodel = new User();
        $user1 = $usermodel->cekEmail($email1);
        $user2 = $usermodel->cekEmail($email2);
        if ($user1 && $user2) {
            $userfriend = new Friends();
            $list = $userfriend->getListFriendBetween($user1->id, $user2->id);
            return response()->json([
                'success' => true,
                'friends' => $list,
                'count' => count($list)
            ], 200);
        } else {
            return response()->json(['error' => 'Email not found'], 400);
        }
    }

    public function block(Request $request)
    {
        $request->validate([
            'requestor' => 'required|email',
            'block' => 'required|email'
        ], [
            'requestor.required' => 'Requestor is required',
            'block.required' => 'block is required',
            'requestor.emai' => 'Requestor must email type',
            'block.email' => 'block must email type',

        ]);
        $data = $request->json()->all();
        //cek email req exist
        $req = User::where('email', $data['requestor'])->first();
        if ($req) {
            $req_id = $req->id;
        } else {
            return response()->json(['error' => 'Email Requestor not found'], 400);
        }

        //cek email to exist
        $to = User::where('email', $data['block'])->first();
        if ($to) {
            $to_id = $to->id;
        } else {
            return response()->json(['error' => 'Email Block not found'], 400);
        }

        $data = array(
            'status' => 1 //block
        );
        $cekblock = Friends::where('user_id', $req_id)->where('friends_user_id', $to_id)->where('status', 1)->first();
        if (!empty($cekblock)) {
            return response()->json(['message' => "Sorry, you've blocked friendship before"], 400);
        } else {
            $upd = Friends::where('user_id', $req_id)->where('friends_user_id', $to_id)
                ->update(
                    $data
                );
            if ($upd > 0) {
                return response()->json(['success' => 'True'], 200);
            } else {
                return response()->json(['error' => 'you are not friend'], 400);
            }
        }
    }
}
