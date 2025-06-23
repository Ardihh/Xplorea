<?php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table = 'events';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'title', 'description', 'location', 'start_datetime', 
        'end_datetime', 'image_url', 'max_attendees', 'is_active', 'created_by'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    // Get events with creator info
    public function getEventsWithCreator($limit = null, $offset = 0)
    {
        $builder = $this->select('events.*, users.username as creator_name, users.fullname as creator_fullname')
            ->join('users', 'users.id = events.created_by', 'left')
            ->where('events.is_active', true)
            ->orderBy('events.start_datetime', 'ASC');

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    // Get events by artist
    public function getEventsByArtist($artistId)
    {
        return $this->select('events.*, users.username as creator_name')
            ->join('users', 'users.id = events.created_by', 'left')
            ->where('events.created_by', $artistId)
            ->orderBy('events.start_datetime', 'ASC')
            ->findAll();
    }

    // Get upcoming events
    public function getUpcomingEvents($limit = 10)
    {
        return $this->select('events.*, users.username as creator_name')
            ->join('users', 'users.id = events.created_by', 'left')
            ->where('events.is_active', true)
            ->where('events.start_datetime >', date('Y-m-d H:i:s'))
            ->orderBy('events.start_datetime', 'ASC')
            ->limit($limit)
            ->findAll();
    }

    // Get event with full details
    public function getEventWithDetails($eventId)
    {
        return $this->select('events.*, users.username as creator_name, users.fullname as creator_fullname')
            ->join('users', 'users.id = events.created_by', 'left')
            ->where('events.id', $eventId)
            ->where('events.is_active', true)
            ->first();
    }
} 