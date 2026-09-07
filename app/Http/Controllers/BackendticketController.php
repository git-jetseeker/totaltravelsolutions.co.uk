<?php



namespace App\Http\Controllers;



use App\airport;

use App\Company;

use App\support_departments;

use App\ticket_chat;

use App\ticket_notes;
use App\users_roles;
use App\tickets;

use App\airports_bookings;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Input;

use \Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Crypt;





class BackendticketController extends Controller

{



    public function __construct()

    {

        $this->middleware('auth');

    }



    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function myticket(Request $request)

    {

        $role_nam = users_roles::get_user_role(Auth::user()->id)->name;
        set_time_limit(0);

        $tickets = new tickets();



        $admins = User::all();





//        $bookings = airports_bookings::orderBy('id', 'DESC')->paginate(20);

//        if ($request->has("name")) {

//            $name = $request->input("name");

//            $bookings = airports_bookings::where("referenceNo", $name)->paginate(20);

//        }



//       $tickets = $db->select("select t.*,ad.*,t.id as tID,d.name As department_name from ".$db->prefix."tickets t

//                  join ".$db->prefix."support_departments d ON t.department = d.id

//                  LEFT join ".$db->prefix."admin ad ON t.assign_to = ad.id

//                  where (t.assign_to = '".$_SESSION['admin_user_id']."' OR t.department IN (" . $admin_depart . ") )

//                  ORDER BY FIELD(t.urgency,'High','Medium','Low') , t.id DESC");



        $id = auth()->user()->id;

        $dep = auth()->user()->support_department;

        //dd($dep);

        $admin_depart = "";

        if ($dep != "") {

            $admin_depart = explode(",", $dep);

        }


        if ($role_nam == "airport_parking") {
        $tickets = $tickets->select(["tickets.*", "tickets.id as ticketid", "u.*", "tickets.id as tID", "tickets.name as raised_named", "d.name As department_name"]);
        $tickets = $tickets->leftJoin('support_departments as d', 'tickets.department', '=', 'd.id');
        $tickets = $tickets->leftJoin('users as u', 'tickets.company_admin_id', '=', 'u.id');
        $tickets = $tickets->where('u.id','=',$id);
        }else{
        $tickets = $tickets->select(["tickets.*", "tickets.id as ticketid", "u.*", "tickets.id as tID", "tickets.name as raised_named", "d.name As department_name"]);
        $tickets = $tickets->leftJoin('support_departments as d', 'tickets.department', '=', 'd.id');
        $tickets = $tickets->leftJoin('users as u', 'tickets.assign_to', '=', 'u.id');
        }
        
        
        

//        if ($request->has("search")) {

//            $name = $request->input("search");

//            if($name !="" && $name !="all" ) {

//                $tickets = $tickets->where("tickets.ticket_id", "'".$name."'");

//

//            }

//        }

//

//        if ($request->has("status")) {

//            $name = $request->input("status");

//            if($name !="" && $name !="all" ) {

//                $tickets = $tickets->where("tickets.status", "'".$name."'");

//            }

//        }





        if ($request->has("status")) {

            $name = $request->input("status");

            if ($name != "" && $name != "all") {

                $tickets = $tickets->where("tickets.status",(string) DB::raw($name));

            }

        }else{
			$tickets = $tickets->where("tickets.status",(string) DB::raw('Open'));
		}

        if ($request->has("search")) {

            $name = $request->input("search");

            if ($name != "" && $name != "all") {

                $tickets = $tickets->where("tickets.ticket_id",(string) DB::raw($name));



            }

        }




/*
        $tickets = $tickets->where(function ($tickets) use ($id, $admin_depart) {



            $tickets = $tickets->where("tickets.assign_to", "=", $id);



            if ($admin_depart != "") {

                $tickets->whereIn("tickets.department", $admin_depart, "OR");

            }

        });*/





        $tickets = $tickets->orderBy('tickets.id', 'DESC');





        $tickets = $tickets->paginate(20);





        return view("admin.myticket.list", ["tickets" => $tickets, "admins" => $admins]);






    }



    public function myticketview($id)

