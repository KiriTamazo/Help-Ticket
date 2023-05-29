<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\User;
use App\Notifications\TicketNoti;
use Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $tickets =$user->isAdmin ? Ticket::latest()->get() : $user->tickets;
        return view('ticket.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ticket.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        $ticket = Ticket::create([
            'user_id'=>auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
         ]);

        $file = $request->file('attachment');
        if($file) {
            $this->storeAttachment($file, $ticket);
        }
        return redirect(route('ticket.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {

        return view("ticket.show", compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        return view("ticket.edit", compact('ticket'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        // update all field except attachment
        $ticket->update($request->except('attachment'));
        // status -> resolved -> rejected -> open depend on admin permissions
        if($request->has('status')) {
            // $user = User::find($ticket->user_id);
            $ticket->user->notify(new TicketNoti($ticket));
        }

        $file = $request->file('attachment');

        if($file) {
            Storage::disk('public')->delete($ticket->attachment);
            $this->storeAttachment($file, $ticket);
        }
        return redirect(route('ticket.index'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {

        $ticket->delete();
        return redirect()->route('ticket.index');
    }

    protected function storeAttachment($request, $ticket)
    {
        $content= file_get_contents($request);
        $filename = Str::random(25);
        $ext = $request->extension();
        $path = "attachment/$filename.$ext";
        Storage::disk('public')->put($path, $content);
        $ticket->update(['attachment' => $path]);
    }
}
