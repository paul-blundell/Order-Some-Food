<div class="takeaway">
    <div class="logo">
        <img src="<?=base_url()?>images/nologo.gif" alt="No logo available" />
    </div>
    <div class="name">
    <h2><?=$takeaway->name ?></h2>
    <p><?=$takeaway->description ?></p>
    </div>
    <div class="clear"></div>
</div>
<h3>Your Order</h3>
<div id="order-list">
    <?php foreach ($order AS $id=>$items): ?>
    <div class="item">
    <div class="name">
        <?=$items['name']?> x <?=$items['qty']?>
    </div>
    <a href="javascript:void()" onclick="removeItem(<?=$items['id']?>)"><img class="remove" src="<?=base_url()?>images/remove.png" alt="remove" title="Remove Item" /></a>
    <div class="price">
        <?= $this->config->item('price_prefix'); ?><?=money_format('%i',floatval($items['price']*$items['qty']))?><?= $this->config->item('price_suffix'); ?>
    </div>
    <div class="clear"></div>
    </div>
    <?php endforeach; ?>
    <div class="delivery">
       Delivery <span class="price"><?= $this->config->item('price_prefix'); ?><?=money_format('%i',floatval($takeaway->deliveryCharge))?><?= $this->config->item('price_suffix'); ?></span>
    </div>
    <div class="total">
       Final Total <span class="price"><strong><?= $this->config->item('price_prefix'); ?><?=money_format('%i',floatval($total))?><?= $this->config->item('price_suffix'); ?></strong></span>
    </div>
</div>
<div id="additional-comments">
    <h3>Additional Comments</h3>
    <p>Allergic to something? Say so here.</p>
    <textarea name="comments" rows="10" cols="30"></textarea>
</div>
<div id="checkout">
    <?php if(!$user['loggedIn']): ?>
    <h4>Existing Member?</h4>
    <div id="existing-member">
        <form method="post" action="<?=base_url()?>users/login">
        <label>Email Address</label><input type="text" name="email" />
        <label>Password</label><input type="text" name="pass" />
        <input type="hidden" name="returnurl" value="<?=base_url()?>order/takeaway/<?=$takeaway->takeawayId?>" />
        <input type="submit" name="login" value="Login" />
        </form>
    </div>
    <?php endif; ?>
    <h4>Delivery Details</h4>
    <div id="delivery-details">
        <label>Address Line 1</label><input type="text" name="address1" value="<?=$user['address1']?>"/>
        <label>Address Line 2</label><input type="text" name="address2" value="<?=$user['address2']?>"/>
        <label>Town/City</label><input type="text" name="town" value="<?=$user['town']?>"/>
        <label>Post Code</label><input type="text" name="postcode" value="<?=$user['postcode']?>"/>
        <label>Telephone</label><input type="text" name="telephone" value="<?=$user['telephone']?>"/><br/><br/>
        <?php if(!$user['loggedIn']): ?>
        <input type="checkbox" class="register-box" name="register"/> <span class="small">Register to remember details and orders</span><br/><br/>
        <div class="register">
            <label>Email Address</label><input type="text" name="email" />
            <label>Password</label><input type="text" name="pass" />
            <label>Confirm Password</label><input type="text" name="pass-confirm" />
        </div>
        <?php endif; ?>
        <br/><br/>
        <label>Delivery</label><select class="type" name="delivery">
            <option value="delivery">Pay Cash on Delivery</option>
            <option value="collection">Collect in Person</option>
        </select>
    </div>
    
    <div class="button"><a href="#">Place Order</a></div>
</div>
<div class="clear"></div>


