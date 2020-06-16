<?php

namespace denis909\yii;

use Exception;
use yii\helpers\ArrayHelper;
use yii\mail\MessageInterface;

class MailHelper
{

    public static function hasRecipient(MessageInterface $message, $toEmail = null, $toName = null)
    {
        if (!$toEmail && !$toName)
        {
            return true;
        }

        foreach($message->getTo() as $email => $name)
        {
            $valid = true;

            if ($toEmail && ($toEmail != $email))
            {
                $valid = false;
            }

            if ($toName && ($toName != $name))
            {
                $valid = false;
            }

            if ($valid)
            {
                return true;
            }
        }

        return false;
    }

    public static function isMessageSent(array $messages, array $attributes = [])
    {
        foreach($messages as $message)
        {
            if (!($message instanceof MessageInterface))
            {
                throw new Exception('Message is not an instance of ' . MessageInterface::class);
            }

            if ($attributes)
            {
                $subject = ArrayHelper::getValue($attributes, 'subject');

                if ($subject && ($subject != $message->getSubject()))
                {
                    continue;
                }

                $toEmail = ArrayHelper::getValue($attributes, 'toEmail');

                $toName = ArrayHelper::getValue($attributes, 'toName');

                if (!static::hasRecipient($message, $toEmail, $toName))
                {
                    continue;
                }

                return true;
            }
            else
            {
                return true;
            }
        }

        return false;
    }

}