<?php
/**
 * ownCloud - 
 *
 * @author Marc DeXeT
 * @copyright 2014 DSI CNRS https://www.dsi.cnrs.fr
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
// VERSION AVEC JQUERY
\OCP\Util::addScript('user_servervars2', 'usv2');
\OCP\Util::addStyle('user_servervars2', 'servervars');
?>
<div id="usv2settings" class="section">
	<h2><?php p($l->t('User ServerVars II'));?></h2>
	<form name="usv2Form" id="usv2FormID">
	<div id="usv2tabs">
		<ul>
			<li><a href="#usv2tabs-1">General</a></li>
			<li><a href="#usv2tabs-2">Token</a></li>
		</ul>
		<fieldset id="usv2tabs-1">
			<div class="block"><label><?php p($l->t('Single Sign On Url'))?></label><input type="text" name="sso_url" value="<?php p($_['sso_url'])?>"></div>
			<div class="block"><label><?php p($l->t('Single Log Out Url'))?></label><input type="text" name="slo_url" value="<?php p($_['slo_url'])?>" ></div>
			<div class="block"><label><?php p($l->t('Auto Create User'))?></label><input type="checkbox" name="auto_create_user" value="1" <?php if($_['auto_create_user']) { p('checked="checked"'); }?> ></div>
			<div class="block"><label><?php p($l->t('Update User Data'))?></label><input type="checkbox" name="update_user_data" value="1" <?php if($_['update_user_data']) { p('checked="checked"'); }?> ></div>
			<div class="block"><label><?php p($l->t('Stop If Empty'))?></label><input type="checkbox" name="stop_if_empty" value="1" <?php if($_['stop_if_empty']) { p('checked="checked"'); }?> ></div>
		</fieldset>
		<fieldset id="usv2tabs-2">
			<div class="block"><label><?php p($l->t('Token Class'))?></label><input type="text" name="tokens_class" value="<?php p($_['tokens_class'])?>" ></div>
			<div class="block"><label><?php p($l->t('tokens Provider Id'))?></label><input type="text" name="tokens_provider_id" value="<?php p($_['tokens_provider_id'])?>" ></div>
			<div class="block"><label><?php p($l->t('tokens User id'))?></label><input type="text" name="tokens_user_id" value="<?php p($_['tokens_user_id'])?>" ></div>
			<div class="block"><label><?php p($l->t('tokens Display Name'))?></label><input type="text" name="tokens_display_name" value="<?php p($_['tokens_display_name'])?>" ></div>
			<div class="block"><label><?php p($l->t('tokens Email'))?></label><input type="text" name="tokens_email" value="<?php p($_['tokens_email'])?>" ></div>
		</fieldset>
		</div>
	</form>
</div>
