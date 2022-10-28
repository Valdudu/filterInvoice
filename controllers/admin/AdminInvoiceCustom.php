<?php
class AdminInvoiceCustomController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        parent::__construct();
        $this->token = Tools::getAdminTokenLite('AdminInvoiceCustom');
    }
    public function initContent()
    {
        parent::initContent();
        $this->addJqueryUi('ui.autocomplete');
        $this->addJS(_PS_MODULE_DIR_ . 'filterInvoice/views/js/back.js');
        $this->addCSS(_PS_MODULE_DIR_ . 'filterInvoice/views/css/back.css');
        Media::addJsDef(['moduleAdminLink' => $this->context->link->getAdminLink('AdminInvoiceCustom')]);
        $this->context->smarty->assign([
            'back_url' => $this->context->link->getAdminLink('AdminInvoiceCustom'),
            'now' => date('Y-m-d'),
            'states' => OrderState::getOrderStates($this->context->language->id),
        ]);
        $this->setTemplate('form.tpl');

    }
    public function ajaxProcessSearchCustomer()
    {
        die(json_encode(Db::getInstance()->executeS('select concat(firstname, \' \', lastname) as complete_name, id_customer from ' . _DB_PREFIX_ . 'customer where concat(firstname, \' \', lastname) like \'%' . Tools::getValue('valeur') . '%\'')));
    }
}