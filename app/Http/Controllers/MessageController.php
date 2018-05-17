<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpSms;

class MessageController extends Controller
{
    /**
     * 短信发送
     */
    public function send(Request $request)
    {
        //写入日志
        file_put_contents('./log', $request->ip().'----'.$request->phone, FILE_APPEND);

    	//验证
    	$validator = \Validator::make($request->all(), [
            'phone' => [
                'required',
                'regex: /^[1][3,4,5,6,7,8,9][0-9]{9}$/'
            ],
            'code' => [
                'required',
                'regex: /^\d{4,6}$/'
            ],
            'sign' => [
                'required',
                'regex: /'.env('sign').'/'
            ]
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>'params error','code'=>'0002']);
        }

        //获取手机号参数
        $to = $request->phone;

        $templates = [
            'Ucpaas' => env('Template_id')
        ];

        $tempData = [
            'code' => $request -> code
        ];

        $res = PhpSms::make()
            ->to($to)
            ->template($templates)
            ->data($tempData)
            ->send(); 

        //检测结果
        if($res['success']){
            return response()->json(['status'=>'ok','code'=>'0000']);
        }else{
            return response()->json(['status'=>'fail','code'=>'0001']);
        }
    }
}
