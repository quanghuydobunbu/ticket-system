<?php

namespace App\Services;

use App\Models\Event;
use App\Repositories\EventRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EventService{
    protected $eventRepository;
    public function __construct(EventRepositoryInterface $eventRepository){
        $this->eventRepository = $eventRepository;
    }
    public function getAllEvent(){
        $events = $this->eventRepository->getAllEvent();
        return $events;
    }

    public function createEvent(array $data)
    {
        try {
            DB::beginTransaction();
            
            $eventData = [
                'organizer_id' => $data['organizer_id'],
                'category_id' => $data['category_id'],
                'venue_id' => $data['venue_id'] ?? null,
                'title' => $data['title'],
                'slug' => $data['slug'],
                'description' => $data['description'] ?? null,
                'start_datetime' => $data['start_datetime'],
                'end_datetime' => $data['end_datetime'],
                'registration_end' => $data['registration_end'] ?? null,
                'max_attendees' => $data['max_attendees'] ?? null,
                'status' => $data['status'],
            ];

            if (isset($data['is_featured'])) {
                $eventData['is_featured'] = ($data['is_featured'] == 1) ? 1 : 0;
            }

            if (isset($data['is_free'])) {
                $eventData['is_free'] = ($data['is_free'] == 1) ? 1 : 0;
            }

            if (isset($data['featured_image']) && $data['featured_image'] instanceof UploadedFile) {
                $uploadedFileName = $this->uploadFeaturedImage($data['featured_image']);
                $eventData['featured_image'] = $uploadedFileName;
            }
            $event = $this->eventRepository->create($eventData);
            DB::commit();
            return $event;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Failed to create event: ' . $e->getMessage());
        }
    }

    public function updateEvent(Event $event, array $data){
        $oldAvatarFileName = $event->featured_image;
        $newAvatarFileName = null;

        try {
            $updateData = [
                'organizer_id' => $data['organizer_id'],
                'category_id' => $data['category_id'],
                'venue_id' => $data['venue_id'] ?? null,
                'title' => $data['title'],
                'slug' => $data['slug'],
                'description' => $data['description'] ?? null,
                'start_datetime' => $data['start_datetime'],
                'end_datetime' => $data['end_datetime'],
                'registration_end' => $data['registration_end'] ?? null,
                'max_attendees' => $data['max_attendees'] ?? null,
                'status' => $data['status'],
            ];

            if (isset($data['is_featured'])) {
                $updateData['is_featured'] = ($data['is_featured'] == 1) ? 1 : 0;
            }

            if (isset($data['is_free'])) {
                $updateData['is_free'] = ($data['is_free'] == 1) ? 1 : 0;
            }

            if (isset($data['featured_image']) && $data['featured_image'] instanceof UploadedFile) {
                $newAvatarFileName = $this->uploadFeaturedImage($data['featured_image']);
                $updateData['featured_image'] = $newAvatarFileName;
            }

            $updateEvent = $this->eventRepository->update($event, $updateData);

            if ($newAvatarFileName && $oldAvatarFileName) {
                $this->deleteAvatar($oldAvatarFileName);
            }
            return $updateEvent;

        } catch (\Exception $e) {
            if ($newAvatarFileName) {
                $this->deleteAvatar($newAvatarFileName);
            }
            throw new \Exception('Không thể cập nhật sự kiện: ' . $e->getMessage());
        }
    }

    public function deleteEvent($id){
        $event = Event::findOrFail($id);
        return $this->eventRepository->delete($event);
    }

    public function getAllCategory(){
        return $this->eventRepository->getAllCategory();
    }

    public function getAllOrganizer(){
        return $this->eventRepository->getAllOrganizer();
    }

    private function uploadFeaturedImage(UploadedFile $imageFile): string
    {
        try {
            $fileName = time() . '_' . uniqid() . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->storeAs('events', $fileName, 'public');
            return $fileName;
            
        } catch (\Exception $e) {
            throw new \Exception('Cannot upload image: ' . $e->getMessage());
        }
    }

    public function findEvent($id){
        return $this->eventRepository->findById($id);
    }

    private function deleteAvatar(?string $avatarFileName): void
    {
        if ($avatarFileName && Storage::disk('public')->exists('events/' . $avatarFileName)) {
            Storage::disk('public')->delete('events/' . $avatarFileName);
        }
    }

    public function getEventsWithFilters(array $filters, int $perPage = 6)
    {
        return $this->eventRepository->getAllWithFilters($filters, $perPage);
    }
}