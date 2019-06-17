<?php
/**
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class RoxCustomerSeller extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'RoxCustomerSeller';
        $this->tab = 'pricing_promotion';
        $this->version = '1.0.0';
        $this->author = 'ROXAR Web Solution & Experience of Design';
        $this->need_instance = 1;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Roxar :: Customers Partner');
        $this->description = $this->l('Module for management custoemrs with sellers');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('ROXCUSTOMERSELLER_ACTIVE_MODE', false);
        Configuration::updateValue('ROXCUSTOMERSELLER_CUSTOMERS_MODE', false);
        Configuration::updateValue('ROXCUSTOMERSELLER_POINT_COEFICIENT', false);
        Configuration::updateValue('ROXCUSTOMERSELLER_STATUS_INCREASE_POINTS', false);
        Configuration::updateValue('ROXCUSTOMERSELLER_STATUS_DECREASE_POINTS', false);

        include(dirname(__FILE__).'/sql/install.php');

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('actionCartSave') &&
            $this->registerHook('actionOrderSlipAdd') &&
            $this->registerHook('actionOrderStatusUpdate') &&
            $this->registerHook('displayAdminCustomers');
    }

    public function uninstall()
    {
        Configuration::deleteByName('ROXCUSTOMERSELLER_ACTIVE_MODE');
        Configuration::deleteByName('ROXCUSTOMERSELLER_CUSTOMERS_MODE');
        Configuration::deleteByName('ROXCUSTOMERSELLER_POINT_COEFICIENT');
        Configuration::deleteByName('ROXCUSTOMERSELLER_STATUS_INCREASE_POINTS');
        Configuration::deleteByName('ROXCUSTOMERSELLER_STATUS_DECREASE_POINTS');

        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall()  &&
            $this->unregisterHook('header') &&
            $this->unregisterHook('backOfficeHeader') &&
            $this->unregisterHook('actionCartSave') &&
            $this->unregisterHook('actionOrderSlipAdd') &&
            $this->unregisterHook('actionOrderStatusUpdate') &&
            $this->unregisterHook('displayAdminCustomers');
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitRoxCustomerSellerModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitRoxCustomerSellerModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Benefits active'),
                        'name' => 'ROXCUSTOMERSELLER_ACTIVE_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use this module in sellers cart'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Benefits active for all customers'),
                        'name' => 'ROXCUSTOMERSELLER_CUSTOMER_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use this module in customers cart'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            ),
                        ),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Enter a valid email address'),
                        'name' => 'ROXCUSTOMERSELLER_ACCOUNT_EMAIL',
                        'label' => $this->l('Email'),
                    ),
                    array(
                        'type' => 'password',
                        'name' => 'ROXCUSTOMERSELLER_ACCOUNT_PASSWORD',
                        'label' => $this->l('Password'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-compress"></i>',
                        'desc' => $this->l('Enter a valid coeficient'),
                        'name' => 'ROXCUSTOMERSELLER_POINT_COEFICIENT',
                        'label' => $this->l('Coeficient'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'ROXCUSTOMERSELLER_ACTIVE_MODE' => Configuration::get('ROXCUSTOMERSELLER_ACTIVE_MODE', true),
            'ROXCUSTOMERSELLER_ACCOUNT_EMAIL' => Configuration::get('ROXCUSTOMERSELLER_ACCOUNT_EMAIL', 'michal.bezecny@eode.eu'),
            'ROXCUSTOMERSELLER_ACCOUNT_PASSWORD' => Configuration::get('ROXCUSTOMERSELLER_ACCOUNT_PASSWORD', null),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    public function hookActionCartSave()
    {
        /* Place your code here. */
    }

    public function hookActionOrderSlipAdd()
    {
        /* Place your code here. */
    }

    public function hookActionOrderStatusUpdate()
    {
        /* Place your code here. */
    }

    public function hookDisplayAdminCustomers()
    {
        /* Place your code here. */
    }
}
