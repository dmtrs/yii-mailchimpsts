<?php
/**
 * MailchimpSTS
 **/
class MailchimpSTS extends CApplicationComponent
{
    public $apiKey;

    public function init()
    {
        throw new MailchimpSTSException('test');
    }
}
/**
 * MailchimpSTSException
 */
class MailchimpSTSException extends CException
{
}
