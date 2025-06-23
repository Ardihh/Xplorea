<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\EventTicketModel;
use App\Models\EventAttendeeModel;
use CodeIgniter\Controller;

class EventController extends Controller
{
    protected $eventModel;
    protected $ticketModel;
    protected $attendeeModel;
    protected $db;

    public function __construct()
    {
        $this->eventModel = new EventModel();
        $this->ticketModel = new EventTicketModel();
        $this->attendeeModel = new EventAttendeeModel();
        $this->db = \Config\Database::connect();
    }

    // Artist: List events they created
    public function myEvents()
    {
        $artistId = session()->get('user_id');
        $events = $this->eventModel->getEventsByArtist($artistId);

        return view('artist/events/index', [
            'events' => $events,
            'title' => 'My Events'
        ]);
    }

    // Artist: Create new event form
    public function create()
    {
        return view('artist/events/create', [
            'title' => 'Create New Event'
        ]);
    }

    // Artist: Store new event
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
            'max_attendees' => $this->request->getPost('max_attendees'),
            'image_url' => $imageName,
            'created_by' => session()->get('user_id')
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
                            'sale_end' => $saleEnds[$index] ?? $eventData['start_datetime']
                        ];
                        $this->ticketModel->save($ticketData);
                    }
                }
            }

            return redirect()->to('artist/events')->with('success', 'Event created successfully!');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create event');
    }

    // Artist: Edit event form
    public function edit($id)
    {
        $artistId = session()->get('user_id');
        $event = $this->eventModel->where(['id' => $id, 'created_by' => $artistId])->first();

        if (!$event) {
            return redirect()->to('artist/events')->with('error', 'Event not found');
        }

        $tickets = $this->ticketModel->where('event_id', $id)->findAll();

        return view('artist/events/edit', [
            'event' => $event,
            'tickets' => $tickets,
            'title' => 'Edit Event'
        ]);
    }

    // Artist: Update event
    public function update($id)
    {
        // Add debugging
        log_message('info', 'Attempting to update event ID: ' . $id);
        log_message('info', 'POST data: ' . json_encode($this->request->getPost()));
        
        $artistId = session()->get('user_id');
        log_message('info', 'Artist ID: ' . $artistId);
        
        $event = $this->eventModel->where(['id' => $id, 'created_by' => $artistId])->first();
        
        if (!$event) {
            log_message('error', 'Event not found or not owned by artist');
            return redirect()->to('artist/events')->with('error', 'Event not found');
        }
        
        log_message('info', 'Found event: ' . json_encode($event));

        $rules = [
            'title' => 'required|min_length[5]|max_length[255]',
            'description' => 'required|min_length[10]',
            'location' => 'required|max_length[255]',
            'start_datetime' => 'required|valid_date',
            'end_datetime' => 'required|valid_date',
            'max_attendees' => 'permit_empty|integer|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            log_message('error', 'Validation failed: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $eventData = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'location' => $this->request->getPost('location'),
            'start_datetime' => $this->request->getPost('start_datetime'),
            'end_datetime' => $this->request->getPost('end_datetime'),
            'max_attendees' => $this->request->getPost('max_attendees') ?: null
        ];

        log_message('info', 'Event data to update: ' . json_encode($eventData));

        $imageFile = $this->request->getFile('image_url');
        if ($imageFile && $imageFile->isValid()) {
            $imageName = $imageFile->getRandomName();
            $imageFile->move(ROOTPATH . 'public/uploads/events', $imageName);
            $eventData['image_url'] = $imageName;
            log_message('info', 'New image uploaded: ' . $imageName);
        }

        try {
            $updateResult = $this->eventModel->update($id, $eventData);
            log_message('info', 'Update result: ' . ($updateResult ? 'success' : 'failed'));
            
            if ($updateResult) {
                log_message('info', 'Event updated successfully');
                return redirect()->to('artist/events')->with('success', 'Event updated successfully!');
            } else {
                log_message('error', 'Failed to update event. Model errors: ' . json_encode($this->eventModel->errors()));
                return redirect()->back()->withInput()->with('error', 'Failed to update event');
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception in update: ' . $e->getMessage());
            log_message('error', 'Exception trace: ' . $e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'Failed to update event: ' . $e->getMessage());
        }
    }

    // Artist: Delete event
    public function delete($id)
    {
        $artistId = session()->get('user_id');
        $event = $this->eventModel->where(['id' => $id, 'created_by' => $artistId])->first();

        if (!$event) {
            return redirect()->to('artist/events')->with('error', 'Event not found');
        }

        // Check if event has attendees
        $attendees = $this->attendeeModel->where('event_id', $id)->countAllResults();
        if ($attendees > 0) {
            return redirect()->to('artist/events')->with('error', 'Cannot delete event with existing attendees');
        }

        if ($this->eventModel->delete($id)) {
            return redirect()->to('artist/events')->with('success', 'Event deleted successfully!');
        }

        return redirect()->to('artist/events')->with('error', 'Failed to delete event');
    }

    // Artist: View event attendees
    public function attendees($eventId)
    {
        $builder = $this->db->table('event_attendees');
        $builder->select('event_attendees.*, users.fullname, users.email, event_tickets.price, event_tickets.ticket_type');
        $builder->join('users', 'users.id = event_attendees.user_id');
        $builder->join('event_tickets', 'event_tickets.id = event_attendees.ticket_id');
        $builder->where('event_attendees.event_id', $eventId);
        $attendees = $builder->get()->getResultArray();

        $event = $this->eventModel->find($eventId);

        return view('artist/events/attendees', [
            'attendees' => $attendees,
            'event' => $event
        ]);
    }
} 