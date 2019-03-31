<?php
namespace Lima\Newsletter\Block;

use \Magento\Framework\View\Element\Template\Context;

class Popup extends \Magento\Framework\View\Element\Template
{
	
	protected $_scopeConfig;

	public function __construct(
		Context $context
	) {
		$this->_scopeConfig = $context->getScopeConfig();
		parent::__construct($context);
	}

	public function getFormActionUrl()
	{
		return $this->getUrl('newsletter/subscriber/new', ['_secure' => true]);
	}

	public function isEnabled()
	{
		$secounds = $this->getConfig('status');
		return $secounds;
	}

	public function getTimeToShow()
	{
		$defaultValue = 3;
		$secounds = $this->getConfig('time_to_show') ? $this->getConfig('time_to_show') : $defaultValue;
		return $secounds;
	}

	public function getText()
	{
		$defaultValue = "Subscribe to our newsletters now and stay up-to-date with new products and exclusive offers";
		$secounds = $this->getConfig('text') ? $this->getConfig('text') : $defaultValue;
		return $secounds;
	}

	protected function getConfig($config)
	{
		$value = $this->_scopeConfig->getValue(
			'lima_newsletter/general/' . $config, 
			\Magento\Store\Model\ScopeInterface::SCOPE_STORE
		);

		return $value;
	}

}
