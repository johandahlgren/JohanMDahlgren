<label for="componentName">Page template</label>
<select id="componentName" name="data_pageTemplate" data-value="<?php print getValueFromString("pageTemplate", $data) ?>">
	<option value="x">Sida A</option>
	<option value="y">Sida B</option>
	<option value="x">Sida C</option>
</select>
<label for="description">Description</label>
<input type="text" id="description" name="data_description" value="<?php print getValueFromString("description", $data) ?>" /><br/>	