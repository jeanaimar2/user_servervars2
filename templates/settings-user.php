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
\OCP\Util::addScript('gtu', 'gtu');
\OCP\Util::addScript('core', 'lostpassword');
?>
<div class="section">
	<h2><?php p($l->t('Useful Informations For Synchronization'));?></h2>
	<div>
		<label><?php p($l->t('User ID'));?>:</label><input type="text" id="user" value="<?php p($_['uid']);?>" disabled>
		<p><em><?php p($l->t('This user ID will be requested to set up synchronization'));?></em></p>
		<p><em><?php p($l->t("It can't be modified"));?></em></p>
	</div>
	<div>
		<label><?php p($l->t('Password')); ?>:</label>
		<button id="lost-password">
			<?php p($l->t('Send Email To Reset My Password')); ?>
		</button>

		<p><em><?php p($l->t("If you don't know your password, ask for an email to reset password"));?></em></p>		
	</div>
</div>