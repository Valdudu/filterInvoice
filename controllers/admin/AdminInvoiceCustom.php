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
        if (Tools::isSubmit('submitOrder')) {
            $this->processGenerateInvoice();
        }
        $this->setTemplate('form.tpl');

    }
    public function ajaxProcessSearchCustomer()
    {
        die(json_encode(Db::getInstance()->executeS('select concat(firstname, \' \', lastname) as complete_name, id_customer from ' . _DB_PREFIX_ . 'customer where concat(firstname, \' \', lastname) like \'%' . Tools::getValue('valeur') . '%\'')));
    }
    private function processGenerateInvoice()
    {
        $invoice_collection = $this->getInvoiceCollection();
        if (!count($invoice_collection)) {
            die($this->trans('No invoice was found.', [], 'Admin.Orderscustomers.Notification'));
        }
        $this->generatePDF($invoice_collection, PDF::TEMPLATE_INVOICE);
        //return $this->getInvoiceCollection();
    }
    private function getInvoiceCollection()
    {
        $customerRestriction = (Tools::getValue('customerId') != ('0' && '') ? 'AND o.id_customer=' . Tools::getValue('customerId') : '');
        $stateRestriction = '';
        if (is_array(Tools::getValue('order_states')) > 0) {
            $toEnd = sizeof(Tools::getValue('order_states'));
            $stateRestriction = ' AND o.current_state in (';
            foreach (Tools::getValue('order_states') as $state) {
                $stateRestriction .= $state;
                if (0 !== --$toEnd) {
                    $stateRestriction .= ', ';
                }
            }
            $stateRestriction .= ')';
        }
        $collection = Db::getInstance()->executeS('SELECT oi.*
            FROM `' . _DB_PREFIX_ . 'order_invoice` oi
            LEFT JOIN `' . _DB_PREFIX_ . 'orders` o ON (o.`id_order` = oi.`id_order`)
            WHERE DATE_ADD(oi.date_add, INTERVAL -1 DAY) <= \'' . pSQL(Tools::getValue('end')) . '\'
            AND oi.date_add >= \'' . pSQL(Tools::getValue('start')) . '\'
            AND oi.number > 0
            ' . $customerRestriction . '
            ' . $stateRestriction . '
            ORDER BY oi.date_add ASC');
        return ObjectModel::hydrateCollection('OrderInvoice', $collection);


    }
    public function generatePDF($object, $template)
    {
        $pdf = new PDF($object, $template, Context::getContext()->smarty);
        $pdf->render();
    }
}