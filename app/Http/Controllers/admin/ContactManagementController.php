<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\ContactModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactManagementController extends Controller
{

    private $contact;

    public function __construct()
    {
        $this->contact = new ContactModel();
    }
    public function index()
    {
        $title = 'Liên hệ';

        $contacts = $this->contact->getContacts();
        // dd($contacts);

        return view('admin.contact', compact('title', 'contacts'));
    }

    public function replyContact(Request $request)
    {
        $contactId = $request->contactId;
        $emailReply = $request->email;
        $messageContent = $request->message;
        // Kiểm tra xem message có phải là chuỗi
        if (is_object($messageContent)) {
            $messageContent = (string) $messageContent; // Chuyển đối tượng thành chuỗi nếu cần
        }
        try {
            Mail::send('admin.emails.reply-contact', compact('messageContent'), function ($message) use ($emailReply) {
                $message->to($emailReply)
                    ->subject('Phản hồi liên lệ của khách hàng');
            });
            // Cập nhật trạng thái
            $dataUpdate = [
                'isReply' => 'y'
            ];
            $this->contact->updateContact($contactId, $dataUpdate);

            return response()->json([
                'success' => true,
                'message' => 'Phản hồi qua email thành công.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể gửi email: ' . $e->getMessage(),
            ], 500);
        }
    }
}
