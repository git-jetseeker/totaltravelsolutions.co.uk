<?php



namespace App\Http\Controllers;



use App\Models\airport;

use App\Models\airports_bookings;

use App\Models\Company;

use App\Models\pages;

use App\Models\support_departments;

use App\Models\ticket_chat;

use App\Models\tickets;

use App\Models\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Crypt;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Log;

use GuzzleHttp\Client;



class TicketsController extends Controller

{

    public function getPagebySlug()

    {

        $url = explode('/', URL::full());



        $page = pages::where('slug', $url[3])->where('type', 'main')->first();

        if ($page) {

            return $page;

        } else {

            $page = (object) $page;



            $page->meta_title = '';

            $page->meta_keyword = '';

            $page->meta_description = '';



            return $page;



        }

    }

    

     public function receiveFile(Request $request)

    {

        // Validate the request

        $request->validate([

            'attachment' => 'required|file',

        ]);

        $file = $request->file('attachment');

        $publicPath = public_path('supports');

        // Move the uploaded file to the public uploads directory

        $fileName = time() . '_' . $file->getClientOriginalName(); // You can customize the filename as needed

        $file->move($publicPath, $fileName);



        // Return the full URL of the uploaded file

        $fileUrl = 'supports/' . $fileName;

        return response()->json(['path' => $fileUrl], 200);

    }

    

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()

    {

        //

        $page = $this->getPagebySlug();

        $airports = airport::all()->where('status', 'Yes');

        $departementslist = support_departments::all()->toArray();

        $departements_list = [];

        $departements_list[''] = 'Select Department';

        foreach ($departementslist as $u) {

            $departements_list[$u['id']] = $u['name'];

        }



        return view('frontend.customer_support', ['airports' => $airports, 'departements_list' => $departements_list, 'page' => $page]);

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        //

    }



    /**

     * Store a newly created resource in storage.

     *

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request)

{

     $messages = [
    'ref_no.required' => 'Booking reference number is required.',
    'ref_no.string' => 'Reference number must be valid.',
    'ref_no.max' => 'Reference number cannot be longer than 255 characters.',

    'full_name.required' => 'Last name is required.',
    'full_name.string' => 'Last name must be valid text.',

    'email.required' => 'Email address is required.',
    'email.string' => 'Email must be valid.',
    'email.email' => 'Enter a valid email address.',

    'contact.required' => 'Contact number is required.',
    'contact.regex' => 'Please enter a valid UK contact number.',
    'contact.max' => 'Contact number cannot be longer than 15 digits.',

    'department.required' => 'Please select a department.',

    'priority.required' => 'Please select the priority level.',

    'subject.required' => 'Ticket subject is required.',

    'message.required' => 'Message field cannot be empty.',
    'message.string' => 'Message must be valid text.',

    'supportdeskpolicy.required' => 'You must agree to the Support Policy and Terms of Service.',

    'attatchment.mimes' => 'Attachment must be one of: jpeg, bmp, png, pdf, doc, docx.',
    'attatchment.max' => 'Attachment may not be greater than 2MB.',
];




    $validator = Validator::make($request->all(), [

        'ref_no' => 'required|string|max:255',

        'full_name' => 'required|string',

        'email' => 'required|string|email',

        'contact' => ['required', 'regex:/^(?:0|\+?44)[0-9]{7,15}$/', 'max:15'],

        'department' => 'required',

        'priority' => 'required',

        'subject' => 'required',

        'message' => 'required|string',

        'supportdeskpolicy' => 'required',

        'attatchment' => 'nullable|mimes:jpeg,bmp,png,pdf,doc,docx|max:2000',

    ], $messages);



    if ($validator->fails()) {

        return redirect()->back()

            ->withErrors($validator, 'ticket_store')

            ->withInput();

    }



    try {

        DB::beginTransaction();



        $booking = airports_bookings::where('referenceNo', $request->input('ref_no'))

            ->where('email', $request->input('email'))

            ->first();



        if (!$booking) {

            $validator->getMessageBag()->add('ref_no', 'Invalid reference number or email.');

            return redirect()->back()

                ->withErrors($validator, 'ticket_store')

                ->withInput();

        }



        $company = Company::where('id', $booking->companyId)

            ->orWhere('aph_id', $booking->companyId)

            ->first();



        // Create new ticket

        $ticket = new tickets();

        $ticket->title = $request->input('subject');

        $ticket->booking_ref = $request->input('ref_no');

        $ticket->user_id = $booking->customerId;

        $ticket->company_admin_id = $company ? $company->admin_id : null;

        $ticket->name = $request->input('full_name');

        $ticket->email = $request->input('email');

        $ticket->contact = $request->input('contact');

        $ticket->department = $request->input('department');

        $ticket->urgency = $request->input('priority');

        $ticket->date = date('Y-m-d H:i:s');

        $ticket->status = 'open';

        $ticket->agent_id = '9';

        $ticket->save();



        $ticketId = DB::getPdo()->lastInsertId();

        $ticketRef = 'JST' . date('dmy') . $ticketId;

        $ticket->ticket_id = $ticketRef;

        $ticket->save();



        // Handle attachment (publicly accessible)

        $attachmentPath = null;

        // if ($request->hasFile('attatchment')) {

        //     $file = $request->file('attatchment');

        //     $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());

        //     $file->move(public_path('supports'), $filename);

        //     $attachmentPath = 'supports/' . $filename;

        // }
        
        if ($request->hasFile('attatchment')) {
    $file = $request->file('attatchment');
    $attachmentPath = $file->store('public/supports');
}



        // Create initial chat entry

        $chatData = [

            'message' => $request->input('message'),

            'attachment' => $attachmentPath,

            'clientunread' => 'No',

            'adminunread' => 'Yes',

            'replyingtime' => date('Y-m-d H:i:s'),

            'replyingadmin' => $booking->customerId,

        ];

        $ticket->chat()->create($chatData);



        // Prepare email details

        $encryptedTicketId = Crypt::encrypt($ticketRef);

        $link = 'https://www.totaltravelsolutions.co.uk/ticket/view/' . $encryptedTicketId;



        $templateData = [

            'username' => $request->input('full_name'),

            'link' => $link,

            'subject' => $request->input('subject'),

            'urgency' => $request->input('priority'),

            'status' => 'open',

            'ticket_ref' => $ticketRef,

            'msg' => $request->input('message'),

        ];



        $emailController = new EmailController();



        // Send email to department

        $department = support_departments::find($request->input('department'));

        if ($department && $department->email) {

            $emailController->sendGmail('ticket_reply_client', $department->email, $templateData);

        }



        // Send confirmation to client

        $emailController->sendGmail('ticket_reply_client', $request->input('email'), $templateData);



        DB::commit();



        return redirect(route('view-ticket', ['id' => $encryptedTicketId]))

            ->with('success', 'Ticket created successfully.');



    } catch (\Exception $e) {

        DB::rollBack();
        //dd($e->getMessage());


        Log::error('Ticket creation failed', [

            'error' => $e->getMessage(),

            'trace' => $e->getTraceAsString(),

        ]);



        return redirect()->back()

            ->withErrors(['error' => 'An unexpected error occurred while creating your ticket. Please try again later.'])

            ->withInput();

    }

}





    /**

     * Display the specified resource.

     *

     * @param  \App\tickets  $tickets

     * @return \Illuminate\Http\Response

     */

