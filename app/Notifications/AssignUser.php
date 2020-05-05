<?php

namespace App\Notifications;

use App\Employer;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssignUser extends Notification
{
    use Queueable;
    public $user;
    public $employer;
    /**
     * Create a new notification instance.
     *
     * @param Employer $employer
     * @param User $user
     */
    public function __construct(Employer $employer, User $user)
    {
        $this->employer = $employer;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'employer_id' => $this->employer->id,
            'employer_name' => $this->employer->name,
            //'message' => $this->user->name . ' delegate employer : ' . $this->employer->id . ' | ' . $this->employer->name . ' to you.'
			'message' => $this->user->name . ' has delegated Employer : ' . $this->employer->name . ' to you.'
		];
    }
}
