<?php
////////////////////////////////////////////////////////////////////////////
//                              SnowCMS v2.0                              //
//                           By the SnowCMS Team                          //
//                             www.snowcms.com                            //
//            Released under the Microsoft Reciprocal License             //
//                 www.opensource.org/licenses/ms-rl.html                 //
////////////////////////////////////////////////////////////////////////////
//                                                                        //
//       SnowCMS originally pawned by soren121 started in early 2008      //
//                                                                        //
////////////////////////////////////////////////////////////////////////////
//                                                                        //
//                  SnowCMS v2.0 began in November 2009                   //
//                                                                        //
////////////////////////////////////////////////////////////////////////////
//                       File version: SnowCMS 2.0                        //
////////////////////////////////////////////////////////////////////////////

if(!defined('INSNOW'))
{
  die('Nice try...');
}

if(!admin_prompt_required() && admin_show_sidebar() && empty(api()->context['cp_access_denied']))
{
	echo '
			</div>
			<div class="break">
			</div>';
}
?>
		</div>
		<!-- /END CONTENT -->
		<div id="footer-container">
			<div id="footer-text">
				<p><?php echo l('Powered by <a href="http://www.snowcms.com/" target="_blank" title="SnowCMS">SnowCMS</a> v%s.', settings()->get('version', 'string')); ?></p>
				<p><?php echo l('Page created in %s seconds with %u queries.', round(microtime(true) - starttime, 3), db()->num_queries); ?></p>
			</div>
<?php
if(!admin_prompt_required() && empty(api()->context['cp_access_denied']))
{
?>
			<div id="jump-to">
				<form action="#" method="post" onsubmit="return false;">
					<select name="jump_to_select" onchange="this.form.go.click();">
						<option><?php echo l('Control Panel'); ?></option>
<?php
		// There can be up to two levels of navigation specified in the icons
		// array, but we only show the first level in this drop down... So we
		// need to figure out whether the currently specified area is at the
		// first level or not.
		$area_id = admin_current_area();

		if(isset($GLOBALS['icon_map']['index'][$area_id]))
		{
			$index = $GLOBALS['icon_map']['index'][$area_id];

			// If there is just one item, then we're at the first level.
			if(count($index) > 1)
			{
				$area_id = array_pop($index);
			}
		}

    // Display the drop down for quick navigation.
    foreach($GLOBALS['icons'] as $icon_group => $icon)
    {
      echo '
        <optgroup label="', htmlchars($icon_group), '">';

      foreach($icon as $i)
      {
        echo '
          <option value="', urlencode(htmlspecialchars_decode($i['href'])), '"', (!empty($i['id']) && $area_id == $i['id'] ? ' selected="selected"' : ''), '>', $i['label'], '</option>';
      }

      echo '
        </optgroup>';
    }

    echo '
      </select>
      <input type="submit" name="go" title="', l('Go'), '" value="', l('Go'), '" onclick="if(this.form.jump_to_select.value == \'', l('Control Panel'), '\') { location.href = \'', baseurl, '/index.php?action=admin\'; } else { location.href = decodeURIComponent(this.form.jump_to_select.value); }" />';
?>
				</form>
			</div>
<?php
}
?>
			<div class="break">
			</div>
		</div>
	</div>
</body>
</html>