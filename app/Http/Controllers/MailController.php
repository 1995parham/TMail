<?php
/**
 * In The Name Of God
 *
 * PHP Version 5
 *
 * @category HttpController
 * @package  TMail\Http\Controllers
 * @author   Parham Alvani <parham.alvani@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     Link
 */
namespace TMail\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use TMail\Http\Requests;
use TMail\Mail as Mail;

class MailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function inbox(string $by = 'created_at')
    {
        $mails = Mail::where('recipient', Auth::user()->email)
            ->orderBy($by, 'desc')
            ->paginate(5);

        return response()->json($mails);
    }

    public function sent(string $by = 'created_at')
    {
        $mails = Mail::where('author', Auth::user()->email)
            ->orderBy($by, 'desc')
            ->paginate(5);

        return response()->json($mails);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'recipient' => 'required|email|exists:users,email',
            'content' => 'required',
            'attachments' => 'array',
        ]);

        $mail = Mail::create([
            'title' => $request['title'],
            'author' => Auth::user()->email,
            'recipient' => $request['recipient'],
            'content' => $request['content']
        ]);
        if (isset($request['attachments'])) {
            $mail['attachments'] = $request['attachments'];
        }
        $mail->save();

        return response()->json($mail);
    }

    public function destory(Mail $mail)
    {
    }

    public function read(Mail $mail)
    {
        $mail->readed_at = Carbon::now();
        $mail->save();

        return response()->json($mail);
    }
}
