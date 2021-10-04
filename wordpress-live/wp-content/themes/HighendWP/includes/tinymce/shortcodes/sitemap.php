<script type="text/javascript">
var SitemapDialog = {
	local_ed : 'ed',
	init : function(ed) {
		SitemapDialog.local_ed = ed;
		tinyMCEPopup.resizeToInnerSize();
	},
	insert : function insertButton(ed) {
	 
		// Try and remove existing style / blockquote
		tinyMCEPopup.execCommand('mceRemoveNode', false, null);
		
		var depth = jQuery('#sitemap-depth').val();
		var extra_class = jQuery('#sitemap-extra-class').val();
		var output = '';
		
		// setup the output of our shortcode
		output += '[sitemap';

		if (depth != '') {
			output += ' depth=\"'+depth+'\"';
		}

		if (extra_class != '') {
			output += ' class=\"'+extra_class+'\"';
		}
		output += ']';
		tinyMCEPopup.execCommand('mceReplaceContent', false, output);
		 
		// Return
		tinyMCEPopup.close();
	}
};
tinyMCEPopup.onInit.add(SitemapDialog.init, SitemapDialog);
</script>
<form action="/" method="get" accept-charset="utf-8">

        <div class="form-section clearfix">
            <label for="sitemap-depth">Sitemap Depth.<br/><small>Specify how many child levels to show. Leave empty for default value. Default: 2.</small></label>
            <input type="text" name="sitemap-depth" id="sitemap-depth" placeholder="Example: 1"></textarea>
        </div>

        <div class="form-section clearfix">
            <label for="sitemap-extra-class">Extra Class.<br/><small>Enter additional CSS class. Separate classes with space. Eg: my-class second-class</small></label>
            <input name="sitemap-extra-class" id="sitemap-extra-class" type="text" />
        </div>
         
    <a href="javascript:SitemapDialog.insert(SitemapDialog.local_ed)" id="insert" style="display: block;">Insert</a>
    
</form>