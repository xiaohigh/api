<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    /**
     * 邮件发送
     * @param name 发送者名字
     * @param to  接受者邮箱账号
	 * @param title 邮件标题
     * @param content 发送内容  
     */
    public function send(Request $request)
    {
    	//验证参数
    	$validator = \Validator::make($request->all(), [
            'name' => [
                'required',
            ],
            'to' => [
                'required',
                'regex: /^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.[a-zA-Z0-9]{2,6}$/'
            ],
            'title' => [
            	'required'
            ],
            'content'=>[
            	'required'
            ],
            'sign' => [
                'required',
                'regex: /'.env('sign').'/'
            ]
        ]);

    	//发送失败
        if ($validator->fails()) {
            return response()->json(['status'=>'params error','code'=>'0002']);
        }

        //发送内容
    	$data = [
    		'content' => $request->content
    	];

    	//发送邮件
	    $res = Mail::send('emails.email', $data, function ($message) use($request) {
	        $message->from('admin@mail.xiaohigh.com', $request->name);
	        $message->to($request->to);
	        $message->subject($request->title);
	    });
    }

}
