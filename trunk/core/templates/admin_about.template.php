<?php
if(!defined('INSNOW'))
{
	die('Nice try...');
}

	echo '
	<h3><img src="', theme()->url(), '/style/images/about-small.png" alt="" /> ', l('About SnowCMS'), '</h3>
	<p>', l('SnowCMS is a light, powerful and free content management system, otherwise known as a CMS. It has a powerful plugin system allowing you to install plugins which make minor changes to how your site works, but also major changes such as adding forums, blogs, and more. By default SnowCMS only has a few features such as member management and a plugin system, meaning you can have your site with as few or as many features as you want, and nothing more. SnowCMS is written in the popular language <abbr title="PHP: Hypertext Preprocessor">PHP</abbr> and uses MySQL or SQLite for storage.'), '</p>
	<p>', l('SnowCMS is released under the <a href="http://www.gnu.org/licenses/quick-guide-gplv3.html" title="GNU General Public License v3">GPL v3</a> license, meaning you are free to use, modify and redistribute SnowCMS if you so please. While you are entitled to these freedoms, please keep in mind that no warranty is provided for this software, so we (the <a href="http://www.snowcms.com/" target="_blank">SnowCMS Developer Team</a>) are not responsible for anything that may occur while using this software, and neither is anyone else.'), '</p>
	<p>', l('If you are wondering if you can remove the &quot;powered by SnowCMS&quot; links in the footer of each page, the answer is yes. However we do reserve the right not to provide support for sites that remove such links from their website on the official <a href="http://www.snowcms.com/" target="_blank">SnowCMS website</a>.'), '</p>

	<h3>', l('Developers'), '</h3>
	<p>', l('The following people are currently, or have been previously, major contributors to the <a href="http://www.snowcms.com/" title="SnowCMS">SnowCMS</a> project, we thank them for all their help!'), '</p>
	<ul>
		<li>Ian Aldrighetti (aldo) - ', l('Lead Developer of SnowCMS v0.7, 1.0 and 2.0'), '</li>
	</ul>

	<h3>', l('Credits'), '</h3>
	<p>', l('There are a few places where SnowCMS used the work of others, and this section is dedicated to their credit!'), '</p>
	<ul>
		<li>', l('Control Panel icons are from the <a href="http://kde-look.org/content/show.php/Oxygen+Icons?content=74184" title="Oxygen Icon set" target="_blank">Oxygen Icon set</a>.'), '</li>
	</ul>

	<h3><img src="', theme()->url(), '/style/images/about-small.png" alt="" /> ', l('System Information'), '</h3>
	<p><strong>Operating system:</strong> ', admin_get_os_information(), '<br />
		 <strong>Server software:</strong> ', admin_get_software_information(), '<br />
		 <strong>PHP version:</strong> ', PHP_VERSION, '<br />
		 <strong>Database:</strong> ', db()->type, '<br />
		 <strong>Database version:</strong> ', db()->version(), '</p>';
?>