    {





        $ticket = tickets::where("id", $id)->orderBy('id', 'desc')->first();

        //dd($ticket);





        $department = support_departments::where("id", $ticket->department)->orderBy('id', 'desc')->first();

        $progress = ticket_chat::where("ticket_id", $ticket->id)->orderBy('id', 'desc')->first();





        ticket_chat::where("ticket_id", $ticket->id)->update(["adminunread" => "Yes"]);



        $companyMsg = "";

        if ($progress->reply_to == 'All') {

            if ($progress->clientunread == 'Yes') {

                $companyMsg = "Awaiting for Client Read";

            } elseif ($progress->Companyread == 'No') {

                $companyMsg = "Awaiting for Company Read";

            } else {

                $companyMsg = "Awaiting for Client and Company Response";

            }

        } elseif ($progress->reply_to != 'All') {

            if ($progress->reply_by == 'Client' && $progress->hold == 'Yes') {

                $companyMsg = "Awaiting for admin to show to Company";

            } else if ($progress->reply_by == 'Company' && $progress->hold == 'Yes') {

                $companyMsg = "Awaiting for Admin to show to Client";

            } elseif ($progress->reply_by == 'Admin' && $progress->reply_to == 'Company') {

                $companyMsg = "Awaiting for Company Reply";

            } elseif ($progress->reply_by == 'Admin' && $progress->reply_to == 'Client') {

                $companyMsg = "Awaiting for Client Reply";

            } else {

                $companyMsg = "Awaiting for Response";

            }

        }





        return view("admin.myticket.view", ["companyMsg" => $companyMsg, "progress" => $progress, "id" => $id, "model" => $ticket, "ticket" => $ticket, "department" => $department]);





    }



    public function updateTicketStatus($id)

    {



        //$id = $id;

        //$ticket = tickets::find

        $ticket = tickets::findOrFail($id);



        if ($ticket->status == "Open") {

            $ticket["status"] = "Closed";

        } else {

            $ticket["status"] = "Open";

        }



        if ($ticket->save()) {

            return redirect(route("myticket"))->with("success", "Ticket status updated Successfully");

        }

    }





    public function assignTicket(Request $request)

    {

        $tid = $request->input("tid");

        $aid = $request->input("aid");

        //$ticket = tickets::find

        $ticket = tickets::findOrFail($tid);

        $ticket["assign_to"] = $aid;

        $ticket["assign_date"] = date("Y-m-d h:i:s");



        if ($ticket->save()) {

            echo "<h3> Ticket Assign Successfully</h3>";

            //return redirect(["tickets.index"])->with("success", "Ticket Assign Successfully");

        }

    }



    public function addNote(Request $request)

    {

        $tid = $request->input("tid");

        $note = $request->input("note");

        //$ticket = tickets::find

        $admin_id = auth()->user()->id;

        $ticket = new ticket_notes();

        $ticket["note"] = $note;

        $ticket["ticket_id"] = $tid;

        $ticket["admin_id"] = $admin_id;



        if ($ticket->save()) {

            return redirect()->back();

            //return redirect(["tickets.index"])->with("success", "Ticket Assign Successfully");

        }



    }





    public function getNewMessages()

    {

        //set_time_limit(0);

        $tickets = new tickets();

        // $admins =  User::all();





        $id = auth()->user()->id;

        $dep = auth()->user()->support_department;

        //   dd($dep);

        $admin_depart = "";

        if ($dep != "") {

            $admin_depart = explode(",", $dep);

        }



        $tickets = $tickets->select(["c.adminunread", "c.reply_by", "c.message", "tickets.*", "tickets.id as ticketid", "u.*", "tickets.id as tID", "tickets.name as raised_named", "d.name As department_name"]);



        $tickets = $tickets->join('support_departments as d', 'tickets.department', '=', 'd.id');

        $tickets = $tickets->leftjoin('users as u', 'tickets.assign_to', '=', 'u.id');

        $tickets = $tickets->leftjoin('ticket_chats as c', 'tickets.id', '=', 'c.ticket_id');

        $tickets = $tickets->where("c.adminunread", "=", "No");

        $tickets = $tickets->where("c.reply_by", "=", "Client");





//        $tickets = $tickets->where("tickets.assign_to", "=", $id);

//

//        if ($admin_depart != "") {

//            $tickets = $tickets->whereIn("tickets.department", $admin_depart, "OR");

//        }





        $tickets = $tickets->where(function ($tickets) use ($id, $admin_depart) {



            $tickets = $tickets->where("tickets.assign_to", "=", $id);



            if ($admin_depart != "") {

                $tickets->whereIn("tickets.department", $admin_depart, "OR");

            }

        });



        $tickets = $tickets->orderBy('c.id', 'DESC')->get();





        return $tickets;

    }





}



?>