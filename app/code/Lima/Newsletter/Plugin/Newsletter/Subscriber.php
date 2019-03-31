<?php

namespace Lima\Newsletter\Plugin\Newsletter;

use Magento\Framework\App\Request\Http;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Session\SessionManagerInterface;

class Subscriber {

	protected $_request;
	protected $_cookieManager;
	protected $_cookieMetadataFactory;
	protected $_sessionManager;

	public function __construct(
		Http $request,
		SubscriberFactory $subscriberFactory,
		CookieManagerInterface $cookieManager,
		CookieMetadataFactory $cookieMetadataFactory,
		SessionManagerInterface $sessionManager
	) {
		$this->_request = $request;
		$this->_cookieManager = $cookieManager;
		$this->_cookieMetadataFactory = $cookieMetadataFactory;
		$this->_sessionManager = $sessionManager;
	}

	public function aroundSubscribe($subject, \Closure $proceed, $email) 
	{
		if (
			$this->_request->isPost() && 
			$this->_request->getPost('email') && 
			$this->_request->getPost('mobile_number')
		) { 

            $mobile_number = $this->_request->getPost('mobile_number');

            $subject->setMobileNumber($mobile_number);
            $result = $proceed($email);

            try {
                $save = $subject->save();
                if($save){
                	$this->stopPopup();
                }
            }catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        }
        return $result;
	}

	public function stopPopup()
	{
		$metadata = $this->_cookieMetadataFactory
			->createPublicCookieMetadata()
			->setDuration(86400*6000)
			->setPath($this->_sessionManager->getCookiePath())
			->setDomain($this->_sessionManager->getCookieDomain());

		$this->_cookieManager->setPublicCookie(
			'lima_newsletter_popup',
			'true',
			$metadata
		);
	}
}
