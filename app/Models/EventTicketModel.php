<?php

namespace App\Models;

use CodeIgniter\Model;

class EventTicketModel extends Model
{
    protected $table = 'event_tickets';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'event_id', 'ticket_type', 'price', 'quantity_available',
        'sale_start', 'sale_end', 'is_active'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    // Get tickets for an event
    public function getTicketsByEvent($eventId)
    {
        return $this->where('event_id', $eventId)
            ->where('is_active', true)
            ->where('sale_start <=', date('Y-m-d H:i:s'))
            ->where('sale_end >=', date('Y-m-d H:i:s'))
            ->findAll();
    }

    // Check ticket availability
    public function checkTicketAvailability($ticketId, $quantity = 1)
    {
        $ticket = $this->find($ticketId);
        if (!$ticket || !$ticket['is_active']) {
            return false;
        }

        // Check if sale period is valid
        $now = date('Y-m-d H:i:s');
        if ($ticket['sale_start'] > $now || $ticket['sale_end'] < $now) {
            return false;
        }

        // Check quantity available
        return $ticket['quantity_available'] >= $quantity;
    }

    // Update ticket quantity after purchase
    public function updateTicketQuantity($ticketId, $quantity)
    {
        return $this->set('quantity_available', "quantity_available - {$quantity}", false)
            ->where('id', $ticketId)
            ->update();
    }
} 