    public function show(tickets $tickets)

    {

        //

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\tickets  $tickets

     * @return \Illuminate\Http\Response

     */

    public function edit(tickets $tickets)

    {

        //

    }



    public function submit_reply(Request $request)

    {



        $messages = [

            'required' => 'This field is required.',

            'attatchment.max' => 'The document may not be greater than 2 megabytes',

        ];



        $validatedData = Validator::make(request()->all(), [

            'ticket_id' => 'required|string|max:255',

            'replyingadmin' => 'required|string',

            'ticket_ref' => 'required|string',

            // 'contact' => 'required',

            //'department' => 'required',

            //'priority' => 'required',

            //'subject' => 'required',

            'message' => 'required|string',

            'attatchment' => 'mimes:jpg,jpeg,bmp,png|max:2000', //2mb file can be uploaded

        ], $messages);



        $path = '';

        // if ($request->hasFile('attatchment')) {



        //     // $path = $request->file('attatchment')->store('public/supports');

        //     // $imagePath = $request->file('attatchment');

        //     // $imageName = $imagePath->getClientOriginalName();

        //     // $request->file('attatchment')->storeAs('public/supports', $imageName);

        //     // $path = 'supports/'.$imageName;

        //     // $ticket->file = $path;

        //     $client = new Client();

        //     $file = $request->file('attatchment');

        //     $fileNameOrg = $file->getClientOriginalName();

        //     $extension = $file->getClientOriginalExtension();

        //     $fileName = uniqid().'_'.$fileNameOrg;

        //      $response = $client->post('https://www.totaltravelsolutions.co.uk/api/receive-file', [

        //         'multipart' => [

        //             [

        //                 'name'     => 'attachment',

        //                 'contents' => fopen($request->file('attatchment'), 'r'),

        //                 'filename' => $fileName

        //             ]

        //         ]

        //     ]);

        //     $responseData = json_decode($response->getBody()->getContents(), true);

        //     $path = $responseData['path'];

        // }

if ($request->hasFile('attatchment')) {
        $path = $request->file('attatchment')->store('public/supports');
    }

        $data = [

            'message' => $request->input('message'),

            'ticket_id' => $request->input('ticket_id'),

            'attachment' => $path,

            'clientunread' => 'No',

            'adminunread' => 'Yes',

            'replyingtime' => date('Y-m-d H:i:s'),

            'replyingadmin' => $request->input('replyingadmin'),

            'reply_by' => $request->input('reply_by'),

        ];

        if (count($validatedData->errors()->messages()) > 0) {

            //var_dump($validatedData->errors());

            return redirect()->back()->withErrors($validatedData)->withInput();

        } else {

            $chat_data = ticket_chat::create($data);

            if ($chat_data) {

                $ticket = tickets::where('ticket_id', $request->input('ticket_ref'))->first();



                // dd($ticket);

                $tickref = Crypt::encrypt($request->input('ticket_ref'));

                $link = 'https://www.totaltravelsolutions.co.uk/ticket/view/'.$tickref;

                $email = new EmailController();



                $template_data = [];

                $template_data['username'] = $request->input('name');

                $template_data['link'] = $link;

                $template_data['subject'] = $ticket->title;

                $template_data['ticket_ref'] = $request->input('ticket_ref');

                $template_data['msg'] = $request->input('message');



                if ($request->input('reply_by') == 'Client') {



                    if ($ticket->assign_to == 0) {

                        $department = support_departments::where('id', $ticket->department)->first();

                        $toEmail = $department->email;

                    } else {

                        $user = User::where('id', $ticket->assign_to)->first();

                        $toEmail = $user->email;

                    }

                    $email->sendGmail('ticket_reply_client', $toEmail, $template_data);



                } else {



                    $toEmail = $ticket->email;



                    $email->sendGmail('ticket_reply_company', $toEmail, $template_data);

                }



                return redirect()->back();

            }

        }



    }



    public function view($id)

    {



        $id = Crypt::decrypt($id);

        $ticket = tickets::where('ticket_id', $id)->orderBy('id', 'desc')->first();



        $department = support_departments::where('id', $ticket->department)->orderBy('id', 'desc')->first();

        $progress = ticket_chat::where('ticket_id', $ticket->id)->orderBy('id', 'desc')->first();

        $companyMsg = '';

        if ($progress->reply_to == 'All') {

            if ($progress->clientunread == 'Yes') {

                $companyMsg = 'Awaiting for Client Read';

            } elseif ($progress->Companyread == 'No') {

                $companyMsg = 'Awaiting for Company Read';

            } else {

                $companyMsg = 'Awaiting for Client and Company Response';

            }

        } elseif ($progress->reply_to != 'All') {

            if ($progress->reply_by == 'Client' && $progress->hold == 'Yes') {

                $companyMsg = 'Awaiting for admin to show to Company';

            } elseif ($progress->reply_by == 'Company' && $progress->hold == 'Yes') {

                $companyMsg = 'Awaiting for Admin to show to Client';

            } elseif ($progress->reply_by == 'Admin' && $progress->reply_to == 'Company') {

                $companyMsg = 'Awaiting for Company Reply';

            } elseif ($progress->reply_by == 'Admin' && $progress->reply_to == 'Client') {

                $companyMsg = 'Awaiting for Client Reply';

            } else {

                $companyMsg = 'Awaiting for Response';

            }

        }



        $airports = airport::all()->where('status', 'Yes');



        return view('frontend.viewticket', ['companyMsg' => $companyMsg, 'progress' => $progress, 'id' => $id, 'airports' => $airports, 'model' => $ticket, 'ticket' => $ticket, 'department' => $department]);



    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \App\tickets  $tickets

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, tickets $tickets)

    {

        //

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\tickets  $tickets

     * @return \Illuminate\Http\Response

     */

    public function destroy(tickets $tickets)

    {

        //

    }



    public function search_ticket(Request $request)
    {
        $messages = [
            'email.required' => 'Email address is required.',
            'email.email' => 'Enter a valid email address.',
            'email.max' => 'Email address cannot be longer than 255 characters.',
            'ref_no.required' => 'Ticket reference is required.',
            'ref_no.string' => 'Ticket reference must be valid text.',
            'ref_no.max' => 'Ticket reference cannot be longer than 255 characters.',
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'ref_no' => 'required|string|max:255',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'search_ticket')
                ->withInput();
        }

        $booking = tickets::where('ticket_id', $request->input('ref_no'))
            ->where('email', $request->input('email'))
            ->first();

        if ($booking) {
            $tickref = Crypt::encrypt($booking->ticket_id);

            return redirect(route('view-ticket', ['id' => $tickref]));
        }

        $validator->getMessageBag()->add(
            'ref_no',
            'No ticket found for that email and ticket reference. Please check your details and try again.'
        );

        return redirect()->back()
            ->withErrors($validator, 'search_ticket')
            ->withInput();
    }

}

