<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\EventTicketModel;
use App\Models\EventAttendeeModel;

class PublicEventController extends BaseController
{
    protected $eventModel;
    protected $ticketModel;
    protected $attendeeModel;

    public function __construct()
    {
        $this->eventModel = new EventModel();
        $this->ticketModel = new EventTicketModel();
        $this->attendeeModel = new EventAttendeeModel();
    }

    // Public: List all events
    public function index()
    {
        $eventModel = new \App\Models\EventModel();
        $recentEvents = $eventModel
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-7 days')))
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $events = $this->eventModel->getUpcomingEvents(20);

        return view('xplorea/events', [
            'recentEvents' => $recentEvents,
            'events' => $events,
            'title' => 'Events'
        ]);
    }

    // Public: List all events (halaman terpisah)
    public function events()
    {
        $events = $this->eventModel->getUpcomingEvents(20);

        return view('xplorea/events', [
            'events' => $events,
            'title' => 'Events'
        ]);
    }

    // Public: View event details
    public function show($id)
    {
        $event = $this->eventModel->getEventWithDetails($id);
        if (!$event) {
            return redirect()->to('events')->with('error', 'Event not found');
        }

        $tickets = $this->ticketModel->getTicketsByEvent($id);

        return view('xplorea/event_detail', [
            'event' => $event,
            'tickets' => $tickets,
            'title' => $event['title']
        ]);
    }

    // User: Book event ticket
    public function book($eventId)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('xplorea/login')->with('error', 'Please login to book tickets');
        }

        $event = $this->eventModel->getEventWithDetails($eventId);
        if (!$event) {
            return redirect()->to('events')->with('error', 'Event not found');
        }

        $ticketId = $this->request->getPost('ticket_id');
        $quantity = (int) $this->request->getPost('quantity') ?: 1;
        $userId = session()->get('user_id');

        // Validate ticket availability
        if (!$this->ticketModel->checkTicketAvailability($ticketId, $quantity)) {
            return redirect()->back()->with('error', 'Tickets not available');
        }

        // Check if user already booked this ticket type
        $existingBooking = $this->attendeeModel->checkUserBooking($eventId, $userId, $ticketId);
        if ($existingBooking) {
            return redirect()->back()->with('error', 'You have already booked this ticket type');
        }

        // Create booking
        $bookingData = [
            'event_id' => $eventId,
            'user_id' => $userId,
            'ticket_id' => $ticketId,
            'quantity' => $quantity,
            'payment_status' => 'pending'
        ];

        if ($this->attendeeModel->save($bookingData)) {
            // Update ticket quantity
            $this->ticketModel->updateTicketQuantity($ticketId, $quantity);
            
            return redirect()->to('my-bookings')->with('success', 'Ticket booked successfully! Please complete payment.');
        }

        return redirect()->back()->with('error', 'Failed to book ticket');
    }

    // User: View their bookings
    public function myBookings()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('xplorea/login');
        }

        $userId = session()->get('user_id');
        $bookings = $this->attendeeModel->getUserBookings($userId);

        return view('xplorea/my_bookings', [
            'bookings' => $bookings,
            'title' => 'My Bookings'
        ]);
    }
} 