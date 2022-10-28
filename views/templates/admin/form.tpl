<h1>tewt</h1>
<div class="panel">
    <form id='module_form' method='POST' action='' validate class='defaultForm form-horizontal'>
        <div class="panel-heading">		
            <i class="material-icons">schedule</i>            
            {l s='Filtre de factures' mod='filterInvoice'}
        </div>
        <div class="card-text">
            <div class="form-group row">
                <label class="control-label col-lg-3">
                    Choisir un client
                </label>
                <div class="col-lg-7">
                    <input type="hidden" name="customerId">
                    <input type="text" id="customerSearch" name="customerName" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                            <label class="control-label col-lg-3">
                    du
                </label>
                <div class="col-lg-3">
                    <input type="date" class="form-control" data-format="YYYY-MM-DD" name="start" value="{$now}">
                </div>
                <label class="control-label col-lg-1">
                    Au
                </label>
                <div class="col-lg-3">
                    <input type="date" class="form-control" data-format="YYYY-MM-DD" name="end" value="{$now}">
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-7 col-lg-offset-3">
                    {foreach $states as $s}
                        <div class="checkbox col-lg-6">                                            
                            <div class="md-checkbox">
                                <label class="">
                                    <input type="checkbox"  name="order_states[]" value="{$s.id_order_state}">
                                    <span>{$s.name}</span>
                                </label>
                            </div>
                        </div>
                    {/foreach}
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" value="1" id="module_form_submit_btn" name="submitOrder" class="btn generate btn-primary pull-right">
                Génerer un fichier PDF
            </button>
        </div> 
    </form>
</div>