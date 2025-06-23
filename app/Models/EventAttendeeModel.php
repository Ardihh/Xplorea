<?php

namespace App\Models;

use CodeIgniter\Model;

class EventAttendeeModel extends Model
{
    protected $table = 'event_attendees';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'event_id', 'user_id', 'ticket_id', 'quantity', 
        'payment_status', 'attended'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    // Get attendees for an event
    public function getAttendeesByEvent($eventId)
    {
        return $this->select('event_attendees.*, users.username, users.fullname, event_tickets.ticket_type')
            ->join('users', 'users.id = event_attendees.user_id', 'left')
            ->join('event_tickets', 'event_tickets.id = event_attendees.ticket_id', 'left')
            ->where('event_attendees.event_id', $eventId)
            ->findAll();
    }

    // Get user's event bookings
    public function getUserBookings($userId)
    {
        return $this->select('event_attendees.*, events.title, events.start_datetime, events.location, event_tickets.ticket_type, event_tickets.price')
            ->join('events', 'events.id = event_attendees.event_id', 'left')
            ->join('event_tickets', 'event_tickets.id = event_attendees.ticket_id', 'left')
            ->where('event_attendees.user_id', $userId)
            ->orderBy('events.start_datetime', 'ASC')
            ->findAll();
    }

    // Check if user already booked this event
    public function checkUserBooking($eventId, $userId, $ticketId)
    {
        return $this->where('event_id', $eventId)
            ->where('user_id', $userId)
            ->where('ticket_id', $ticketId)
            ->first();
    }
} 