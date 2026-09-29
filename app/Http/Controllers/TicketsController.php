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

use Illuminate\Support\Facades\Schema;

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
        return $this->supportPageResponse();
    }

    /**
     * Re-render Customer Support with validation errors (no redirect/flash).
     * JetSeeker parity — keeps old input + named error bags on 422.
     */
    protected function supportPageResponse($validator = null, string $errorBag = 'default')
    {
        $request = request();

        if ($validator) {
            session()->now('_old_input', $request->except(['_token', 'attatchment', 'attachment']));
        }

        $page = $this->getPagebySlug();
        $airports = airport::all()->where('status', 'Yes');
        $this->ensureSupportDepartments();
        $departementslist = support_departments::orderBy('id')->get()->toArray();
        $departements_list = [];
        $departements_list[''] = 'Select Department';
        foreach ($departementslist as $u) {
            $departements_list[$u['id']] = $u['name'];
        }

        $view = view('frontend.customer_support', [
            'airports' => $airports,
            'departements_list' => $departements_list,
            'page' => $page,
        ]);

        if ($validator) {
            $view->withErrors($validator, $errorBag);
        }

        return response($view, $validator ? 422 : 200)
            ->header('Cache-Control', 'no-store, no-cache, private, max-age=0')
            ->header('Pragma', 'no-cache');
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
        return $this->supportPageResponse($validator, 'ticket_store');
    }



    try {

        DB::beginTransaction();

        $this->ensureTicketsSchema();

        $refInput = trim((string) $request->input('ref_no'));
        $emailInput = trim((string) $request->input('email'));

        $booking = airports_bookings::whereRaw('LOWER(referenceNo) = ?', [strtolower($refInput)])
            ->whereRaw('LOWER(email) = ?', [strtolower($emailInput)])
            ->first();



        if (!$booking) {

            $validator->getMessageBag()->add(
                'ref_no',
                'Invalid booking reference or email. Please use the details from your confirmation email.'
            );

            return $this->supportPageResponse($validator, 'ticket_store');

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

        $ticket->status = 'Open';

        if (function_exists('set_model_agent_id')) {
            set_model_agent_id($ticket, function_exists('current_site_agent_id') ? current_site_agent_id() : (string) (function_exists('current_agent_id') ? current_agent_id() : '9'));
        } else {
            $ticket->agent_id = function_exists('current_agent_id') ? (int) current_agent_id() : null;
        }

        $ticket->save();



        $ticketId = DB::getPdo()->lastInsertId();

        $ticketRef = 'TTST' . date('dmy') . $ticketId;

        $ticket->ticket_id = $ticketRef;

        $ticket->save();



        // Handle attachment (store absolute public URL, JetSeeker-style)

        $attachmentPath = null;

        if ($request->hasFile('attatchment')) {
            $file = $request->file('attatchment');
            $attachmentPath = $this->storeSupportAttachment($file);
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

        $link = route('view-ticket', ['id' => $encryptedTicketId]);



        $templateData = [

            'username' => $request->input('full_name'),

            'link' => $link,

            'subject' => $request->input('subject'),

            'urgency' => $request->input('priority'),

            'status' => 'Open',

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



        return hard_redirect(route('view-ticket', ['id' => $encryptedTicketId]));



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
            'message' => 'required|string',
            'attatchment' => 'nullable|file|mimes:jpg,jpeg,bmp,png,pdf,doc,docx|max:2000',
        ], $messages);

        if ($validatedData->fails()) {
            return redirect()->back()->withErrors($validatedData)->withInput();
        }

        $path = '';
        if ($request->hasFile('attatchment')) {
            $path = $this->storeSupportAttachment($request->file('attatchment'));
            if ($path === '') {
                return redirect()->back()
                    ->withErrors(['attatchment' => 'Attachment could not be uploaded. Please try again.'])
                    ->withInput();
            }
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

        $chat_data = ticket_chat::create($data);

        if ($chat_data) {
            $ticket = tickets::where('ticket_id', $request->input('ticket_ref'))->first();
            $tickref = Crypt::encrypt($request->input('ticket_ref'));
            $link = route('view-ticket', ['id' => $tickref]);
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

            return hard_redirect(route('view-ticket', ['id' => $tickref]));
        }

        return redirect()->back()->withErrors(['message' => 'Unable to submit reply. Please try again.'])->withInput();
    }







    



    /**
     * JetSeeker-style departments. Magr white-label DBs are often empty — seed defaults.
     */
    private function ensureSupportDepartments(): void
    {
        try {
            if (! Schema::hasTable('support_departments')) {
                Schema::create('support_departments', function ($table) {
                    $table->increments('id');
                    $table->string('name', 100)->nullable();
                    $table->string('email', 191)->nullable();
                });
            }

            if (! Schema::hasColumn('support_departments', 'name')) {
                Schema::table('support_departments', function ($table) {
                    $table->string('name', 100)->nullable();
                });
            }
            if (! Schema::hasColumn('support_departments', 'email')) {
                Schema::table('support_departments', function ($table) {
                    $table->string('email', 191)->nullable();
                });
            }

            if (support_departments::query()->count() > 0) {
                return;
            }

            $settings = function_exists('site_settings') ? site_settings() : [];
            $email = $settings['footer_email'] ?? (config('mail.from.address') ?: 'support@example.com');

            $defaults = [
                ['name' => 'Booking', 'email' => $email],
                ['name' => 'Complaint', 'email' => $email],
                ['name' => 'Amendment', 'email' => $email],
                ['name' => 'Cancellation', 'email' => $email],
            ];

            foreach ($defaults as $row) {
                support_departments::query()->create($row);
            }
        } catch (\Throwable $e) {
            Log::error('Unable to ensure support_departments', [
                'error' => $e->getMessage(),
            ]);
        }
    }


    /**
     * Magr / legacy DBs often miss ticket columns the front support flow needs.
     */
    private function ensureTicketsSchema(): void
    {
        try {
            if (! Schema::hasTable('tickets')) {
                return;
            }

            $columns = [
                'ticket_id' => fn ($table) => $table->string('ticket_id', 64)->nullable(),
                'agent_id' => fn ($table) => $table->integer('agent_id')->nullable(),
                'title' => fn ($table) => $table->text('title')->nullable(),
                'booking_ref' => fn ($table) => $table->string('booking_ref', 64)->nullable(),
                'user_id' => fn ($table) => $table->integer('user_id')->nullable(),
                'company_admin_id' => fn ($table) => $table->integer('company_admin_id')->nullable(),
                'name' => fn ($table) => $table->string('name', 255)->nullable(),
                'email' => fn ($table) => $table->string('email', 191)->nullable(),
                'contact' => fn ($table) => $table->string('contact', 32)->nullable(),
                'department' => fn ($table) => $table->integer('department')->nullable(),
                'urgency' => fn ($table) => $table->string('urgency', 20)->nullable(),
                'date' => fn ($table) => $table->dateTime('date')->nullable(),
                'assign_to' => fn ($table) => $table->integer('assign_to')->nullable(),
                'assign_date' => fn ($table) => $table->dateTime('assign_date')->nullable(),
                'status' => fn ($table) => $table->string('status', 20)->nullable(),
            ];

            foreach ($columns as $name => $definition) {
                if (! Schema::hasColumn('tickets', $name)) {
                    Schema::table('tickets', function ($table) use ($definition) {
                        $definition($table);
                    });
                }
            }

            // Widen legacy short ticket_id if present
            try {
                DB::statement('ALTER TABLE tickets MODIFY ticket_id VARCHAR(64) NULL');
            } catch (\Throwable $e) {
                // ignore if already correct / no ALTER privilege
            }
        } catch (\Throwable $e) {
            Log::error('Unable to ensure tickets schema', [
                'error' => $e->getMessage(),
            ]);
        }
    }



    /**
     * Store a ticket attachment and return its absolute public URL.
     * Example: https://www.totaltravelsolutions.co.uk/storage/app/public/supports/xxx.png
     */
    private function storeSupportAttachment($file): string
    {
        if (! $file || ! $file->isValid()) {
            return '';
        }

        $directory = storage_path('app/public/supports');
        if (! is_dir($directory)) {
            @mkdir($directory, 0755, true);
        }

        $storedPath = $file->store('public/supports');
        if (! is_string($storedPath) || $storedPath === '') {
            Log::error('Ticket attachment upload failed', [
                'original_name' => $file->getClientOriginalName(),
                'error' => method_exists($file, 'getErrorMessage') ? $file->getErrorMessage() : null,
            ]);

            return '';
        }

        return url('storage/app/' . ltrim($storedPath, '/'));
    }


    public function view($id)

    {
        $this->ensureTicketsSchema();




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
        $this->ensureTicketsSchema();

        $ticketRef = trim((string) ($request->input('ref_no') ?: $request->input('ticket_id')));
        $email = trim((string) $request->input('email'));

        $validator = Validator::make([
            'email' => $email,
            'ref_no' => $ticketRef,
        ], [
            'email' => 'required|string|email|max:255',
            'ref_no' => 'required|string',
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Enter a valid email address.',
            'ref_no.required' => 'Ticket or booking reference is required.',
        ]);

        if ($validator->fails()) {
            return $this->supportPageResponse($validator, 'search_ticket');
        }

        $emailLower = strtolower($email);
        $refLower = strtolower($ticketRef);

        $ticket = tickets::whereRaw('LOWER(ticket_id) = ?', [$refLower])
            ->whereRaw('LOWER(email) = ?', [$emailLower])
            ->orderByDesc('id')
            ->first();

        if (! $ticket) {
            $ticket = tickets::whereRaw('LOWER(booking_ref) = ?', [$refLower])
                ->whereRaw('LOWER(email) = ?', [$emailLower])
                ->orderByDesc('id')
                ->first();
        }

        if (! $ticket) {
            $refNorm = preg_replace('/\s+/', '', $refLower);
            $ticket = tickets::whereRaw('LOWER(email) = ?', [$emailLower])
                ->where(function ($q) use ($refNorm) {
                    $q->whereRaw("REPLACE(LOWER(IFNULL(ticket_id,'')), ' ', '') = ?", [$refNorm])
                        ->orWhereRaw("REPLACE(LOWER(IFNULL(booking_ref,'')), ' ', '') = ?", [$refNorm]);
                })
                ->orderByDesc('id')
                ->first();
        }

        if ($ticket) {
            $tickref = Crypt::encrypt($ticket->ticket_id);

            return hard_redirect(route('view-ticket', ['id' => $tickref]));
        }

        $validator->getMessageBag()->add(
            'ref_no',
            'No ticket matched that email and reference. Use your ticket ID or the booking reference from your confirmation email.'
        );

        return $this->supportPageResponse($validator, 'search_ticket');
    }

}
