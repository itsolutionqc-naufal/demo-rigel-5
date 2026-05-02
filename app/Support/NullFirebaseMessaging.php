<?php

namespace App\Support;

use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\AppInstance;
use Kreait\Firebase\Messaging\Message;
use Kreait\Firebase\Messaging\Messages;
use Kreait\Firebase\Messaging\MulticastSendReport;
use Kreait\Firebase\Messaging\RegistrationToken;
use Kreait\Firebase\Messaging\RegistrationTokens;
use Kreait\Firebase\Messaging\Topic;

class NullFirebaseMessaging implements Messaging
{
    public function __construct(private readonly string $reason = 'FCM is not configured')
    {
    }

    private function fail(): never
    {
        throw new \RuntimeException('FCM disabled: '.$this->reason);
    }

    public function send(Message|array $message, bool $validateOnly = false): array
    {
        $this->fail();
    }

    public function sendMulticast(
        Message|array $message,
        RegistrationTokens|RegistrationToken|array|string $registrationTokens,
        bool $validateOnly = false
    ): MulticastSendReport {
        $this->fail();
    }

    public function sendAll(array|Messages $messages, bool $validateOnly = false): MulticastSendReport
    {
        $this->fail();
    }

    public function validate(Message|array $message): array
    {
        $this->fail();
    }

    public function validateRegistrationTokens(
        RegistrationTokens|RegistrationToken|array|string $registrationTokenOrTokens
    ): array {
        $this->fail();
    }

    public function subscribeToTopic(string|Topic $topic, RegistrationTokens|RegistrationToken|array|string $registrationTokenOrTokens): array
    {
        $this->fail();
    }

    public function subscribeToTopics(iterable $topics, RegistrationTokens|RegistrationToken|array|string $registrationTokenOrTokens): array
    {
        $this->fail();
    }

    public function unsubscribeFromTopic(string|Topic $topic, RegistrationTokens|RegistrationToken|array|string $registrationTokenOrTokens): array
    {
        $this->fail();
    }

    public function unsubscribeFromTopics(array $topics, RegistrationTokens|RegistrationToken|array|string $registrationTokenOrTokens): array
    {
        $this->fail();
    }

    public function unsubscribeFromAllTopics(RegistrationTokens|RegistrationToken|array|string $registrationTokenOrTokens): array
    {
        $this->fail();
    }

    public function getAppInstance(RegistrationToken|string $registrationToken): AppInstance
    {
        $this->fail();
    }
}

