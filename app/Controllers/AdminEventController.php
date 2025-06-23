<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\EventTicketModel;
use App\Models\EventAttendeeModel;

class AdminEventController extends BaseController
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

    // Admin: List all events
    public function index()
    {
        $events = $this->eventModel->getEventsWithCreator();

        return view('admin/events/index', [
            'events' => $events,
            'title' => 'Manage Events'
        ]);
    }

    // Admin: View event details
    public function show($id)
    {
        $event = $this->eventModel->getEventWithDetails($id);
        if (!$event) {
            return redirect()->to('admin/events')->with('error', 'Event not found');
        }

        $tickets = $this->ticketModel->where('event_id', $id)->findAll();
        $attendees = $this->attendeeModel->getAttendeesByEvent($id);

        return view('admin/events/show', [
            'event' => $event,
            'tickets' => $tickets,
            'attendees' => $attendees,
            'title' => 'Event Details'
        ]);
    }

    // Admin: Toggle event status
    public function toggleStatus($id)
    {
        $event = $this->eventModel->find($id);
        if (!$event) {
            return redirect()->to('admin/events')->with('error', 'Event not found');
        }

        $newStatus = $event['is_active'] ? false : true;
        $this->eventModel->update($id, ['is_active' => $newStatus]);

        $statusText = $newStatus ? 'activated' : 'deactivated';
        return redirect()->to('admin/events')->with('success', "Event {$statusText} successfully!");
    }

    // Admin: Delete event
    public function delete($id)
    {
        $event = $this->eventModel->find($id);
        if (!$event) {
            return redirect()->to('admin/events')->with('error', 'Event not found');
        }

        // Check if event has attendees
        $attendees = $this->attendeeModel->where('event_id', $id)->countAllResults();
        if ($attendees > 0) {
            return redirect()->to('admin/events')->with('error', 'Cannot delete event with existing attendees');
        }

        if ($this->eventModel->delete($id)) {
            return redirect()->to('admin/events')->with('success', 'Event deleted successfully!');
        }

        return redirect()->to('admin/events')->with('error', 'Failed to delete event');
    }

    // Admin: Create new event form
    public function create()
    {
        return view('admin/events/create', [
            'title' => 'Create New Event'
        ]);
    }

    // Admin: Store new event
    public function store()
    {
        $rules = [
            'title' => 'required|min_length[5]|max_length[255]',
            'description' => 'required|min_length[10]',
            'location' => 'required|max_length[255]',
            'start_datetime' => 'required|valid_date',
            'end_datetime' => 'required|valid_date',
            'max_attendees' => 'permit_empty|integer|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $imageFile = $this->request->getFile('image_url');
        $imageName = null;

        if ($imageFile && $imageFile->isValid()) {
            $imageName = $imageFile->getRandomName();
            $imageFile->move(ROOTPATH . 'public/uploads/events', $imageName);
        }

        $eventData = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'location' => $this->request->getPost('location'),
            'start_datetime' => $this->request->getPost('start_datetime'),
            'end_datetime' => $this->request->getPost('end_datetime'),
            'max_attendees' => $this->request->getPost('max_attendees') ?: null,
            'image_url' => $imageName,
            'created_by' => session()->get('user_id'), // admin id
            'is_active' => true // Admin events are active by default
        ];

        if ($this->eventModel->save($eventData)) {
            $eventId = $this->eventModel->getInsertID();

            // Handle ticket types
            $ticketTypes = $this->request->getPost('ticket_types');
            $ticketPrices = $this->request->getPost('ticket_prices');
            $ticketQuantities = $this->request->getPost('ticket_quantities');
            $saleStarts = $this->request->getPost('sale_starts');
            $saleEnds = $this->request->getPost('sale_ends');

            if ($ticketTypes) {
                foreach ($ticketTypes as $index => $type) {
                    if (!empty($type)) {
                        $ticketData = [
                            'event_id' => $eventId,
                            'ticket_type' => $type,
                            'price' => $ticketPrices[$index] ?? 0,
                            'quantity_available' => $ticketQuantities[$index] ?? 0,
                            'sale_start' => $saleStarts[$index] ?? date('Y-m-d H:i:s'),
                            'sale_end' => $saleEnds[$index] ?? $eventData['start_datetime'],
                            'is_active' => true
                        ];
                        $this->ticketModel->save($ticketData);
                    }
                }
            }

            return redirect()->to('admin/events')->with('success', 'Event created successfully!');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create event');
    }
} 