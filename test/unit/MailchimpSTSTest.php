<?php
/**
 * MailchimpSTSTest
 **/
class MailchimpSTSTest extends CTestCase
{
    private $_testEmail='test@email.com';

    /**
     * No api key exception thrown test
     */
    public function testNoApiKey()
    {
        $this->setExpectedException('MailchimpSTSException');
        $this->cInit(array());
    }
    /** 
     * Miss formed api key exception thrown
     */
    public function testMissformedApiKey()
    {
        $this->setExpectedException('MailchimpSTSException');
        $this->cInit(array('apiKey' => 'myApiKey'));
    }
    /**
     * Valid api key
     */
    public function testValidApiKey()
    {
        $this->cInit(array('apiKey' => 'myApiKey-us1'));
    }

    public function testValidOutputFormat()
    {
        $mail=$this->cInit(array('apiKey' => 'myApiKey-us1'));
        foreach($mail->optsOutputFormat() as $format)
        {
            $mail->outputFormat = $format;
        }
    }

    public function testInvalidOutputFormat()
    {
        $this->setExpectedException('MailchimpSTSException');
        $mail=$this->cInit(array('apiKey' => 'myApiKey-us1'));
        $mail->outputFormat = 'invalid output format';
    }

    public function testSubmitUrl()
    {
        $mail=$this->cInit(array('apiKey' => 'myApiKey-us1'));
        $this->assertEquals('https://us1.sts.mailchimp.com/1.0/json/', $mail->submitUrl);

        $mail->outputFormat = 'xml';
        $this->assertEquals('https://us1.sts.mailchimp.com/1.0/xml/', $mail->submitUrl);

        $mail->ssl = false;
        $mail->outputFormat = 'json';
        $this->assertEquals('http://us1.sts.mailchimp.com/1.0/json/', $mail->submitUrl);
    }

    /**
     * Email verifications
     */
    public function testEmailVerification()
    {
        $mail=$this->mailchimpSTS();
        //Delete the test email if it is already there
        $mail->delete($this->_testEmail);

        $verifiedEmail=$mail->list();

        $mail->add($this->_testEmail);
        $newVerifiedList=array_merge($verifiedEmail,array($this->_testEmail));

        $this->assertEquals($newVerifiedList, $mail->list());

        $mail->delete($this->_testEmail);
        $this->assertEquals($verified, $mail->list());
    }
    /**
     * Mailchimp statistics
     */
    public function testMCstats()
    {
        $mail=$this->mailchimpSTS();
        $mail->bounces;
        //TODO: validate format
        $mail->getBounces(date('Y-m-d H:i:s', time()));

        $tags=$mail->tags;

        $mail->sendStats;
        foreach($tags as $id=>$tag)
        {
            $mail->getSendStats($id);
        }
        //TODO: validate format
        $mail->getSendStats(null, date('YYYY-MM-DD HH',time());

        foreach($mail->urls as $uid=>$url)
        {
            $mail->getUrlStats($uid);
        }
        $mail->getUrlStats(null, date('YYYY-MM-DD HH',time()));

    }

    /**
     * Send stats
     */
    public function testSendStats()
    {
        $mail=$this->mailchimpSTS();
        $mail->sendQuota;
        $mail->sendStatistics;
    }

    /**
     * send email
     */
    public function testEmail()
    {
        $mail=$this->mailchimpSTS();

        $email = new MailchimpEmail();
        $email->body="the full HTML content to be sent";
        $email->to  = array('Test email name'=>$this->_testEmail);
        $email->subject = 'Testing mailchimp';
        $email->trackOpens  = true;
        $email->trackClicks = true;
        $email->tags = $mail->tags;

        $mail->send($email);
    }

    /** 
     * Initialize a MailchimpSTS component
     * @param array $config component configuration
     * @return MailchimpSTS
     */
    private function cInit($config)
    {
        $c=Yii::createComponent(array_merge($config,array(
            'class'  => 'MailchimpSTS'
        )));
        $c->init();
        return $c;
    }

    private function mailchimpSTS()
    {
        return Yii::app()->mailchimpSTS;
    }
}
